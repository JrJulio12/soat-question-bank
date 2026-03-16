<?php

namespace App\Http\Controllers;

use App\Models\Bncc;
use App\Models\Option;
use App\Models\Question;
use App\Models\Discipline;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Question::with(['bnccs', 'subjects']);

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $questions = $query->latest()->paginate(15);
        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Load hierarchical data for BNCCs
        $disciplines = Discipline::with(['units.knowledges', 'topics.chapters.subjects'])->get()->map(function ($discipline) {
            $discipline->stage = $discipline->stage?->value;
            return $discipline;
        });
        $units = \App\Models\Unit::with(['discipline', 'knowledges'])->get();
        $knowledges = \App\Models\Knowledge::with(['unit.discipline', 'bnccs'])->get();
        $bnccs = Bncc::with(['discipline', 'knowledges.unit.discipline'])->get()->map(function ($bncc) {
            $bncc->stage = $bncc->stage?->value;
            if ($bncc->discipline) {
                $bncc->discipline->stage = $bncc->discipline->stage?->value;
            }
            return $bncc;
        });
        
        // Load hierarchical data for Subjects
        $topics = \App\Models\Topic::with(['discipline', 'chapters.subjects'])->get()->map(function ($topic) {
            if ($topic->discipline) {
                $topic->discipline->stage = $topic->discipline->stage?->value;
            }
            return $topic;
        });
        $chapters = \App\Models\Chapter::with(['topic.discipline', 'subjects'])->get()->map(function ($chapter) {
            if ($chapter->topic && $chapter->topic->discipline) {
                $chapter->topic->discipline->stage = $chapter->topic->discipline->stage?->value;
            }
            return $chapter;
        });
        $subjects = Subject::with(['chapter.topic.discipline'])->get()->map(function ($subject) {
            if ($subject->chapter && $subject->chapter->topic && $subject->chapter->topic->discipline) {
                $subject->chapter->topic->discipline->stage = $subject->chapter->topic->discipline->stage?->value;
            }
            return $subject;
        });
        
        return view('questions.create', compact(
            'disciplines',
            'units',
            'knowledges',
            'bnccs',
            'topics',
            'chapters',
            'subjects'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Parse options JSON if it's a string
        if ($request->has('options') && is_string($request->input('options'))) {
            $optionsJson = $request->input('options');
            if (!empty($optionsJson)) {
                $options = json_decode($optionsJson, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($options)) {
                    $request->merge(['options' => $options]);
                }
            }
        }

        $rules = [
            'stem' => 'required|string',
            'stage' => 'required|in:EF,EM',
            'type' => 'required|in:multiple_choice,multi_select,true_false,open',
            'status' => 'required|in:draft,published',
            'bnccs' => 'required|array|min:1',
            'bnccs.*' => 'exists:bnccs,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ];

        if ($request->input('stage') === 'EF') {
            $rules['discipline_ids'] = 'required|array|min:1';
            $rules['discipline_ids.*'] = 'exists:disciplines,id';
        }

        // Add validation based on question type
        if ($request->input('type') === 'open') {
            $rules['answer_text'] = 'nullable|string';
        } else {
            $rules['options'] = ['required', 'array', 'min:2'];
            $rules['options.*.text'] = 'required|string|max:255';
            $rules['options.*.is_correct'] = 'boolean';
            $rules['options.*.order'] = 'required|integer|min:1';

            // Type-specific validation
            if ($request->input('type') === 'true_false') {
                $rules['options'][] = 'size:2';
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn($option) => $option['is_correct'])) !== 1) {
                        $fail('Exactly one option must be marked as correct for True/False questions.');
                    }
                    if ($value[0]['text'] !== 'True' || $value[1]['text'] !== 'False') {
                        $fail('True/False options must be "True" and "False".');
                    }
                };
            } elseif ($request->input('type') === 'multiple_choice') {
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn($option) => $option['is_correct'])) !== 1) {
                        $fail('Exactly one option must be marked as correct for Multiple Choice questions.');
                    }
                };
            } elseif ($request->input('type') === 'multi_select') {
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn($option) => $option['is_correct'])) === 0) {
                        $fail('At least one option must be marked as correct for Multi Select questions.');
                    }
                };
            }
        }

        $validated = $request->validate($rules);

        $question = Question::create($validated);

        if ($request->filled('bnccs')) {
            $question->bnccs()->sync($request->bnccs);
        }

        if ($request->has('subjects')) {
            $question->subjects()->sync($request->subjects);
        }

        // Create options if type is not "open"
        if ($request->input('type') !== 'open' && isset($validated['options'])) {
            foreach ($validated['options'] as $optionData) {
                $question->options()->create($optionData);
            }
        }

        // Return JSON response for axios
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Question created successfully.',
                'question' => $question
            ]);
        }

        return redirect()->route('questions.index')
            ->with('success', 'Question created successfully.');
    }

    /**
     * AJAX endpoint to fetch units by discipline IDs
     */
    public function getUnitsByDisciplines(Request $request)
    {
        $request->validate([
            'discipline_ids' => 'required|array',
            'discipline_ids.*' => 'exists:disciplines,id',
            'stage' => 'nullable|in:EF,EM'
        ]);

        $disciplineIds = $request->discipline_ids;
        $stage = $request->stage;

        $query = \App\Models\Unit::with(['discipline'])
            ->whereIn('discipline_id', $disciplineIds);

        // Filter by stage if provided
        if ($stage) {
            $query->whereHas('discipline', function ($q) use ($stage) {
                $q->where('stage', $stage)->orWhereNull('stage');
            });
        }

        $units = $query->get()->map(function ($unit) {
            return [
                'id' => $unit->id,
                'name' => $unit->name,
                'discipline_id' => $unit->discipline_id,
                'discipline' => $unit->discipline ? [
                    'id' => $unit->discipline->id,
                    'name' => $unit->discipline->name,
                    'stage' => $unit->discipline->stage?->value
                ] : null
            ];
        });

        return response()->json($units);
    }

    /**
     * AJAX endpoint to fetch knowledges by unit IDs
     */
    public function getKnowledgesByUnits(Request $request)
    {
        $request->validate([
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id'
        ]);

        $unitIds = $request->unit_ids;

        $knowledges = \App\Models\Knowledge::with(['unit.discipline'])
            ->whereIn('unit_id', $unitIds)
            ->get()
            ->map(function ($knowledge) {
                return [
                    'id' => $knowledge->id,
                    'name' => $knowledge->name,
                    'unit_id' => $knowledge->unit_id,
                    'unit' => $knowledge->unit ? [
                        'id' => $knowledge->unit->id,
                        'name' => $knowledge->unit->name,
                        'discipline_id' => $knowledge->unit->discipline_id
                    ] : null
                ];
            });

        return response()->json($knowledges);
    }

    /**
     * AJAX endpoint to fetch BNCCs by knowledge IDs, discipline IDs, or stage.
     * For EF: pass discipline_ids to get BNCCs for that discipline.
     * For EM: can pass knowledge_ids to filter, or just stage.
     */
    public function getBnccs(Request $request)
    {
        $request->validate([
            'knowledge_ids' => 'nullable|array',
            'knowledge_ids.*' => 'exists:knowledges,id',
            'discipline_ids' => 'nullable|array',
            'discipline_ids.*' => 'exists:disciplines,id',
            'stage' => 'required|in:EF,EM'
        ]);

        $knowledgeIds = $request->input('knowledge_ids', []);
        $disciplineIds = $request->input('discipline_ids', []);
        $stage = $request->stage;

        $query = Bncc::with(['discipline', 'knowledges.unit.discipline'])
            ->where('stage', $stage);

        // Filter by discipline(s) when provided (e.g. for EF flow)
        if (!empty($disciplineIds) && is_array($disciplineIds)) {
            $query->whereIn('discipline_id', $disciplineIds);
        }

        // If knowledge IDs provided and not empty, filter by them (e.g. for EM flow)
        if (!empty($knowledgeIds) && is_array($knowledgeIds) && count($knowledgeIds) > 0) {
            $query->whereHas('knowledges', function ($q) use ($knowledgeIds) {
                $q->whereIn('knowledges.id', $knowledgeIds);
            });
        }

        $bnccs = $query->get()->map(function ($bncc) {
            return [
                'id' => $bncc->id,
                'code' => $bncc->code,
                'description' => $bncc->description,
                'stage' => $bncc->stage->value,
                'discipline' => $bncc->discipline ? [
                    'id' => $bncc->discipline->id,
                    'name' => $bncc->discipline->name,
                    'stage' => $bncc->discipline->stage?->value
                ] : null,
                'knowledges' => $bncc->knowledges->map(function ($knowledge) {
                    return [
                        'id' => $knowledge->id,
                        'name' => $knowledge->name,
                        'unit_id' => $knowledge->unit_id,
                        'unit' => $knowledge->unit ? [
                            'id' => $knowledge->unit->id,
                            'name' => $knowledge->unit->name,
                            'discipline_id' => $knowledge->unit->discipline_id
                        ] : null
                    ];
                })
            ];
        });

        return response()->json($bnccs);
    }

    /**
     * AJAX endpoint to fetch topics by discipline IDs
     */
    public function getTopicsByDisciplines(Request $request)
    {
        $request->validate([
            'discipline_ids' => 'required|array',
            'discipline_ids.*' => 'exists:disciplines,id',
            'stage' => 'nullable|in:EF,EM'
        ]);

        $disciplineIds = $request->discipline_ids;
        $stage = $request->stage;

        $query = \App\Models\Topic::with(['discipline'])
            ->whereIn('discipline_id', $disciplineIds);

        // Filter by stage if provided
        if ($stage) {
            $query->whereHas('discipline', function ($q) use ($stage) {
                $q->where('stage', $stage)->orWhereNull('stage');
            });
        }

        $topics = $query->get()->map(function ($topic) {
            return [
                'id' => $topic->id,
                'name' => $topic->name,
                'discipline_id' => $topic->discipline_id,
                'discipline' => $topic->discipline ? [
                    'id' => $topic->discipline->id,
                    'name' => $topic->discipline->name,
                    'stage' => $topic->discipline->stage?->value
                ] : null
            ];
        });

        return response()->json($topics);
    }

    /**
     * AJAX endpoint to fetch chapters by topic IDs
     */
    public function getChaptersByTopics(Request $request)
    {
        $request->validate([
            'topic_ids' => 'required|array',
            'topic_ids.*' => 'exists:topics,id'
        ]);

        $topicIds = $request->topic_ids;

        $chapters = \App\Models\Chapter::with(['topic.discipline'])
            ->whereIn('topic_id', $topicIds)
            ->get()
            ->map(function ($chapter) {
                return [
                    'id' => $chapter->id,
                    'name' => $chapter->name,
                    'topic_id' => $chapter->topic_id,
                    'topic' => $chapter->topic ? [
                        'id' => $chapter->topic->id,
                        'name' => $chapter->topic->name,
                        'discipline_id' => $chapter->topic->discipline_id
                    ] : null
                ];
            });

        return response()->json($chapters);
    }

    /**
     * AJAX endpoint to fetch subjects by chapter IDs
     */
    public function getSubjectsByChapters(Request $request)
    {
        $request->validate([
            'chapter_ids' => 'required|array',
            'chapter_ids.*' => 'exists:chapters,id'
        ]);

        $chapterIds = $request->chapter_ids;

        $subjects = Subject::with(['chapter.topic.discipline'])
            ->whereIn('chapter_id', $chapterIds)
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'chapter_id' => $subject->chapter_id,
                    'chapter' => $subject->chapter ? [
                        'id' => $subject->chapter->id,
                        'name' => $subject->chapter->name,
                        'topic_id' => $subject->chapter->topic_id
                    ] : null
                ];
            });

        return response()->json($subjects);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question): View
    {
        $question->load(['bnccs.discipline', 'subjects.chapter.topic', 'options']);
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question): View
    {
        // Load same hierarchical data as create() for the Vue form
        $disciplines = Discipline::with(['units.knowledges', 'topics.chapters.subjects'])->get()->map(function ($discipline) {
            $discipline->stage = $discipline->stage?->value;
            return $discipline;
        });
        $units = \App\Models\Unit::with(['discipline', 'knowledges'])->get();
        $knowledges = \App\Models\Knowledge::with(['unit.discipline', 'bnccs'])->get();
        $bnccs = Bncc::with(['discipline', 'knowledges.unit.discipline'])->get()->map(function ($bncc) {
            $bncc->stage = $bncc->stage?->value;
            if ($bncc->discipline) {
                $bncc->discipline->stage = $bncc->discipline->stage?->value;
            }
            return $bncc;
        });
        $topics = \App\Models\Topic::with(['discipline', 'chapters.subjects'])->get()->map(function ($topic) {
            if ($topic->discipline) {
                $topic->discipline->stage = $topic->discipline->stage?->value;
            }
            return $topic;
        });
        $chapters = \App\Models\Chapter::with(['topic.discipline', 'subjects'])->get()->map(function ($chapter) {
            if ($chapter->topic && $chapter->topic->discipline) {
                $chapter->topic->discipline->stage = $chapter->topic->discipline->stage?->value;
            }
            return $chapter;
        });
        $subjects = Subject::with(['chapter.topic.discipline'])->get()->map(function ($subject) {
            if ($subject->chapter && $subject->chapter->topic && $subject->chapter->topic->discipline) {
                $subject->chapter->topic->discipline->stage = $subject->chapter->topic->discipline->stage?->value;
            }
            return $subject;
        });

        $question->load(['options', 'bnccs.knowledges.unit', 'subjects.chapter.topic']);

        $questionForVue = [
            'id' => $question->id,
            'stem' => $question->stem,
            'answer_text' => $question->answer_text ?? '',
            'stage' => $question->stage->value,
            'type' => $question->type->value,
            'status' => $question->status->value,
            'options' => $question->options->sortBy('order')->values()->map(fn ($o) => [
                'text' => $o->text,
                'is_correct' => $o->is_correct,
                'order' => $o->order,
            ])->values()->all(),
            'bnccs' => $question->bnccs->map(fn ($b) => [
                'id' => $b->id,
                'discipline_id' => $b->discipline_id,
                'knowledges' => $b->knowledges->map(fn ($k) => [
                    'id' => $k->id,
                    'unit_id' => $k->unit_id,
                    'unit' => $k->unit ? ['discipline_id' => $k->unit->discipline_id] : null,
                ])->all(),
            ])->all(),
            'subjects' => $question->subjects->map(fn ($s) => [
                'id' => $s->id,
                'chapter_id' => $s->chapter_id,
                'chapter' => $s->chapter ? [
                    'topic_id' => $s->chapter->topic_id,
                    'topic' => $s->chapter->topic ? ['discipline_id' => $s->chapter->topic->discipline_id] : null,
                ] : null,
            ])->all(),
        ];

        return view('questions.edit', compact(
            'question',
            'disciplines',
            'units',
            'knowledges',
            'bnccs',
            'topics',
            'chapters',
            'subjects',
            'questionForVue'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        // Parse options JSON if it's a string (same as store)
        if ($request->has('options') && is_string($request->input('options'))) {
            $optionsJson = $request->input('options');
            if (! empty($optionsJson)) {
                $options = json_decode($optionsJson, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($options)) {
                    $request->merge(['options' => $options]);
                }
            }
        }

        $rules = [
            'stem' => 'required|string',
            'stage' => 'required|in:EF,EM',
            'type' => 'required|in:multiple_choice,multi_select,true_false,open',
            'status' => 'required|in:draft,published',
            'bnccs' => 'required|array|min:1',
            'bnccs.*' => 'exists:bnccs,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ];

        if ($request->input('stage') === 'EF') {
            $rules['discipline_ids'] = 'required|array|min:1';
            $rules['discipline_ids.*'] = 'exists:disciplines,id';
        }

        if ($request->input('type') === 'open') {
            $rules['answer_text'] = 'nullable|string';
        } else {
            $rules['options'] = ['required', 'array', 'min:2'];
            $rules['options.*.text'] = 'required|string|max:255';
            $rules['options.*.is_correct'] = 'boolean';
            $rules['options.*.order'] = 'required|integer|min:1';

            if ($request->input('type') === 'true_false') {
                $rules['options'][] = 'size:2';
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn ($option) => $option['is_correct'])) !== 1) {
                        $fail('Exactly one option must be marked as correct for True/False questions.');
                    }
                    if ($value[0]['text'] !== 'True' || $value[1]['text'] !== 'False') {
                        $fail('True/False options must be "True" and "False".');
                    }
                };
            } elseif ($request->input('type') === 'multiple_choice') {
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn ($option) => $option['is_correct'])) !== 1) {
                        $fail('Exactly one option must be marked as correct for Multiple Choice questions.');
                    }
                };
            } elseif ($request->input('type') === 'multi_select') {
                $rules['options'][] = function ($attribute, $value, $fail) {
                    if (count(array_filter($value, fn ($option) => $option['is_correct'])) === 0) {
                        $fail('At least one option must be marked as correct for Multi Select questions.');
                    }
                };
            }
        }

        $validated = $request->validate($rules);

        $question->update([
            'stem' => $validated['stem'],
            'stage' => $validated['stage'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'answer_text' => $validated['answer_text'] ?? null,
        ]);

        if ($request->has('bnccs')) {
            $question->bnccs()->sync($request->bnccs);
        } else {
            $question->bnccs()->sync([]);
        }

        if ($request->has('subjects')) {
            $question->subjects()->sync($request->subjects);
        } else {
            $question->subjects()->sync([]);
        }

        // Replace options: delete existing and create from request when type is not open
        $question->options()->delete();
        if ($request->input('type') !== 'open' && isset($validated['options'])) {
            foreach ($validated['options'] as $optionData) {
                $question->options()->create($optionData);
            }
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully.',
            ]);
        }

        return redirect()->route('questions.index')
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully.');
    }
}

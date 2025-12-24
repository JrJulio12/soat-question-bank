<?php

namespace App\Http\Controllers;

use App\Models\Bncc;
use App\Models\Option;
use App\Models\Question;
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
        $bnccs = Bncc::all();
        $subjects = Subject::all();
        return view('questions.create', compact('bnccs', 'subjects'));
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
            'bnccs' => 'nullable|array',
            'bnccs.*' => 'exists:bnccs,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ];

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

        if ($request->has('bnccs')) {
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
        $bnccs = Bncc::all();
        $subjects = Subject::all();
        $question->load(['bnccs', 'subjects']);
        return view('questions.edit', compact('question', 'bnccs', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'stem' => 'required|string',
            'answer_text' => 'nullable|string',
            'stage' => 'required|in:EF,EM',
            'type' => 'required|in:multiple_choice,multi_select,true_false,open',
            'status' => 'required|in:draft,published',
            'bnccs' => 'nullable|array',
            'bnccs.*' => 'exists:bnccs,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $question->update($validated);

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

<?php

namespace App\Http\Controllers;

use App\Models\Bncc;
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

        if ($request->has('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->has('status')) {
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
    public function store(Request $request): RedirectResponse
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

        $question = Question::create($validated);

        if ($request->has('bnccs')) {
            $question->bnccs()->sync($request->bnccs);
        }

        if ($request->has('subjects')) {
            $question->subjects()->sync($request->subjects);
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

<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Subject::with(['chapter.topic', 'questions']);

        if ($request->filled('chapter_id')) {
            $query->where('chapter_id', $request->chapter_id);
        }

        $subjects = $query->paginate(15);
        $chapters = Chapter::all();

        return view('subjects.index', compact('subjects', 'chapters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $chapters = Chapter::all();
        return view('subjects.create', compact('chapters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        Subject::create($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject): View
    {
        $subject->load(['chapter.topic.discipline', 'questions']);
        return view('subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        $chapters = Chapter::all();
        return view('subjects.edit', compact('subject', 'chapters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}

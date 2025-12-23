<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Chapter::with(['topic', 'subjects']);

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $chapters = $query->paginate(15);
        $topics = Topic::all();

        return view('chapters.index', compact('chapters', 'topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $topics = Topic::all();
        return view('chapters.create', compact('topics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
        ]);

        Chapter::create($validated);

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter): View
    {
        $chapter->load(['topic.discipline', 'subjects']);
        return view('chapters.show', compact('chapter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter): View
    {
        $topics = Topic::all();
        return view('chapters.edit', compact('chapter', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $chapter->update($validated);

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter): RedirectResponse
    {
        $chapter->delete();

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter deleted successfully.');
    }
}

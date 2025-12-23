<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Topic::with(['discipline', 'chapters']);

        if ($request->filled('discipline_id')) {
            $query->where('discipline_id', $request->discipline_id);
        }

        $topics = $query->paginate(15);
        $disciplines = Discipline::all();

        return view('topics.index', compact('topics', 'disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $disciplines = Discipline::all();
        return view('topics.create', compact('disciplines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discipline_id' => 'required|exists:disciplines,id',
        ]);

        Topic::create($validated);

        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic): View
    {
        $topic->load(['discipline', 'chapters.subjects']);
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic): View
    {
        $disciplines = Discipline::all();
        return view('topics.edit', compact('topic', 'disciplines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discipline_id' => 'required|exists:disciplines,id',
        ]);

        $topic->update($validated);

        return redirect()->route('topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic): RedirectResponse
    {
        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}

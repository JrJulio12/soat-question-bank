<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $disciplines = Discipline::with(['units', 'topics', 'bnccs'])->paginate(15);
        return view('disciplines.index', compact('disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('disciplines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'nullable|in:EF,EM',
        ]);

        Discipline::create($validated);

        return redirect()->route('disciplines.index')
            ->with('success', 'Discipline created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discipline $discipline): View
    {
        $discipline->load(['units.knowledges', 'topics.chapters.subjects', 'bnccs']);
        return view('disciplines.show', compact('discipline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline): View
    {
        return view('disciplines.edit', compact('discipline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discipline $discipline): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'nullable|in:EF,EM',
        ]);

        $discipline->update($validated);

        return redirect()->route('disciplines.index')
            ->with('success', 'Discipline updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline): RedirectResponse
    {
        $discipline->delete();

        return redirect()->route('disciplines.index')
            ->with('success', 'Discipline deleted successfully.');
    }
}

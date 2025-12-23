<?php

namespace App\Http\Controllers;

use App\Enums\Stage;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $series = Serie::with('bnccs')->orderBy('order')->paginate(15);
        return view('series.index', compact('series'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('series.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'required|in:EF,EM',
            'order' => 'nullable|integer|min:1',
        ]);

        Serie::create($validated);

        return redirect()->route('series.index')
            ->with('success', 'Serie created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Serie $serie): View
    {
        $serie->load('bnccs');
        return view('series.show', compact('serie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Serie $serie): View
    {
        return view('series.edit', compact('serie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Serie $serie): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stage' => 'required|in:EF,EM',
            'order' => 'nullable|integer|min:1',
        ]);

        $serie->update($validated);

        return redirect()->route('series.index')
            ->with('success', 'Serie updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Serie $serie): RedirectResponse
    {
        $serie->delete();

        return redirect()->route('series.index')
            ->with('success', 'Serie deleted successfully.');
    }
}

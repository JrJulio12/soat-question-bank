<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Knowledge::with(['unit', 'bnccs']);

        if ($request->has('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $knowledges = $query->paginate(15);
        $units = Unit::all();

        return view('knowledges.index', compact('knowledges', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $units = Unit::all();
        return view('knowledges.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'unit_id' => 'required|exists:units,id',
        ]);

        Knowledge::create($validated);

        return redirect()->route('knowledges.index')
            ->with('success', 'Knowledge created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Knowledge $knowledge): View
    {
        $knowledge->load(['unit.discipline', 'bnccs']);
        return view('knowledges.show', compact('knowledge'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Knowledge $knowledge): View
    {
        $units = Unit::all();
        return view('knowledges.edit', compact('knowledge', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Knowledge $knowledge): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'unit_id' => 'required|exists:units,id',
        ]);

        $knowledge->update($validated);

        return redirect()->route('knowledges.index')
            ->with('success', 'Knowledge updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Knowledge $knowledge): RedirectResponse
    {
        $knowledge->delete();

        return redirect()->route('knowledges.index')
            ->with('success', 'Knowledge deleted successfully.');
    }
}

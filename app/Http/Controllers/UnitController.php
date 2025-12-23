<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Unit::with(['discipline', 'knowledges']);

        if ($request->has('discipline_id')) {
            $query->where('discipline_id', $request->discipline_id);
        }

        $units = $query->paginate(15);
        $disciplines = Discipline::all();

        return view('units.index', compact('units', 'disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $disciplines = Discipline::all();
        return view('units.create', compact('disciplines'));
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

        Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit): View
    {
        $unit->load(['discipline', 'knowledges.bnccs']);
        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        $disciplines = Discipline::all();
        return view('units.edit', compact('unit', 'disciplines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discipline_id' => 'required|exists:disciplines,id',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}

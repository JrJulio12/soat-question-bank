<?php

namespace App\Http\Controllers;

use App\Models\Bncc;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BnccController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Bncc::with(['discipline', 'series', 'knowledges']);

        if ($request->filled('discipline_id')) {
            $query->where('discipline_id', $request->discipline_id);
        }

        $bnccs = $query->paginate(15);
        $disciplines = Discipline::all();

        return view('bnccs.index', compact('bnccs', 'disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $disciplines = Discipline::all();
        $series = Serie::all();
        $knowledges = Knowledge::all();
        return view('bnccs.create', compact('disciplines', 'series', 'knowledges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:bnccs,code',
            'description' => 'nullable|string',
            'stage' => 'required|in:EF,EM',
            'discipline_id' => 'required|exists:disciplines,id',
            'series' => 'nullable|array',
            'series.*' => 'exists:series,id',
            'knowledges' => 'nullable|array',
            'knowledges.*' => 'exists:knowledges,id',
        ]);

        $bncc = Bncc::create($validated);

        if ($request->has('series')) {
            $bncc->series()->sync($request->series);
        }

        if ($request->has('knowledges')) {
            $bncc->knowledges()->sync($request->knowledges);
        }

        return redirect()->route('bnccs.index')
            ->with('success', 'BNCC created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bncc $bncc): View
    {
        $bncc->load(['discipline', 'series', 'knowledges.unit', 'questions']);
        return view('bnccs.show', compact('bncc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bncc $bncc): View
    {
        $disciplines = Discipline::all();
        $series = Serie::all();
        $knowledges = Knowledge::all();
        $bncc->load(['series', 'knowledges']);
        return view('bnccs.edit', compact('bncc', 'disciplines', 'series', 'knowledges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bncc $bncc): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:bnccs,code,' . $bncc->id,
            'description' => 'nullable|string',
            'stage' => 'required|in:EF,EM',
            'discipline_id' => 'required|exists:disciplines,id',
            'series' => 'nullable|array',
            'series.*' => 'exists:series,id',
            'knowledges' => 'nullable|array',
            'knowledges.*' => 'exists:knowledges,id',
        ]);

        $bncc->update($validated);

        if ($request->has('series')) {
            $bncc->series()->sync($request->series);
        } else {
            $bncc->series()->sync([]);
        }

        if ($request->has('knowledges')) {
            $bncc->knowledges()->sync($request->knowledges);
        } else {
            $bncc->knowledges()->sync([]);
        }

        return redirect()->route('bnccs.index')
            ->with('success', 'BNCC updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bncc $bncc): RedirectResponse
    {
        $bncc->delete();

        return redirect()->route('bnccs.index')
            ->with('success', 'BNCC deleted successfully.');
    }
}

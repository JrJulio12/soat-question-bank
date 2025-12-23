<?php

namespace App\Http\Controllers;

use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Question;
use App\Models\Serie;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Unit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and structure overview.
     */
    public function index()
    {
        // Total counts
        $stats = [
            'disciplines' => Discipline::count(),
            'units' => Unit::count(),
            'knowledges' => Knowledge::count(),
            'topics' => Topic::count(),
            'chapters' => Chapter::count(),
            'subjects' => Subject::count(),
            'series' => Serie::count(),
            'bnccs' => Bncc::count(),
            'questions' => Question::count(),
        ];

        // Counts by stage
        $stats['disciplines_ef'] = Discipline::where('stage', Stage::EF)->count();
        $stats['disciplines_em'] = Discipline::where('stage', Stage::EM)->orWhereNull('stage')->count();
        $stats['series_ef'] = Serie::where('stage', Stage::EF)->count();
        $stats['series_em'] = Serie::where('stage', Stage::EM)->count();
        $stats['bnccs_ef'] = Bncc::where('stage', Stage::EF)->count();
        $stats['bnccs_em'] = Bncc::where('stage', Stage::EM)->count();
        $stats['questions_ef'] = Question::where('stage', Stage::EF)->count();
        $stats['questions_em'] = Question::where('stage', Stage::EM)->count();

        // Hierarchical data for display
        $disciplinesWithUnits = Discipline::with(['units.knowledges', 'topics.chapters.subjects'])->get();
        
        // Series and BNCCs
        $series = Serie::with('bnccs')->orderBy('order')->get();
        $bnccs = Bncc::with(['series', 'knowledges', 'discipline'])->get();

        // Questions with relationships
        $questions = Question::with(['bnccs', 'subjects'])->latest()->take(10)->get();

        return view('dashboard.index', compact('stats', 'disciplinesWithUnits', 'series', 'bnccs', 'questions'));
    }
}


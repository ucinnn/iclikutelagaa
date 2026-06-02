<?php

namespace App\Http\Controllers;

use App\Models\Survey;

class SurveyController extends Controller
{
    public function index()
    {
        $survey = Survey::where('is_active', true)->get();
        return view('filament.surveidansaran', compact('survey'));
    }
}

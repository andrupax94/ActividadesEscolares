<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscriptionRequest;
use App\Models\Inscription;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $inscriptions = Inscription::all();

        return $request->expectsJson()
            ? response()->json($inscriptions)
            : view('inscriptions.index', compact('inscriptions'));
    }

    public function create()
    {
        return view('inscriptions.create');
    }

    public function store(InscriptionRequest $request)
    {
        $inscription = Inscription::create($request->validated());

        return JsonOrViewChecker($request, 'inscriptions.index', [
            'inscription' => $inscription,
            'message' => 'Inscripción registrada correctamente'
        ], 201);
    }
}

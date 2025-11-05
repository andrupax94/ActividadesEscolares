<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::all();

        return $request->expectsJson()
            ? response()->json($activities)
            : view('activities.index', compact('activities'));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(ActivityRequest $request)
    {


        $activity = Activity::create($request->validated());

        return JsonOrViewChecker($request, 'activities.index', [
            'activity' => $activity,
            'message' => 'Actividad creada correctamente'
        ], 201);
    }

    public function show(Request $request, Activity $activity)
    {
        return $request->expectsJson()
            ? response()->json($activity)
            : view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $activity->update($request->validated());

        return JsonOrViewChecker($request, 'activities.index', [
            'activity' => $activity,
            'message' => 'Actividad actualizada correctamente'
        ]);
    }

    public function destroy(Request $request, Activity $activity)
    {
        $activity->delete();

        return JsonOrViewChecker($request, 'activities.index', [
            'message' => 'Actividad eliminada correctamente'
        ]);
    }
}

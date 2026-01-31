<?php

namespace App\Http\Controllers;

use App\Models\ProjectLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectLocationsController extends Controller
{
    /**
     * Display a listing of project locations.
     */
    public function index()
    {
        $locations = ProjectLocation::all();

        return response()->json($locations);
    }

    /**
     * Store a newly created project location.
     */
    public function store(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'city' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'map_link' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $location = ProjectLocation::create($validator->validated());

        return response()->json($location, 201);
    }

    /**
     * Display the specified project location.
     */
    public function show(ProjectLocation $projectLocation)
    {
        return response()->json($projectLocation);
    }

    /**
     * Update the specified project location.
     */
    public function update(Request $request, ProjectLocation $projectLocation)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'sometimes|required|exists:projects,id',
            'city' => 'sometimes|required|string|max:255',
            'district' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'map_link' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $projectLocation->update($validator->validated());

        return response()->json($projectLocation);
    }

    /**
     * Remove the specified project location.
     */
    public function destroy(ProjectLocation $projectLocation)
    {
        $projectLocation->delete();

        return response()->json(null, 204);
    }
}

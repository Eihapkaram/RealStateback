<?php

namespace App\Http\Controllers;

use App\Models\ProjectInterest;
use Illuminate\Http\Request;

class ProjectInterestsController extends Controller
{
    public function index()
    {
        return ProjectInterest::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'purchase_type' => 'nullable|string',
            'purpose' => 'nullable|string',
        ]);

        $interest = ProjectInterest::create($data);

        return response()->json($interest, 201);
    }

    public function show(ProjectInterest $projectInterest)
    {
        return $projectInterest;
    }

    public function update(Request $request, ProjectInterest $projectInterest)
    {
        $projectInterest->update($request->all());

        return response()->json($projectInterest);
    }

    public function destroy(ProjectInterest $projectInterest)
    {
        $projectInterest->delete();

        return response()->json(null, 204);
    }
}

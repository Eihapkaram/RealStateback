<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->select('id', 'title', 'location', 'status', 'main_image', 'area', 'date')
            ->with([
                'features:id,project_id,feature,image',
            ])
            ->withCount('units')
            ->latest()
            ->get();

        $projects->each(function ($project) {

            $project->main_image_url = $project->main_image
                ? asset('storage/'.$project->main_image)
                : null;

            // إضافة image_url للـ features
            $project->features->each(function ($feature) {
                $feature->image_url = $feature->image
                    ? asset('storage/'.$feature->image)
                    : null;
            });

        });

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|string',
            'main_image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'overview_bedrooms' => 'nullable|integer',
            'overview_bathrooms' => 'nullable|integer',
            'overview_kitchens' => 'nullable|integer',
            'area' => 'nullable|numeric',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')
                ->store('projects', 'public');
        }

        $project = Project::create($data);

        $project->main_image_url = $project->main_image
            ? asset('storage/'.$project->main_image)
            : null;

        return response()->json($project, 201);
    }

    public function projectUnits($projectId)
    {
        $project = Project::with([
            'unitTypes.units',
        ])->findOrFail($projectId);

        return response()->json([
            'project_id' => $project->id,
            'unit_types' => $project->unitTypes,
        ]);
    }

    public function show(Project $project)
    {
        $project->load([
            'images:id,project_id,image',
            'features:id,project_id,feature,image',
            'locationDetail',
            'unitTypes:id,project_id,name,floor',
        ])->loadCount('units');

        $project->main_image_url = $project->main_image
            ? asset('storage/'.$project->main_image)
            : null;

        // project images
        $project->images->each(function ($img) {
            $img->image_url = $img->image
                ? asset('storage/'.$img->image)
                : null;
        });

        // project features
        $project->features->each(function ($feature) {
            $feature->image_url = $feature->image
                ? asset('storage/'.$feature->image)
                : null;
        });

        return response()->json($project);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'main_image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'overview_bedrooms' => 'nullable|integer',
            'overview_bathrooms' => 'nullable|integer',
            'overview_kitchens' => 'nullable|integer',
            'area' => 'nullable|numeric',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('main_image')) {

            if ($project->main_image &&
                Storage::disk('public')->exists($project->main_image)) {

                Storage::disk('public')->delete($project->main_image);
            }

            $data['main_image'] = $request->file('main_image')
                ->store('projects', 'public');
        }

        $project->update($data);

        $project->main_image_url = $project->main_image
            ? asset('storage/'.$project->main_image)
            : null;

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        if ($project->main_image &&
            Storage::disk('public')->exists($project->main_image)) {

            Storage::disk('public')->delete($project->main_image);
        }

        $project->delete();

        return response()->json(null, 204);
    }
}

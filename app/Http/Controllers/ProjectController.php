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
                ? url('api/'.$project->main_image)
                : null;
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
            $path = $request->file('main_image')->store('projects', 'public');
            $data['main_image'] = $path;
        }

        $project = Project::create($data);

        $project->main_image_url = $project->main_image
            ? url('api/'.$project->main_image)
            : null;

        return response()->json($project, 201);
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
            ? url('api/'.$project->main_image)
            : null;

        $project->images->each(function ($img) {
            $img->url = $img->image
                ? url('api/project-images/files/'.$img->image)
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
            if ($project->main_image && Storage::disk('public')->exists($project->main_image)) {
                Storage::disk('public')->delete($project->main_image);
            }
            $path = $request->file('main_image')->store('projects', 'public');
            $data['main_image'] = $path;
        }

        $project->update($data);

        $project->main_image_url = $project->main_image
            ? url('api/'.$project->main_image)
            : null;

        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        if ($project->main_image && Storage::disk('public')->exists($project->main_image)) {
            Storage::disk('public')->delete($project->main_image);
        }

        $project->delete();

        return response()->json(null, 204);
    }
}

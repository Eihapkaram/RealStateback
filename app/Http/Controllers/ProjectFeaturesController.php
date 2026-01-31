<?php

namespace App\Http\Controllers;

use App\Models\ProjectFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectFeaturesController extends Controller
{
    public function index()
    {
        $features = ProjectFeature::all()->map(function ($feature) {
            $feature->image_url = $feature->image
                ? url('api/project-features/files/'.$feature->image)
                : null;

            return $feature;
        });

        return response()->json($features);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'feature' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('project_features', 'public');
        }

        $feature = ProjectFeature::create([
            'project_id' => $request->project_id,
            'feature' => $request->feature,
            'image' => $imagePath,
        ]);

        $feature->image_url = $imagePath
            ? url('api/project-features/files/'.$imagePath)
            : null;

        return response()->json($feature, 201);
    }

    public function show(ProjectFeature $projectFeature)
    {
        $projectFeature->image_url = $projectFeature->image
            ? url('api/project-features/files/'.$projectFeature->image)
            : null;

        return response()->json($projectFeature);
    }

    public function update(Request $request, ProjectFeature $projectFeature)
    {
        $request->validate([
            'feature' => 'sometimes|string',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) 
            // حذف الصورة القديمة
            if ($projectFeature->image && Storage::disk('public')->exists($projectFeature->image)) {
                Storage::disk('public')->delete($projectFeature->image);
            }

            $projectFeature->image = $request->file('image')
                ->store('project_features', 'public');
        }

        if ($request->has('feature')) {
            $projectFeature->feature = $request->feature;
        }

        $projectFeature->save();

        $projectFeature->image_url = $projectFeature->image
            ? url('api/project-features/files/'.$projectFeature->image)
            : null;

        return response()->json($projectFeature);
    }

    public function destroy(ProjectFeature $projectFeature)
    {
        if ($projectFeature->image && Storage::disk('public')->exists($projectFeature->image)) {
            Storage::disk('public')->delete($projectFeature->image);
        }

        $projectFeature->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProjectImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImagesController extends Controller
{
    public function index()
    {
        $images = ProjectImage::all()->map(function ($img) {
            $img->url = $img->image ? url('api/project-images/files/'.$img->image) : null;

            return $img;
        });

        return response()->json($images);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'images' => 'required|array',           // استقبل مصفوفة صور
            'images.*' => 'image|max:2048',         // تحقق من كل صورة
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store('project_images', 'public');

            $image = ProjectImage::create([
                'project_id' => $request->project_id,
                'image' => $path,
            ]);

            $image->url = url('api/project-images/files/'.$path);

            $uploadedImages[] = $image;
        }

        return response()->json($uploadedImages, 201);
    }

    public function show(ProjectImage $projectImage)
    {
        $projectImage->url = $projectImage->image ? url('api/project-images/files/'.$projectImage->image) : null;

        return response()->json($projectImage);
    }

    public function update(Request $request, ProjectImage $projectImage)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($projectImage->image && Storage::disk('public')->exists($projectImage->image)) {
                Storage::disk('public')->delete($projectImage->image);
            }

            $path = $request->file('image')->store('project_images', 'public');
            $projectImage->image = $path;
        }

        if ($request->has('project_id')) {
            $projectImage->project_id = $request->project_id;
        }

        $projectImage->save();

        $projectImage->url = $projectImage->image ? url('api/project-images/files/'.$projectImage->image) : null;

        return response()->json($projectImage);
    }

    public function destroy(ProjectImage $projectImage)
    {
        if ($projectImage->image && Storage::disk('public')->exists($projectImage->image)) {
            Storage::disk('public')->delete($projectImage->image);
        }

        $projectImage->delete();

        return response()->json(null, 204);
    }
}

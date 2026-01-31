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

            $img->image_url = $img->image
                ? asset('storage/'.$img->image)
                : null;

            return $img;
        });

        return response()->json($images);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'images' => 'required|array',
            'images.*' => 'image|max:2048',
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {

            $path = $file->store('project_images', 'public');

            $image = ProjectImage::create([
                'project_id' => $request->project_id,
                'image' => $path,
            ]);

            $image->image_url = asset('storage/'.$path);

            $uploadedImages[] = $image;
        }

        return response()->json($uploadedImages, 201);
    }

    public function show(ProjectImage $projectImage)
    {
        $projectImage->image_url = $projectImage->image
            ? asset('storage/'.$projectImage->image)
            : null;

        return response()->json($projectImage);
    }

    public function update(Request $request, ProjectImage $projectImage)
    {
        $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {

            // حذف الصورة القديمة
            if ($projectImage->image &&
                Storage::disk('public')->exists($projectImage->image)) {

                Storage::disk('public')->delete($projectImage->image);
            }

            $projectImage->image = $request->file('image')
                ->store('project_images', 'public');
        }

        if ($request->has('project_id')) {
            $projectImage->project_id = $request->project_id;
        }

        $projectImage->save();

        $projectImage->image_url = $projectImage->image
            ? asset('storage/'.$projectImage->image)
            : null;

        return response()->json($projectImage);
    }

    public function destroy(ProjectImage $projectImage)
    {
        if ($projectImage->image &&
            Storage::disk('public')->exists($projectImage->image)) {

            Storage::disk('public')->delete($projectImage->image);
        }

        $projectImage->delete();

        return response()->json(null, 204);
    }
}

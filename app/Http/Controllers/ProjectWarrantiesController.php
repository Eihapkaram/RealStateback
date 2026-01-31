<?php

namespace App\Http\Controllers;

use App\Models\ProjectWarranty;
use Illuminate\Http\Request;

class ProjectWarrantyController extends Controller
{
    public function index()
    {
        return ProjectWarranty::all();
    }

    public function store(Request $request)
    {
        $warranty = ProjectWarranty::create($request->all());

        return response()->json($warranty, 201);
    }

    public function show(ProjectWarranty $projectWarranty)
    {
        return $projectWarranty;
    }

    public function update(Request $request, ProjectWarranty $projectWarranty)
    {
        $projectWarranty->update($request->all());

        return response()->json($projectWarranty);
    }

    public function destroy(ProjectWarranty $projectWarranty)
    {
        $projectWarranty->delete();

        return response()->json(null, 204);
    }
}

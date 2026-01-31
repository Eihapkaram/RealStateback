<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        return response()->json(UnitType::create($data), 201);
    }

    public function destroy(UnitType $unitType)
    {
        $unitType->delete();

        return response()->json(null, 204);
    }
}

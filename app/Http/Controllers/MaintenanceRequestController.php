<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $requests = MaintenanceRequest::with('project')->get();

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'project_id' => 'required|exists:projects,id',
            'unit' => 'nullable|string|max:50',
            'request_type' => 'required|in:صيانة وإصلاح,أخرى',
            'unit_received' => 'boolean',
            'message' => 'nullable|string',
        ]);

        $maintenanceRequest = MaintenanceRequest::create($request->all());

        return response()->json($maintenanceRequest, 201);
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->load('project');

        return response()->json($maintenanceRequest);
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->update($request->all());

        return response()->json($maintenanceRequest);
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();

        return response()->json(null, 204);
    }
}

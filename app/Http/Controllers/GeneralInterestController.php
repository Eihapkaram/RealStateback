<?php

namespace App\Http\Controllers;

use App\Models\GeneralInterest;
use Illuminate\Http\Request;

class GeneralInterestController extends Controller
{
    public function index()
    {
        return response()->json(
            GeneralInterest::latest()->paginate(15)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'max_price' => 'nullable|integer',
            'property_type' => 'nullable|string|max:50',
            'finance_type' => 'nullable|string|max:50',
            'district' => 'nullable|string|max:100',
            'beds' => 'nullable|integer',
            'baths' => 'nullable|integer',
        ]);

        $interest = GeneralInterest::create($data);

        return response()->json([
            'message' => 'تم تسجيل الاهتمام بنجاح',
            'data' => $interest,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneralInterest $generalInterest)
    {
        return response()->json($generalInterest);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralInterest $generalInterest)
    {
        $generalInterest->delete();

        return response()->json([
            'message' => 'تم الحذف بنجاح',
        ]);
    }
}

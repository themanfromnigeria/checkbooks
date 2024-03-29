<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlanRequest;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $plans = Plan::all();

            return response()->json([
                'status' => 200,
                'plans' => $plans
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch plans.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePlanRequest $request)
    {
        try {
            $plan = Plan::create($request->validated());

            return response()->json([
                'status' => 201,
                'plan' => $plan
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create plan.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePlanRequest $request, $id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->update($request->validated());

            return response()->json([
                'plan' => $plan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update plan.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);

            $plan->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Plan deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete plan.'
            ], 500);
        }
    }
}

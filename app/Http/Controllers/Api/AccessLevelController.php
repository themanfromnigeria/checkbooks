<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\AccessLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccessLevelRequest;

class AccessLevelController extends Controller
{
    public function index()
    {
        try {
            $accessLevels = AccessLevel::all();
            return response()->json([
                'status' => 200,
                'access_levels' => $accessLevels,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching access levels.',
            ], 500);
        }
    }

    public function store(CreateAccessLevelRequest $request)
    {
        try {

            $accessLevel = AccessLevel::create($request->validated());

            return response()->json([
                'status' => 200,
                'message' => 'Access level created successfully',
                'access_level' => $accessLevel,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while creating the access level.',
            ], 500);
        }
    }

    public function update(CreateAccessLevelRequest $request, $id)
    {
        try {
            $accessLevel = AccessLevel::findOrFail($id);
            $accessLevel->update($request->validated());

            return response()->json([
                'status' => 200,
                'message' => 'Access level updated successfully',
                'access_level' => $accessLevel,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while updating the access level.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $accessLevel = AccessLevel::findOrFail($id);
            $accessLevel->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Access level deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while deleting the access level.',
            ], 500);
        }
    }
}

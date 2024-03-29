<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();

            return response()->json([
                'status' => '200',
                'users' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve users.'
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
    public function store(CreateUserRequest $request)
    {
        try {
            $user = User::create($request->all());

            return response()->json([
                'status' => 201,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create user.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 200,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }
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
    public function update(CreateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->all());

            return response()->json([
                'status' => 200,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update user.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete user.'
            ], 500);
        }
    }
}












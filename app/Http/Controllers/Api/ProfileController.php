<?php

namespace App\Http\Controllers\Api;

use App\Models\Profile;
use App\Models\AccessLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

     // I want to remove this profile
    public function show()
    {
        try {
            // Retrieve the authenticated user along with their associated profile data
            $user = auth()->user()->load('profile');

            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'error' => 'An error occurred: ' . $e->getMessage()
            ], 500);
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string',
            'age' => 'required|integer|min:0',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = auth()->user();

        $profile = $user->profile ?? new Profile; // If profile doesn't exist, create a new one

        $profile->first_name = $request->input('first_name');
        $profile->last_name = $request->input('last_name');
        $profile->username = $request->input('username');
        $profile->age = $request->input('age');
        $profile->address = $request->input('address');

        $user->profile()->save($profile);


        $accessLevel = $this->determineAccessLevel($profile->age, $user->borrowing_points);

        // Assign access level to user
        if ($accessLevel) {
            $user->access_level_id = $accessLevel->id;
        } else {
            $user->access_level_id = null;
        }

        $user->save();



        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile,
        ]);
    }


    protected function determineAccessLevel($age, $borrowingPoints)
    {
        if ($age >= 7 && $age <= 15) {
            if ($borrowingPoints == 0) {
                return AccessLevel::where('name', 'Children')->first();
            }
        } elseif ($age >= 15 && $age <= 24) {
            if ($borrowingPoints <= 9) {
                return AccessLevel::where('name', 'Youth')->first();
            } elseif ($borrowingPoints >= 10 && $borrowingPoints <= 14) {
                return AccessLevel::where('name', 'Children Exclusive')->first();
            } else{
                return AccessLevel::where('name', 'Youth Exclusive')->first();
            }
        } elseif ($age >= 25 && $age <= 49) {
            if ($borrowingPoints <= 19) {
                return AccessLevel::where('name', 'Adult')->first();
            } elseif ($borrowingPoints >= 20) {
                return AccessLevel::where('name', 'Adult Exclusive')->first();
            }
        }
        return null;
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

















    // public function update(UpdateProfileRequest $request)
    // {
    //     try {
    //         // Retrieve the authenticated user
    //         $user = auth()->user();

    //         // Retrieve the user's profile, if it exists
    //         $profile = $user->profile;

    //         // Update the profile with validated data from the request
    //         $profile->update($request->validated());

    //         // Save the profile
    //         $user->profile()->save($profile);

    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Profile updated successfully.',
    //             'profile' => $profile,
    //         ]);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions that occur during the update process
    //         return response()->json([
    //             'error' => 'Failed to update profile: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

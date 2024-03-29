<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Lending;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    // Register function
    public function register(RegisterUserRequest $request)
    {
        try {
            // Attempt to create a user after validation checks
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Issue API token
            $token = $user->createToken($user->email.'_API_Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'token' => $token,
                'user' => $user,
                'message' => 'User registered successfully',
            ], 200);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return response()->json([
                'status' => 500,
                'message' => 'Failed to register user: ' . $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


    // login function
    public function login(LoginUserRequest $request)
    {
        try {
            // Attempt to authenticate the user
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Incorrect credentials',
                ], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Generate a new token for the user
            $token = $user->createToken($user->email.'_API_Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'User logged in successfully',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Logout function
    public function logout()
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Revoke all of the user's tokens
            $user->tokens()->delete();

            return response()->json([
                'status' => 200,
                'message' => 'User logged out successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while logging out.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function borrowedBooksCount()
    {
        try {
            $userId = Auth::id();

            // Fetch borrowed books and their count
            $borrowedBooks = Lending::where('user_id', $userId)
                ->whereNull('returned_at')
                ->with('book')
                ->get();

            $borrowedBooksCount = $borrowedBooks->count();

            if ($borrowedBooksCount === 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No borrowed books found.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'borrowed_books_count' => $borrowedBooksCount,
                    'borrowed_books' => $borrowedBooks
                ], 200);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching borrowed books.'
            ], 500);
        }

    }


    public function returnedBooksCount()
    {
        try {
            $userId = Auth::id();

            // Fetch returned books and their count
            $returnedBooks = Lending::where('user_id', $userId)
                ->whereNotNull('returned_at')
                ->with('book')
                ->get();

            $returnedBooksCount = $returnedBooks->count();

            return response()->json([
                'status' => 200,
                'returned_books_count' => $returnedBooksCount,
                'returned_books' => $returnedBooks
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching returned books.'
            ], 500);
        }
    }


}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller
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
    public function store(Request $request, $id)
    {
        // Find the lending record by ID
        $lending = Lending::findOrFail($id);

        // Check if the book has already been returned
        if (!is_null($lending->returned_at)) {
            return response()->json([
                'status' => 400,
                'error' => 'Book has already been returned'
            ], 400);
        }

        // Check if the lending record belongs to the authenticated user
        if ($lending->user_id !== Auth::id()) {
            return response()->json([
                'status' => 403,
                'error' => 'Access not granted! You are not authorized to return this book'
            ], 403);
        }

        //Number of days the book was borrowed
        $borrowedDays = now()->diffInDays($lending->borrowed_at);

        // Number of days the book was returned before the due datetime
        $dueDate = Carbon::parse($lending->due_at);
        $daysBeforeDueDate = now()->diffInDays($dueDate, false); // Negative value if returned after due date

        // Borrowing points based on the days
        $borrowingPoints = 0;
        if ($daysBeforeDueDate >= 0) {
            // Award 2 points for returning before due datetime
            $borrowingPoints = 2;
        } else {
            // Deduct 1 point for returning after due datetime
            $borrowingPoints = -1;
        }


        // Update the lending record with return datetime and borrowing points
        $lending->returned_at = now();
        $lending->points = $borrowingPoints;
        $lending->save();

        // Update the corresponding book's status to available
        $book = $lending->book;
        $book->is_borrowed = 'available';
        $book->save();

        // Award the borrowing points to the user
        $user = User::findOrFail(Auth::id());
        $user->borrowing_points += $borrowingPoints;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Book returned successfully.',
            'borrowing_points_awarded' => $borrowingPoints
        ], 200);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}




// // Logic when a book is returned
// public function returnBook(User $user, Book $book, $returnedAt)
// {
//     // Calculate points based on conditions
//     $points = 0;

//     if ($returnedAt <= $book->due_at) {
//         // Book returned before due datetime
//         $points = 2;
//     } else {
//         // Book returned after due datetime
//         $points = 1; // Deduct 1 point for overdue
//     }

//     // Update user's borrowing points
//     $user->borrowing_points += $points;
//     $user->save();
// }

<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\User;
use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve 5 active books
            $books = Book::where('status', 0)->paginate(5);

            return response()->json([
                'status' => 200,
                'books' => $books,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to retrieve books: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            // Retrieve the validated data from the request
            $validatedData = $request->validated();

            // Set the user_id to the authenticated user's ID
            $validatedData['user_id'] = auth()->id();

            // Create a new book with the validated data
            $book = Book::create($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Book added successfully',
                'book' => $book,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to add book: ' . $e->getMessage(),
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
    public function update(StoreBookRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $book = Book::findOrFail($id);
            $book->update($validatedData);

            return response()->json([
                'status' => 200,
                'message' => 'Book updated successfully',
                'book' => $book,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to update book: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Book deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Failed to delete book!! Book not found',
                ], 404);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Failed to delete book: ' . $e->getMessage(),
                ], 500);
            }
        }
    }

    public function borrowBook(Request $request, $id)
    {
        // Find the book by ID
        $book = Book::findOrFail($id);

        // Check if the user is authenticated and retrieve the user ID
        $userId = Auth::id();
        if (!$userId) {
            return response()->json([
                'status' => 401,
                'error' => 'User is not authenticated'
            ], 401);
        }

        // Check if the book is available for borrowing

        if ($book->is_borrowed != 'borrowed') {

            // Calculate the due date, 7 days from the current date
            $dueDate = Carbon::now()->addDays(7);

            // Create a lending record
            $lending = new Lending();
            $lending->user_id = Auth::id();
            $lending->book_id = $book->id;
            $lending->borrowed_at = now();
            $lending->due_at = $dueDate;
            $lending->save();

            // Update the book's is_borrowed field to 'borrowed'
            $book->update(['is_borrowed' => 'borrowed']);

            return response()->json([
                'status' => 200,
                'message' => 'Book borrowed successfully',
                'book' => $book
            ], 200);
        }
        else {
            return response()->json([
                'status' => 400,
                'error' => 'Book is already borrowed!!'
            ], 400);
        }
    }
}

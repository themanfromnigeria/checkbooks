<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LendingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AccessLevelController;
use App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/





// Authenticated authors and admins
Route::middleware('auth:sanctum', 'admin.author.access')->group(function () {
    Route::post('add-books', [BookController::class, 'store']);
    Route::post('books/{id}', [BookController::class, 'update']);
    Route::delete('books/{id}', [BookController::class, 'destroy']);

});

// Authenticated  admins
Route::middleware(['auth:sanctum', 'admin.access'])->group(function () {
    // Plans
    Route::post('add-plans', [PlanController::class, 'store']);
    Route::post('plans/{id}', [PlanController::class, 'update']);
    Route::delete('plans/{id}', [PlanController::class, 'destroy']);

    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::post('add-users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Access level
    Route::get('access-levels', [AccessLevelController::class, 'index']);
    Route::post('add-access-levels', [AccessLevelController::class, 'store']);
    Route::post('access-levels/{id}', [AccessLevelController::class, 'update']);
    Route::delete('access-levels/{id}', [AccessLevelController::class, 'destroy']);
});

// Public routes accessible to all users
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('books', [BookController::class, 'index']);
Route::get('plans', [PlanController::class, 'index']);

// Authenticated users
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile/update', [ProfileController::class, 'update']);

    Route::post('books/{id}/borrow', [BookController::class, 'borrowBook']);
    Route::post('lendings/{id}/return', [LendingController::class, 'store']);

    Route::get('borrowed-books-count', [AuthController::class, 'borrowedBooksCount']);
    Route::get('returned-books-count', [AuthController::class, 'returnedBooksCount']);

    Route::post('subscribe/{id}', [SubscriptionController::class, 'subscribe']);
    Route::get('subscriptions', [SubscriptionController::class, 'activeSubscriptions']);
    Route::get('subscriptions/history', [SubscriptionController::class, 'subscriptionHistory']);
});









Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

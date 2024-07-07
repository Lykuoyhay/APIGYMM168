<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/users/{id}', [AuthController::class, 'update']); // Update user by ID
    Route::get('/users', [AuthController::class, 'getAllUsers']); // Get all users
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser']); // Delete user by ID
    Route::post('/logout', [AuthController::class, 'logout']);
});





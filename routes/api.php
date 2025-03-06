<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TimeSheetController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Authenticated Routes
Route::middleware('auth:api')->group(function () {
    // TimeSheet Routes
    Route::controller(UserController::class)->group(function(){
        Route::get('user', 'index');      // Get all user
        Route::get('user/{id}', 'show');  // Get a single user
        Route::post('user', 'store');     // Create a user
        Route::put('user/{id}', 'update'); // Update user
        Route::delete('user/{id}', 'destroy'); // Delete user
    });

    // TimeSheet Routes
    Route::controller(TimeSheetController::class)->group(function(){
        Route::get('timesheet', 'index');      // Get all timesheet
        Route::get('timesheet/{id}', 'show');  // Get a single timesheet
        Route::post('timesheet', 'store');     // Create a timesheet
        Route::put('timesheet/{id}', 'update'); // Update timesheet
        Route::delete('timesheet/{id}', 'destroy'); // Delete timesheet
    });

    // Project Routes
    Route::controller(ProjectController::class)->group(function(){
        Route::get('project', 'index');      // Get all project
        Route::get('project/{id}', 'show');  // Get a single project
        Route::post('project', 'store');     // Create a project
        Route::put('project/{id}', 'update'); // Update project
        Route::delete('project/{id}', 'destroy'); // Delete project
    });

    Route::post('logout', [AuthController::class, 'logout']);
});

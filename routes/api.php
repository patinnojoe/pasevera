<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\TaskAnalysisController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Task Group
Route::controller(TaskController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('addTask', 'addTask');
    Route::post('updateTask', 'updateTaskStatus');
    Route::post('editTask', 'editTaskItem');
    Route::get('allTask', 'allTask');
});

// Analysis Controller
Route::controller(TaskAnalysisController::class)->middleware('auth:sanctum')->group(function () {
    // Route::post('userAnalysis', 'userAnalysis');
    Route::get('getUserAnalysis', 'getUserAnalysis');
    Route::post('generalAnalysis', 'generalAnalysis');
    Route::get('getGeneralAnalysis', 'getGeneralAnalysis');
});

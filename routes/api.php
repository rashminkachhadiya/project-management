<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\CommentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:admin');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->middleware('role:admin');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->middleware('role:admin');

    // Tasks
    Route::get('/projects/{project_id}/tasks', [TaskController::class, 'indexByProject']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/projects/{project_id}/tasks', [TaskController::class, 'store'])->middleware('role:manager');
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('role:manager');

    // Comments
    Route::get('/tasks/{task_id}/comments', [CommentController::class, 'index']);
    Route::post('/tasks/{task_id}/comments', [CommentController::class, 'store']);
});

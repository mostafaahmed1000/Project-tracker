<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\AttachmentApiController;

Route::prefix('v1')->as('api')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('projects', ProjectApiController::class);
        Route::post('projects/{id}/restore', [ProjectApiController::class, 'restore']);
        Route::get('projects/{project}/tasks', [ProjectApiController::class, 'tasks']);

        Route::post('projects/{project}/tasks', [TaskApiController::class, 'store']);
        Route::get('tasks/{task}', [TaskApiController::class, 'show']);
        Route::put('tasks/{task}', [TaskApiController::class, 'update']);
        Route::patch('tasks/{task}/toggle-done', [TaskApiController::class, 'toggleDone']);
        Route::delete('tasks/{task}', [TaskApiController::class, 'destroy']);
        Route::post('tasks/{task}/restore', [TaskApiController::class, 'restore']);

        Route::post('tasks/{task}/attachments', [AttachmentApiController::class, 'store']);
        Route::get('tasks/{task}/attachments', [AttachmentApiController::class, 'index']);
        Route::delete('attachments/{attachment}', [AttachmentApiController::class, 'destroy']);
    });
});

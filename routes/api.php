<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FeatureFlagController;
use App\Http\Controllers\Api\V1\Admin\FeatureFlagManagementController;
use App\Http\Controllers\Api\V1\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Auth Login Route (Generates the token)
Route::post('/v1/login', [AuthController::class, 'login']);

// Public Evaluation Route (Fast & Cached)
Route::post('/v1/evaluate/{projectSlug}/{flagKey}', [FeatureFlagController::class, 'evaluate']);

// Protected Management Routes (Admin Only)
Route::middleware('auth:sanctum')->prefix('v1/admin')->group(function () {
    Route::get('/projects/{projectId}/flags', [FeatureFlagManagementController::class, 'index']);
    Route::post('/projects/{projectId}/flags', [FeatureFlagManagementController::class, 'store']);
    Route::put('/flags/{id}', [FeatureFlagManagementController::class, 'update']);
    Route::delete('/flags/{id}', [FeatureFlagManagementController::class, 'destroy']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FeatureFlagController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/evaluate/{projectSlug}/{flagKey}', [FeatureFlagController::class, 'evaluate']);

<?php

use App\Http\Controllers\TestRunController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/test-runs', [TestRunController::class, 'store']);
Route::get('/test-runs', [TestRunController::class, 'index']);
Route::get('/test-runs/latest', [TestRunController::class, 'latest']);

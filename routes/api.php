<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\JobController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/bids', [BidController::class, 'index']);
Route::post('/bids', [BidController::class, 'store']);
Route::get('/bids/{id}', [BidController::class, 'show']);
Route::put('/bids/{id}', [BidController::class, 'update']);
Route::delete('/bids/{id}', [BidController::class, 'destroy']);
Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'store']);
Route::get('/messages/{id}', [MessageController::class, 'show']);
Route::delete('/messages/{id}', [MessageController::class, 'destroy']);
Route::put('/messages/{id}', [MessageController::class, 'update']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus']);




//(Sarah profile)

    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::post('/profile/bio', [ProfileController::class, 'updateBio']);
    Route::post('/profile/skills', [ProfileController::class, 'updateSkills']);


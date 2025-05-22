<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;



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
Route::get('/messages/recent/{userId}', [MessageController::class, 'recentChats']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus']);





//(sarah profile)
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
Route::post('/profile/bio', [ProfileController::class, 'updateBio']);
Route::post('/profile/skills', [ProfileController::class, 'updateSkills']);
//( sarah admin)
Route::get('/admin/dashboard/summary', [AdminController::class, 'dashboardSummary']);

Route::get('/admin/dashboard/recent-jobs', [AdminController::class, 'getRecentJobs']);
Route::get('/admin/dashboard/recent-artisans', [AdminController::class, 'getRecentArtisans']);

Route::get('/admin/artisans', [AdminController::class, 'index']);
Route::put('/admin/artisans/{id}/status', [AdminController::class, 'updateStatus']);
Route::delete('/admin/artisans/{id}', [AdminController::class, 'destroy']);
Route::get('/admin/artisans/search', [AdminController::class, 'search']); 

Route::get('/admin/jobs', [AdminController::class, 'listJobs']);
Route::get('/admin/jobs/{id}', [AdminController::class, 'showJob']);
Route::put('/admin/jobs/{id}/status', [AdminController::class, 'updateJobStatus']);
Route::delete('/admin/jobs/{id}', [AdminController::class, 'deleteJob']);







Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
Route::get('/role', [RoleController::class, 'index']);
Route::get('/role/{id}', [RoleController::class, 'show']);


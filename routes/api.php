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
})->middleware('auth:api');

//edit
//Route::get('/role', [RoleController::class, 'index']);
//Route::get('/role/{id}', [RoleController::class, 'show']);
//Route::get('/bids/{id}', [BidController::class, 'show']);
//Route::put('/bids/{id}', [BidController::class, 'update']);
//Route::delete('/messages/{id}', [MessageController::class, 'destroy']);
//Route::put('/messages/{id}', [MessageController::class, 'update']);


//(sarah profile)
Route::middleware(['auth:api'])->group(function () {
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::post('/profile/bio', [ProfileController::class, 'updateBio']);
    Route::post('/profile/skills', [ProfileController::class, 'updateSkills']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});



//freelancer
Route::middleware(['auth:api','role:FreeLancer'])->group(function () {
    Route::post('/bids', [BidController::class, 'store']);
    Route::get('/bids', [BidController::class, 'index']);
    Route::get('/messages', [MessageController::class, 'index']);//j,f


});

//job owner
Route::middleware(['auth:api','role:JobOwner'])->group(function () {
    Route::delete('/bids/{id}', [BidController::class, 'destroy']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::get('/jobs/{jobId}/bids', [BidController::class, 'getBidsForJob']);
     Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus']);
});

//admin
Route::middleware(['auth:api' , 'role:Admin'])->group(function () {
    Route::delete('/admin/artisans/{id}', [AdminController::class, 'destroy']);
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
    Route::get('/users', [UserController::class, 'index']);

});

//anyone
Route::get('/jobs', [JobController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
 Route::get('/jobs/{id}', [JobController::class, 'show']);
//
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// //admin,freelancer
// Route::middleware(['auth:api','role:Admin,FreeLancer'])->group(function () {
//     Route::get('/jobs/{id}', [JobController::class, 'show']);
// });

//Job owner, freelancer
Route::middleware(['auth:api','role:JobOwner,FreeLancer'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index']);//j,f
    Route::post('/messages', [MessageController::class, 'store']);//j,f
    Route::get('/messages/{id}', [MessageController::class, 'show']);//j,f
    Route::get('/messages/recent/{userId}', [MessageController::class, 'recentChats']);//j,f
});
//admin,job owner
Route::middleware(['auth:api','role:Admin,JobOwner'])->group(function () {
      Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
});

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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\JobPhotoController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//freelancer
Route::middleware(['auth:api','role:FreeLancer'])->group(function () {
    Route::post('/bids', [BidController::class, 'store']);
    Route::get('/bids', [BidController::class, 'index']);
});

//job owner
Route::middleware(['auth:api','role:JobOwner'])->group(function () {
    Route::delete('/bids/{id}', [BidController::class, 'destroy']);
    Route::get('/my-jobs', [JobController::class, 'myJobs']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::get('/jobs/{jobId}/bids', [BidController::class, 'getBidsForJob']);
    Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus']);
    Route::put('/bids/{id}/status', [BidController::class, 'changeStatus']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/bid/{bidId}', [ReviewController::class, 'showByBid']);
    Route::post('/job-photos', [JobPhotoController::class, 'store']);
    Route::delete('/job-photos/{id}', [JobPhotoController::class, 'destroy']);
});

//admin
Route::middleware(['auth:api' , 'role:Admin'])->group(function () {
    Route::delete('/admin/artisans/{id}', [AdminController::class, 'destroy']);

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
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/freelancer-ratings/{freelancerId}', [ReviewController::class, 'getFreelancerRating']);
Route::get('/top-artisans', [ReviewController::class, 'topRated']);


//
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::post('/profile/bio', [ProfileController::class, 'updateBio']);
    Route::post('/profile/skills', [ProfileController::class, 'updateSkills']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/profile/social-links', [ProfileController::class, 'updateSocialLinks']);
       Route::get('/users/{id}', [UserController::class, 'show']);

});

// //admin,freelancer
// Route::middleware(['auth:api','role:Admin,FreeLancer'])->group(function () {
//     Route::get('/jobs/{id}', [JobController::class, 'show']);
// });

//Job owner, freelancer
Route::middleware(['auth:api','role:JobOwner,FreeLancer'])->group(function () {
    Route::post('/messages', [MessageController::class, 'store']);//j,f
    Route::get('/messages/conversation/{receiver_id}', [MessageController::class, 'getConversationMessages']); // j,f
    Route::get('/messages/Contact', [MessageController::class, 'recentContacts']); // j,f
    Route::get('/job-photos/job/{jobId}', [JobPhotoController::class, 'index']);


});
//admin,job owner
Route::middleware(['auth:api','role:Admin,JobOwner'])->group(function () {
      Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
});

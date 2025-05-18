<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
Route::get('/test', function () {
    return response()->json(['hello' => 'API is working!']);
});

Route::get('/bids', [BidController::class, 'index']);

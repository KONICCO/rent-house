<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json(
        [
            'success' => true,
            'message' => 'detail user',
            'data' => $request->user()
        ]
    );
});
Route::get('/listings', \App\Http\Controllers\API\ListingController::class)->only(['index','show']);
require __DIR__.'/auth.php';

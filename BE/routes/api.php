<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ListingController;
use App\Http\Controllers\API\TransactionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json(
        [
            'success' => true,
            'message' => 'detail user',
            'data' => $request->user()
        ]
    );
});
Route::resource('/listings', ListingController::class)->only(['index', 'show']);
// Route::get('/listings/{slug}', [ListingController::class, 'show']);
Route::post('/transaction/is-available', [TransactionController::class, 'isAvailaible'])->middleware('auth:sanctum');
Route::resource('transaction', TransactionController::class)->only(['index', 'store','show'])->middleware('auth:sanctum');
require __DIR__ . '/auth.php';

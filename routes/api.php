<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'system' => 'Apex Diagnostics & Patient Management AI',
        'timestamp' => now()->toIso8601String(),
    ]);
});

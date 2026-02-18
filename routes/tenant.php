<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant'])->prefix('tenant')->group(function () {
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'tenant' => $request->attributes->get('tenant'),
            'user' => $request->user(),
        ]);
    });
});

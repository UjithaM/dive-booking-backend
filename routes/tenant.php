<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant', 'role:tenant_admin'])->prefix('tenant')->group(function () {
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'tenant' => $request->attributes->get('tenant'),
            'user' => $request->user(),
        ]);
    });

    Route::apiResource('activities', \App\Http\Controllers\Api\Tenant\ActivityController::class);
    Route::apiResource('courses', \App\Http\Controllers\Api\Tenant\CourseController::class);
    Route::apiResource('dive-sites', \App\Http\Controllers\Api\Tenant\DiveSiteController::class);
    Route::apiResource('staff', \App\Http\Controllers\Api\Tenant\StaffController::class);
    Route::apiResource('customers', \App\Http\Controllers\Api\Tenant\CustomerController::class);
});

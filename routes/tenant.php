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
    Route::apiResource('centres', \App\Http\Controllers\Api\Tenant\CentreController::class);
    Route::apiResource('centres.seasons', \App\Http\Controllers\Api\Tenant\CentreSeasonController::class);
    Route::apiResource('services', \App\Http\Controllers\Api\Tenant\ServiceController::class);
    Route::apiResource('room-types', \App\Http\Controllers\Api\Tenant\RoomTypeController::class);
    Route::apiResource('rooms', \App\Http\Controllers\Api\Tenant\RoomController::class);
    Route::apiResource('promotions', \App\Http\Controllers\Api\Tenant\PromotionController::class);
    Route::apiResource('blog-posts', \App\Http\Controllers\Api\Tenant\BlogPostController::class);
    Route::apiResource('faqs', \App\Http\Controllers\Api\Tenant\FaqController::class);
});

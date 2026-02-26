<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Public\ActivityController;
use App\Http\Controllers\Api\Public\CourseController;
use App\Http\Controllers\Api\Public\DiveSiteController;
use App\Http\Controllers\Api\Public\StaffController;
use App\Http\Controllers\Api\Public\CentreController;
use App\Http\Controllers\Api\Public\ServiceController;
use App\Http\Controllers\Api\Public\RoomTypeController;
use App\Http\Controllers\Api\Public\RoomController;
use App\Http\Controllers\Api\Public\PromotionController;
use App\Http\Controllers\Api\Public\BlogPostController;
use App\Http\Controllers\Api\Public\FaqController;

Route::middleware(['public_tenant'])->prefix('public')->group(function () {
    Route::apiResource('activities', ActivityController::class)->only(['index', 'show']);
    Route::apiResource('courses', CourseController::class)->only(['index', 'show']);
    Route::apiResource('dive-sites', DiveSiteController::class)->only(['index', 'show']);
    Route::apiResource('staff', StaffController::class)->only(['index', 'show']);
    Route::apiResource('centres', CentreController::class)->only(['index', 'show']);
    Route::apiResource('services', ServiceController::class)->only(['index', 'show']);
    Route::apiResource('room-types', RoomTypeController::class)->only(['index', 'show']);
    Route::apiResource('rooms', RoomController::class)->only(['index', 'show']);
    Route::apiResource('promotions', PromotionController::class)->only(['index', 'show']);
    Route::apiResource('blog-posts', BlogPostController::class)->only(['index', 'show']);
    Route::apiResource('faqs', FaqController::class)->only(['index', 'show']);
});

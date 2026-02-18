<?php

use App\Http\Controllers\Api\SuperAdmin\TenantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:super_admin'])->prefix('super-admin')->group(function () {
    Route::apiResource('tenants', TenantController::class);
    Route::patch('/tenants/{tenant}/restore', [TenantController::class, 'restore']);
    Route::patch('/tenants/{tenant}/toggle-status', [TenantController::class, 'toggleStatus']);
});

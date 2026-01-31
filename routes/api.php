<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeneralInterestController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFeaturesController;
use App\Http\Controllers\ProjectImagesController;
use App\Http\Controllers\ProjectInterestsController;
use App\Http\Controllers\ProjectLocationsController;
use App\Http\Controllers\ProjectWarrantysController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitTypeController;
use Illuminate\Support\Facades\Route;

// -------------------------
// imge show
// -------------------------
Route::get('/project_features/{filename}', function ($filename) {
    $path = storage_path('app/public/project_features/'.$filename);

    if (! file_exists($path)) {
        abort(404, 'Image not found');
    }

    return response()->file($path);
})->where('filename', '.*'); // <-- غير 'path' لـ 'filename'

// Route to serve project sub-images
Route::get('/project-images/files/{path}', function ($path) {
    $fullPath = storage_path('app/public/'.$path);

    if (! file_exists($fullPath)) {
        abort(404, 'Image not found.');
    }

    return response()->file($fullPath);
})->where('path', '.*');

// Route to serve project main images
Route::get('/projects/{filename}', function ($filename) {
    $path = storage_path('app/public/projects/'.$filename);

    if (! file_exists($path)) {
        abort(404, 'Image not found.');
    }

    return response()->file($path);
});

// -------------------------
// Public Auth
// -------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// -------------------------
// Public Routes (عرض فقط)
// -------------------------
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/project/{project}', [ProjectController::class, 'show']);

Route::post('/maintenance-requests', [MaintenanceRequestController::class, 'store']);

Route::get('/project-images', [ProjectImagesController::class, 'index']);
Route::get('/project-images/{projectImage}', [ProjectImagesController::class, 'show']);

Route::get('/project-features', [ProjectFeaturesController::class, 'index']);
Route::get('/project-features/{projectFeature}', [ProjectFeaturesController::class, 'show']);

Route::get('/project-warranties', [ProjectWarrantysController::class, 'index']);
Route::get('/project-warranties/{projectWarranty}', [ProjectWarrantysController::class, 'show']);

Route::get('/project-locations', [ProjectLocationsController::class, 'index']);
Route::get('/project-locations/{projectLocation}', [ProjectLocationsController::class, 'show']);

Route::post('/project-interest', [ProjectInterestsController::class, 'store']);
Route::post('/project-interests', [ProjectInterestsController::class, 'store']);
Route::get('/project-interests/{projectInterest}', [ProjectInterestsController::class, 'show']);
Route::get('unit-types/{unitType}/units', [UnitController::class, 'byType']);

Route::post('general-interests', [GeneralInterestController::class, 'store']);
Route::get('general-interests/{id}', [GeneralInterestController::class, 'show']);
Route::put('general-interests/{id}', [GeneralInterestController::class, 'update']);
Route::delete('general-interests/{id}', [GeneralInterestController::class, 'destroy']);

// -------------------------
// Protected Routes (إنشاء، تعديل، حذف) - Passport
// -------------------------
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Projects
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::patch('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    // Maintenance Requests

    Route::put('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);
    Route::patch('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);
    Route::delete('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'destroy']);

    // Project Images
    Route::post('/project-images', [ProjectImagesController::class, 'store']);
    Route::put('/project-images/{projectImage}', [ProjectImagesController::class, 'update']);
    Route::patch('/project-images/{projectImage}', [ProjectImagesController::class, 'update']);
    Route::delete('/project-images/{projectImage}', [ProjectImagesController::class, 'destroy']);

    // Project Features
    Route::post('/project-features', [ProjectFeaturesController::class, 'store']);
    Route::put('/project-features/{projectFeature}', [ProjectFeaturesController::class, 'update']);
    Route::patch('/project-features/{projectFeature}', [ProjectFeaturesController::class, 'update']);
    Route::delete('/project-features/{projectFeature}', [ProjectFeaturesController::class, 'destroy']);

    // Project Warranties
    Route::post('/project-warranties', [ProjectWarrantysController::class, 'store']);
    Route::put('/project-warranties/{projectWarranty}', [ProjectWarrantysController::class, 'update']);
    Route::patch('/project-warranties/{projectWarranty}', [ProjectWarrantysController::class, 'update']);
    Route::delete('/project-warranties/{projectWarranty}', [ProjectWarrantysController::class, 'destroy']);

    // Project Locations
    Route::post('/project-locations', [ProjectLocationsController::class, 'store']);
    Route::put('/project-locations/{projectLocation}', [ProjectLocationsController::class, 'update']);
    Route::patch('/project-locations/{projectLocation}', [ProjectLocationsController::class, 'update']);
    Route::delete('/project-locations/{projectLocation}', [ProjectLocationsController::class, 'destroy']);

    // Project Interests

    Route::put('/project-interests/{projectInterest}', [ProjectInterestsController::class, 'update']);
    Route::patch('/project-interests/{projectInterest}', [ProjectInterestsController::class, 'update']);
    Route::delete('/project-interests/{projectInterest}', [ProjectInterestsController::class, 'destroy']);

    // units
    Route::post('/unit-types', [UnitTypeController::class, 'store']);
    Route::delete('/unit-types/{unitType}', [UnitTypeController::class, 'destroy']);
    Route::post('/units', [UnitController::class, 'store']);
    Route::delete('/units/{unit}', [UnitController::class, 'destroy']);
    // show interestes
    Route::get('general-interests', [GeneralInterestController::class, 'index']);
    Route::get('/project-interests', [ProjectInterestsController::class, 'index']);
    Route::get('/maintenance-requests', [MaintenanceRequestController::class, 'index']);
    Route::get('/maintenance-requests/{maintenanceRequest}', [MaintenanceRequestController::class, 'show']);
});

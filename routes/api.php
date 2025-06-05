<?php

/*
|--------------------------------------------------------------------------
| API Routes Documentation
|--------------------------------------------------------------------------
|
| This file contains all API routes for the LinaScheduler application.
| Routes are organized by functionality and follow RESTful conventions.
| Most routes require authentication and admin privileges.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication including login, registration,
| and password recovery. The password recovery route requires authentication
| to prevent unauthorized access.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);           // Handle user login
    Route::post('register', [RegisterController::class, 'register']);  // Handle new user registration
    Route::post('recover-password', [LoginController::class, 'recoverPassword'])->middleware('auth:sanctum'); // Handle password recovery
});

/*
|--------------------------------------------------------------------------
| Branch Management Routes
|--------------------------------------------------------------------------
|
| Routes for managing branches in the system. Supports CRUD operations
| plus soft delete management. All routes use {param} wildcards to support
| various ID formats.
|
*/

Route::prefix('admin/branches')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\BranchController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\BranchController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\BranchController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{branch}', [App\Http\Controllers\Admin\BranchController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\BranchController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\BranchController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\BranchController::class, 'showDeleted'])
        ->where('param', '.*');
});

/*
|--------------------------------------------------------------------------
| Flow Management Routes
|--------------------------------------------------------------------------
|
| Routes for managing workflow flows. Includes standard CRUD operations
| and soft delete functionality. Flows represent process sequences in
| the scheduling system.
|
*/

Route::prefix('admin/flows')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\FlowController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\FlowController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\FlowController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{flow}', [App\Http\Controllers\Admin\FlowController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\FlowController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\FlowController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\FlowController::class, 'showDeleted'])
        ->where('param', '.*');
});

/*
|--------------------------------------------------------------------------
| Room Management Routes
|--------------------------------------------------------------------------
|
| Core routes for managing rooms. These routes handle basic room operations
| before specialization into specific room types (class, study, library, etc.).
| Includes soft delete management.
|
*/

Route::prefix('admin/rooms')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{room}', [App\Http\Controllers\Admin\RoomController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomController::class, 'showDeleted'])
        ->where('param', '.*');
});

/*
|--------------------------------------------------------------------------
| Room Type Management Routes
|--------------------------------------------------------------------------
|
| These routes manage specialized room types. Each route supports:
| - GET /index: List all rooms of specific type
| - POST /store: Create new room of specific type
| - GET /show/{id}: View specific room details
| - PUT /update/{id}: Update room details
| - DELETE /destroy/{id}: Soft delete room
| - POST /restore/{id}: Restore soft-deleted room
|
| Parameters:
| - id: Room identifier
| - name: Room name/identifier
| - capacity: Room capacity
| - type_id: Room type identifier
| - equipment_ids[]: Array of equipment IDs assigned to room
|
*/

Route::prefix('admin/rooms/roomtypes')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{class}', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomTypes\RoomClassController::class, 'showDeleted'])
        ->where('param', '.*');
});

/*
|--------------------------------------------------------------------------
| Equipment Management Routes
|--------------------------------------------------------------------------
|
| Equipment management endpoints include:
| - GET /index: List all equipment
| - POST /store: Add new equipment
| - GET /show/{id}: View equipment details
| - PUT /update/{id}: Update equipment
| - DELETE /destroy/{id}: Soft delete equipment
| - POST /restore/{id}: Restore deleted equipment
| - POST /upload-image/{id}: Upload equipment image
|
| Parameters:
| - id: Equipment identifier
| - name: Equipment name
| - description: Equipment description
| - type_id: Equipment type identifier
| - status: Equipment status (active/inactive)
| - image: Equipment image file (for upload)
|
*/

Route::prefix('admin/Equipments')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{equipment}', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentController::class, 'showDeleted'])
        ->where('param', '.*');
});

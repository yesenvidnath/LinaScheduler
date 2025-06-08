<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('recover-password', [LoginController::class, 'recoverPassword'])->middleware('auth:sanctum');
});

Route::prefix('admin/rooms/roomtypes/laboratory_types')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{labtype}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryTypeController::class, 'showDeleted'])
        ->where('param', '.*');
});

Route::prefix('admin/rooms/roomtypes/laboratoriesroom')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{laboratory}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomTypes\LaboratoryController::class, 'showDeleted'])
        ->where('param', '.*');
});

Route::prefix('admin/roomimages')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomImageListController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomImageListController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomImageListController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{roomimage}', [App\Http\Controllers\Admin\RoomImageListController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomImageListController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomImageListController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomImageListController::class, 'showDeleted'])
        ->where('param', '.*');
});

Route::prefix('admin/Equipments/equipmenttypes')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{equipmenttype}', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentTypeController::class, 'showDeleted'])
        ->where('param', '.*');
});

Route::prefix('admin/Equipments/equipmentimages')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{equipmentimage}', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\Equipments\EquipmentImageController::class, 'showDeleted'])
        ->where('param', '.*');
});

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

Route::prefix('admin/courses')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Courses\CourseController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\Courses\CourseController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\Courses\CourseController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{course}', [App\Http\Controllers\Admin\Courses\CourseController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\Courses\CourseController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\Courses\CourseController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\Courses\CourseController::class, 'showDeleted'])
        ->where('param', '.*');
});

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

Route::prefix('admin/rooms/roomtypes/classes')->group(function () {
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

Route::prefix('admin/rooms/roomtypes/studyroom')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{study}', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomTypes\StudyController::class, 'showDeleted'])
        ->where('param', '.*');
});

Route::prefix('admin/rooms/roomtypes/libraryroom')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'index']);
    Route::post('/create', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'store']);
    Route::get('/show/{param}', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'show'])
        ->where('param', '.*');
    Route::put('/update/{library}', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'update']);
    Route::delete('/destroy/{param}', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'destroy'])
        ->where('param', '.*');
    Route::put('/recover/{param}', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'recover'])
        ->where('param', '.*');
    Route::get('/deleted/{param}', [App\Http\Controllers\Admin\RoomTypes\LibraryController::class, 'showDeleted'])
        ->where('param', '.*');
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

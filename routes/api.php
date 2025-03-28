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

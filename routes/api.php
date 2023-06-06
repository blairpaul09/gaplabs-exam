<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Enums\Role;
use App\Http\Controllers\Api\UserController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth'], function(){
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    //users Rest API
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store')->middleware('has_role:'.Role::SUPER_ADMIN->value.','.Role::ADMIN->value);
            Route::put('/{id}', 'update')->middleware('has_role:'.Role::SUPER_ADMIN->value.','.Role::ADMIN->value);
            Route::delete('/{id}', 'destroy')->middleware('has_role:'.Role::SUPER_ADMIN->value);
            Route::post('/{id}/assign-role', 'assignRole')->middleware('has_role:'.Role::SUPER_ADMIN->value);
        });
});

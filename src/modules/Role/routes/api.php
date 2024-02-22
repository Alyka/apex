<?php

use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/roles/privileges', [RoleController::class, 'addPrivilege']);
// Route::delete('/roles/privileges', [RoleController::class, 'removePrivilege']);
Route::post('/roles/assign', [RoleController::class, 'assign']);
Route::post('/roles/remove', [RoleController::class, 'remove']);
Route::get('/roles/all', [RoleController::class, 'all']);
Route::apiResource('/roles', RoleController::class);

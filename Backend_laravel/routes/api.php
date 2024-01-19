<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ListItemController;

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

Route::get('/users', [UserController::class, 'users']);

Route::get('/login', [UserController::class, 'userLogin']);
Route::get('/create', [UserController::class, 'createUser']);

Route::get('/getListItem/{id}', [ListItemController::class, 'getListItem']);
Route::get('/addTask', [ListItemController::class, 'addTask']);
Route::get('/removeTask', [ListItemController::class, 'removeTask']);
Route::get('/updateTask', [ListItemController::class, 'updateTask']);

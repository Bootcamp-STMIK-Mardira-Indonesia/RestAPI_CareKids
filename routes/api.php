<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authentication\AuthenticationController;
use App\Http\Controllers\Api\User\ProfileController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user/profile', [ProfileController::class, 'index'])->middleware('auth:sanctum');
Route::put('/user/profile', [ProfileController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/user/profile', [ProfileController::class, 'destroy'])->middleware('auth:sanctum');

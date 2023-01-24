<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\Content\ContentController;
use App\Http\Controllers\Api\Content\ArticleController;
use App\Http\Controllers\Api\Authentication\AuthenticationController;
use App\Http\Controllers\Api\Content\CategoryController;

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


//Register Admin
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

//Admin Panel
Route::group(['middleware' => ['AuthBasicApi']], function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);

    //test route category
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
});

//User Panel
Route::get('/content', [ContentController::class, 'index']);
Route::get('/articles', [ArticlesController::class, 'index']);

//test route articles
Route::post('/article', [ArticleController::class, 'store']); //pengerjaan
Route::get('/article', [ArticleController::class, 'index']);
Route::get('/article/{id}', [ArticleController::class, 'show']);
Route::put('/article/{id}', [ArticleController::class, 'update']); //pengerjaan
Route::delete('/article/{id}', [ArticleController::class, 'destroy']);
Route::get('/article/search/{keyword}', [ArticleController::class, 'search']); //pengerjaan
Route::get('/article/category/{id}', [ArticleController::class, 'showByCategory']);//pengerjaan 

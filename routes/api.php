<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Content\ArticleController;
use App\Http\Controllers\Api\Content\CommentController;
use App\Http\Controllers\Api\Content\CategoryController;
use App\Http\Controllers\Api\Authentication\AuthenticationController;
use App\Http\Controllers\Api\Content\ImageController;
use App\Http\Controllers\Api\Contact\ContactController;
use App\Http\Controllers\Api\About\AboutController;
use App\Http\Controllers\Api\Setting\SettingAppController;
use App\Http\Controllers\Api\Content\StatusController;

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
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);

    //Category Route
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    //Article Route
    Route::post('/article', [ArticleController::class, 'store']); //belum bisa upload file
    Route::put('/article/{id}', [ArticleController::class, 'update']); //->middleware('Author'); //pengerjaan
    Route::delete('/article/{id}', [ArticleController::class, 'destroy']); //->middleware('Author');
    Route::get('/article/trash/{id}', [ArticleController::class, 'showTrash']);
    Route::get('/article/trash', [ArticleController::class, 'showTrashAll']);
    Route::get('/article/restore/{id}', [ArticleController::class, 'restore']);
    Route::get('/article/force/{id}', [ArticleController::class, 'forceDelete']);

    //Upload Image Article
    Route::post('/image', [ImageController::class, 'upload']);
    Route::delete('/image/{id}', [ImageController::class, 'delete']);

    //Comment Route
    Route::delete('/comment/{id}', [CommentController::class, 'destroy']);
    Route::get('/comment/force/{id}', [CommentController::class, 'forceDelete']);

    //Contact Route
    Route::get('/contact', [ContactController::class, 'index']);
    Route::get('/contact/{id}', [ContactController::class, 'show']);
    Route::delete('/contact/{id}', [ContactController::class, 'destroy']);
    Route::get('/contact/force/{id}', [ContactController::class, 'forceDelete']);

    //About Route
    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);

    //Setting Route
    Route::post('/setting', [SettingAppController::class, 'store']);
    Route::put('/setting/{id}', [SettingAppController::class, 'update']);

    //Status Route
    Route::get('/status', [StatusController::class, 'index']);
    Route::post('/status', [StatusController::class, 'store']);
    Route::put('/status/{id}', [StatusController::class, 'update']);
});

//User Panel Article
Route::get('/article', [ArticleController::class, 'index']);
Route::get('/article/{id}', [ArticleController::class, 'show']);

//User Panel Category
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::get('/article/category/{id}', [ArticleController::class, 'showByCategory']);
Route::get('/article/search/{keyword}', [ArticleController::class, 'search']);
Route::get('/article/user/{id}', [ArticleController::class, 'showByUser']);
Route::get('/article/status/{id}', [ArticleController::class, 'showByStatus']);

//User Panel Comment
Route::post('/comment', [CommentController::class, 'store']);
Route::get('/comment', [CommentController::class, 'index']);
Route::put('/comment/{id}', [CommentController::class, 'update']);

//Contact
Route::post('/contact', [ContactController::class, 'store']);

//About
Route::get('/about', [AboutController::class, 'index']);


//Setting
Route::get('/setting', [SettingAppController::class, 'index']);

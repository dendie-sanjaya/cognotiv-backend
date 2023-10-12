<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogLikeController;
use App\Http\Controllers\BlogCommentLikeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return 'Welcome to Backend Cognotiv'; });
Route::get('welcome', function () { return 'Welcome to Backend Cognotiv'; });
Route::get('php', function () { return phpinfo(); });
Route::post('api/v1/user', [UserController::class, 'create']);
Route::post('api/v1/user/login', [UserController::class, 'login']);
Route::post('api/v1/blog', [BlogController::class, 'create']);
Route::put('api/v1/blog/{id}', [BlogController::class, 'update']);
Route::delete('api/v1/blog/{id}', [BlogController::class, 'delete']);
Route::get('api/v1/blog/{id}', [BlogController::class, 'read']);
Route::get('api/v1/blog', [BlogController::class, 'list']);
Route::post('api/v1/blogcomment', [BlogCommentController::class, 'create']);
Route::get('api/v1/blogcomment/{id}', [BlogCommentController::class, 'list']);
Route::post('api/v1/bloglike', [BlogLikeController::class, 'create']);
Route::post('api/v1/blogcommentlike', [BlogCommentLikeController::class, 'create']);

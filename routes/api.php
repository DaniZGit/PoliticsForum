<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// code redoing --

/* posts */
Route::prefix('/posts')->group(function() {
    Route::get('/', [PostController::class, 'getPosts']);
    Route::get('/{id}', [PostController::class, 'getPost']);
    Route::post('/create', [PostController::class, 'createPost'])->middleware('auth:sanctum');
});

/* categories */
Route::prefix('/categories')->group(function() {
    Route::get('/', [CategoryController::class, 'getCategories']);
    Route::get('/{id}', [CategoryController::class, 'getCategory']);
});

/* tags */
Route::prefix('/tags')->group(function() {
    Route::get('/', [TagController::class, 'getTags']);
    Route::post('/store', [TagController::class, 'store']);
});

/* user */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users/{id}', [UserController::class, 'getUser']);

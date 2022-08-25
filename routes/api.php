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
Route::get('/posts/all', [PostController::class, 'getPosts']);
Route::get('/categories', [CategoryController::class, 'getCategories']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/categories')->group( function() {
    Route::get('/', [CategoryController::class, 'getCategories']);
    Route::get('/posts', [CategoryController::class, 'getCategoriesPosts']);
    Route::get('/posts/details', [CategoryController::class, 'getCategoriesPostsDetails']);
    Route::post('/store', [CategoryController::class, 'store']);
    Route::get('/count', [CategoryController::class, 'getCategoriesCount']);
    Route::get('/{category}/posts/details', [CategoryController::class, 'getCategoryPostsDetails']);
    Route::get('/{category}/posts/search', [CategoryController::class, 'getCategoryFilteredPosts']);
    //Route::get('/{category}', [CategoryController::class, 'getCategoryCurrPagePosts']); // params ?page=2&size=5
    Route::get('/{category}', [CategoryController::class, 'getCategoryFilteredPosts']);
    Route::get('/{category}/count', [CategoryController::class, 'getCategoryPostsCount']);
});

Route::prefix('/tags')->group(function() {
    Route::get('/', [TagController::class, 'getTags']);
});

Route::get('/posts/{id}', [PostController::class, 'getPost']);

Route::post('/tags/store', [TagController::class, 'store']);

Route::get('/posts', [PostController::class, 'index']);

Route::get('/users', [UserController::class, 'getUsers']);
Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/register', [UserController::class, 'register']);
Route::post('/users/logout', [UserController::class, 'logout'])
    ->middleware('auth:sanctum');
Route::get('/users/{id}', [UserController::class, 'getUser']);

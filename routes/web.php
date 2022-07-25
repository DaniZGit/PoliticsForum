<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/forum');
});

Route::get('/forum/{vue?}', function () {
    return view('forum.index');
})->where('vue', '.*?');

Route::get('/user/{vue?}', function () {
    return view('user.index');
})->where('vue', '.*?');
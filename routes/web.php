<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

/* Route::get('/', function () {
    return view('welcome');
}); */


Route::resource('category', CategoryController::class)->middleware('auth'); // todas las rutas de category pasan por autorización
Route::resource('post', PostController::class)->middleware('auth');

Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home', [CategoryController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', [CategoryController::class, 'index'])->name('home');
});

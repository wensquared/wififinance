<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function() {
    Route::middleware('can:usergate')->group(function() {
        Route::get('/portfolio', function() {
            return view('portfolio.index');
        })->name('portfolio.index');
        Route::resource('/user','UserController')->except(['index','create','show','destroy']);
});
    Route::middleware('can:admingate')->group(function() {
        Route::resource('/admin','AdminController')->except(['create']);
    });
});
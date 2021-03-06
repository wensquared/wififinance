<?php

use App\Http\Controllers\AdminController;
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
    if(Auth::check()) {
        return redirect()->route('user.index');
    }
    return view('mainpage');
})->name('mainpage');

Auth::routes();

Route::get('/faq', function() {
    return view('faqs');
})->name('faqs');

Route::get('/info', 'InfoController@index')->name('info.index');
Route::post('/info_result','InfoController@search')->name('info.result');

Route::middleware('auth')->group(function() {

    Route::middleware('can:usergate')->group(function() {
        Route::get('/balance', 'BalanceController@index')->name('balance.index');
        Route::post('/info_watchlist','InfoController@watchlist')->name('info.watchlist');
        Route::resource('/user','UserController')->except(['create','store','show','destroy']);
    });

    Route::middleware('can:user_verified_gate')->group(function() {
        Route::put('/balance/update','BalanceController@update')->name('balance.update');
        Route::post('/buy','StocklistController@buy')->name('stocklist.buy');
        Route::post('/sell','StocklistController@sell')->name('stocklist.sell');
        Route::get('/user/stock_history/{ticker?}','StocklistController@show_stock_history')->name('stocklist.show_stock_history');
        Route::post('/user/search_ticker_history','StocklistController@search_ticker_history')->name('stocklist.search_ticker_history');
    });

    Route::middleware('can:admingate')->group(function() {
        Route::get('/admin/img/{img}','AdminController@showimg')->name('admin.showimg');
        Route::get('/admin/download/{img}','AdminController@download')->name('admin.download');
        Route::get('/admin/history/{user_id_history}','AdminController@show_history')->name('admin.user_history');
        Route::get('/admin/stock_history/{user_id_history}/{ticker?}','AdminController@show_stock_history')->name('admin.user_stock_history');
        Route::post('/admin/search_ticker_history','AdminController@search_ticker_history')->name('admin.search_ticker_history');
        Route::resource('/admin','AdminController')->except(['create']);
    });
});
<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('product', 'App\Http\Controllers\ProductController');


Route::get('/adicionar-produtos', "App\Http\Controllers\StockMovementController@screenAddStock")
    ->name('stock.display-add');

Route::get('/baixar-produtos', 'App\Http\Controllers\StockMovementController@screenRemoveStock')
    ->name('stock.display-remove');

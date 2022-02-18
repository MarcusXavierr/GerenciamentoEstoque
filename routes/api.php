<?php

use App\Http\Controllers\StockMovementController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/api/adicionar-produtos', "App\Http\Controllers\StockMovementController@addStock")->name('stock.add');
Route::post('/api/baixar-produtos', "App\Http\Controllers\StockMovementController@removeStock")->name('stock.remove');

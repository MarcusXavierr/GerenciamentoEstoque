<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StockMovementApiController;
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


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/listar-produtos', [StockMovementApiController::class, 'index']);
    Route::post('/adicionar-produtos', [StockMovementApiController::class, 'addStock'])->name('stock.add');
    Route::post('/baixar-produtos', [StockMovementApiController::class, "removeStock"])->name('stock.remove');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

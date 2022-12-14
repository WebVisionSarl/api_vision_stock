<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
use App\Http\Controllers\API\SettingsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------

| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*Login utilisé pour le Client Simple*/
Route::post('login', [UserController::class, 'login']);
Route::get('getsettings', [SettingsController::class, 'getsettings']);
Route::post('register', [UserController::class, 'registerUser']);
Route::post('configTheme', [SettingsController::class, 'configTheme']);
Route::post('save_product', [ProductController::class, 'saveProduct']);
Route::post('update_product', [ProductController::class, 'update_product']);
Route::post('delete_product', [ProductController::class, 'delete_product']);
Route::post('save_sales', [SaleController::class, 'saveSale']);
Route::post('soldecredit', [SaleController::class, 'soldecredit']);
Route::get('credits', [SaleController::class, 'credits']);
Route::get('all_product', [ProductController::class, 'getAllProduct']);
Route::get('all_sales', [SaleController::class, 'getAllSales']);
Route::get('detail_product/{id}', [ProductController::class, 'detailProduct']);

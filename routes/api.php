<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SaleController;
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



/*Login utilisé pour le Client Simple*/
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'registerUser']);
Route::post('save_product', [ProductController::class, 'saveProduct']);
Route::get('all_product', [ProductController::class, 'getAllProduct']);
Route::get('detail_product/{id}', [ProductController::class, 'detailProduct']);

<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'api'], function () {
    Route::get('subscribes/free', [SubscribeController::class, 'free']);
    Route::get('subscribes/premium', [SubscribeController::class, 'premium']);
    Route::get('urls/tree', [UrlController::class, 'tree']);
    Route::get('urls/index', [UrlController::class, 'index']);
    Route::get('packages/index', [PackageController::class, 'index']);
    Route::post('orders/create', [OrderController::class, 'create']);
    Route::get('orders/info', [OrderController::class, 'info']);
    Route::post('orders/exchange', [OrderController::class,'exchane']);
    Route::post('orders/index', [OrderController::class,'index']);
    Route::post('payment/callback', [PaymentController::class,'callback']);
});

<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UrlController;
use App\Http\Middleware\MemberAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'hello';
});

Route::get('/api/urls/groups', [UrlController::class, 'groups']);
Route::get('/api/urls/index', [UrlController::class, 'index']);
Route::get('/api/urls/recommendeds', [UrlController::class,'recommendeds']);
Route::middleware([MemberAuth::class])->prefix('api')->group(function () {
    Route::get('subscribes/free', [SubscribeController::class, 'free']);
    Route::get('subscribes/premium', [SubscribeController::class, 'premium']);
    Route::post('orders/create', [OrderController::class, 'create']);
    Route::get('orders/info', [OrderController::class, 'info']);
    Route::post('orders/exchange', [OrderController::class, 'exchane']);
    Route::post('orders/index', [OrderController::class, 'index']);
});
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/info', [OrderController::class, 'info'])->name('orders.info');
Route::get('/orders/pay', [OrderController::class, 'pay'])->name('orders.pay');
Route::get('/orders/index', [OrderController::class, 'index'])->name('orders.index');

Route::get('/api/payment/callback', [PaymentController::class, 'callback']);
Route::get('/api/payment/notify', [PaymentController::class, 'notify']);


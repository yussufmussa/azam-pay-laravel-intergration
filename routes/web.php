<?php

use App\Http\Controllers\CheckoutController;
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
    return view('checkout');
});

Route::get('/generateToken', [CheckoutController::class, 'generateToken']);
Route::get('/success', [CheckoutController::class, 'generateToken']);
Route::post('/mobileCheckout', [CheckoutController::class, 'mobileCheckout'])->name('checkout.mobile');


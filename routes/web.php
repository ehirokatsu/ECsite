<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', 'App\Http\Controllers\ShopController@index')->name('index');

Route::get('/create', 'App\Http\Controllers\ShopController@create')->name('create');

Route::post('/', 'App\Http\Controllers\ShopController@store')->name('store');

Route::get('/{id}', 'App\Http\Controllers\ShopController@show')->name('show')->where('id', '[0-9]+');

Route::get('/{id}/edit', 'App\Http\Controllers\ShopController@edit')->name('edit')->where('id', '[0-9]+');

Route::put('/{id}', 'App\Http\Controllers\ShopController@update')->name('update')->where('id', '[0-9]+');

Route::delete('/{id}', 'App\Http\Controllers\ShopController@destroy')->name('destroy')->where('id', '[0-9]+');

Route::get('/cart', 'App\Http\Controllers\CartController@index')->name('cart.index');

Route::post('/cart', 'App\Http\Controllers\CartController@store')->name('cart.store');

Route::delete('/cart/{id}', 'App\Http\Controllers\CartController@destroy')->name('cart.destroy')->where('id', '[0-9]+');




//問い合わせフォーム（Mailableクラスを使用）
Route::get('/contact', 'App\Http\Controllers\ContactController@index')->name('contact.index');
Route::post('/contact/confirm', 'App\Http\Controllers\ContactController@confirm')->name('contact.confirm');
Route::post('/contact/thanks', 'App\Http\Controllers\ContactController@send')->name('contact.send');

Route::get('/databaseManage', 'App\Http\Controllers\DatabaseManageController@index')->name('databaseManage.index');
Route::post('/databaseManage/export', 'App\Http\Controllers\DatabaseManageController@export')->name('databaseManage.export');

Route::post('/cart/complete', 'App\Http\Controllers\CartController@complete')->name('cart.complete');

Route::post('/cart/buy', 'App\Http\Controllers\CartController@buy')->name('cart.buy');

Route::post('/cart/buyConfirm', 'App\Http\Controllers\CartController@buyConfirm')->name('cart.buyConfirm');

//ログイン画面からリダイレクトするのでGETメソッドを使用する
Route::get('/cart/confirm', 'App\Http\Controllers\CartController@confirm')->name('cart.confirm');

Route::post('/cart/register', 'App\Http\Controllers\CartController@register')->name('cart.register');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
});

Route::get('/welcome', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

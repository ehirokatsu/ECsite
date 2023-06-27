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

//商品一覧画面
Route::get('/', 'App\Http\Controllers\ShopController@index')->name('index');

//商品詳細画面
Route::get('/{id}', 'App\Http\Controllers\ShopController@show')->name('show')->where('id', '[0-9]+');

//管理者のみ可能
Route::middleware(['can:admin'])->group(function() {

    //商品追加画面
    Route::get('/create', 'App\Http\Controllers\ShopController@create')->name('create');

    //商品追加確認画面
    Route::post('/createConfirm', 'App\Http\Controllers\ShopController@createConfirm')->name('createConfirm');

    //商品追加処理
    Route::post('/', 'App\Http\Controllers\ShopController@store')->name('store');

    //商品編集画面
    Route::get('/{id}/edit', 'App\Http\Controllers\ShopController@edit')->name('edit')->where('id', '[0-9]+');

    //商品追加確認画面
    Route::post('/{id}/editConfirm', 'App\Http\Controllers\ShopController@editConfirm')->name('editConfirm')->where('id', '[0-9]+');

    //商品編集処理
    Route::put('/{id}', 'App\Http\Controllers\ShopController@update')->name('update')->where('id', '[0-9]+');

    //商品削除処理
    Route::delete('/{id}', 'App\Http\Controllers\ShopController@destroy')->name('destroy')->where('id', '[0-9]+');
});


//カート一覧画面
Route::get('/cart', 'App\Http\Controllers\CartController@index')->name('cart.index');

//カート追加処理
Route::post('/cart', 'App\Http\Controllers\CartController@store')->name('cart.store');

//カート削除処理
Route::delete('/cart/{id}', 'App\Http\Controllers\CartController@destroy')->name('cart.destroy')->where('id', '[0-9]+');

//カート全削除処理
Route::delete('/cart', 'App\Http\Controllers\CartController@allDelete')->name('cart.allDelete');

//購入者情報入力画面
Route::get('/cart/buyer', 'App\Http\Controllers\CartController@buyer')->name('cart.buyer');

//購入者情報確認画面
Route::post('/cart/buyerConfirm', 'App\Http\Controllers\CartController@buyerConfirm')->name('cart.buyerConfirm');

//ログイン後、又は登録後の購入者情報確認画面
//ログイン画面からリダイレクトするのでGETメソッドを使用する
Route::get('/cart/regConfirm', 'App\Http\Controllers\CartController@regConfirm')->name('cart.regConfirm');

//ログイン画面からの登録処理
Route::post('/cart/register', 'App\Http\Controllers\CartController@register')->name('cart.register');

//カート内の商品数量更新処理
Route::put('/cart/{id}', 'App\Http\Controllers\CartController@quantityUpdate')->name('cart.quantityUpdate')->where('id', '[0-9]+');

//購入完了処理
Route::post('/cart/buyerComplete', 'App\Http\Controllers\CartController@buyerComplete')->name('cart.buyerComplete');

//購入完了処理
Route::post('/cart/regComplete', 'App\Http\Controllers\CartController@regComplete')->name('cart.regComplete');

//問い合わせフォーム（Mailableクラスを使用）
Route::get('/contact', 'App\Http\Controllers\ContactController@index')->name('contact.index');
Route::post('/contact/confirm', 'App\Http\Controllers\ContactController@confirm')->name('contact.confirm');
Route::post('/contact/thanks', 'App\Http\Controllers\ContactController@send')->name('contact.send');

Route::get('/databaseManage', 'App\Http\Controllers\DatabaseManageController@index')->name('databaseManage.index');
Route::post('/databaseManage/export', 'App\Http\Controllers\DatabaseManageController@export')->name('databaseManage.export');


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

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});    
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
    Route::get('/createConfirm', function () {
        return view('no');
    });

    //商品追加処理
    Route::post('/', 'App\Http\Controllers\ShopController@store')->name('store');

    //商品編集画面
    Route::get('/{id}/edit', 'App\Http\Controllers\ShopController@edit')->name('edit')->where('id', '[0-9]+');

    //商品追加確認画面
    Route::post('/{id}/editConfirm', 'App\Http\Controllers\ShopController@editConfirm')->name('editConfirm')->where('id', '[0-9]+');
    Route::get('/{id}/editConfirm', function () {
        return view('no');
    });

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

Route::get('/no', function () {
    return view('no');
})->name('no');

//注文履歴画面
Route::get('/orderHistory', 'App\Http\Controllers\OrderHistoryController@index')->name('orderHistory');

//注文履歴詳細画面
Route::get('/orderHistory/{id}', 'App\Http\Controllers\OrderHistoryController@show')->name('orderHistory.show')->where('id', '[0-9]+');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/htmlTest', function () {
    return view('htmlTest');
})->name('htmlTest');

Route::get('/vue/sample/test', function () {
    return Inertia::render('Sample/test');
})->name('vue.sample.test');

//Product系のAPI
//Route::resource('product', 'App\Http\Controllers\ApiProductController');
Route::get('product', 'App\Http\Controllers\ApiProductController@index')->name('api.index');
Route::get('product/search', 'App\Http\Controllers\ApiProductController@search')->name('api.search');
Route::delete('product/{id}', 'App\Http\Controllers\ApiProductController@destroy')->name('api.delete');
Route::post('/product', 'App\Http\Controllers\ApiProductController@store')->name('api.store');
Route::put('product/{id}', 'App\Http\Controllers\ApiProductController@update')->name('api.update');


Route::get('/api/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});


//Vue
Route::get('/vue', 'App\Http\Controllers\VueController@index')->name('vue.index');
Route::get('/vue/create', 'App\Http\Controllers\VueController@create')->name('vue.create');
Route::post('/vue', 'App\Http\Controllers\VueController@store')->name('vue.store');
Route::get('/vue/{id}/edit', 'App\Http\Controllers\VueController@edit')->name('vue.edit');
Route::put('/vue/{id}', 'App\Http\Controllers\VueController@update')->name('vue.update');
Route::delete('/vue/{id}', 'App\Http\Controllers\VueController@destroy')->name('vue.destroy');
Route::get('/vue/search', 'App\Http\Controllers\VueController@search')->name('vue.search');

Route::get('/vue/cart', 'App\Http\Controllers\VueCartController@index')->name('vue.cart.index');
Route::post('/vue/cart', 'App\Http\Controllers\VueCartController@store')->name('vue.cart.store');
Route::delete('/vue/cart/{id}', 'App\Http\Controllers\VueCartController@destroy')->name('vue.cart.destroy')->where('id', '[0-9]+');
Route::put('/vue/cart/{id}', 'App\Http\Controllers\VueCartController@quantityUpdate')->name('vue.cart.quantityUpdate')->where('id', '[0-9]+');


//Vue(Link)
Route::get('/vue/ajaxlink', 'App\Http\Controllers\VueAjaxLinkController@index')->name('vue.ajaxlink.index');
Route::get('/vue/ajaxlink/create', 'App\Http\Controllers\VueAjaxLinkController@create')->name('vue.ajaxlink.create');
Route::post('/vue/ajaxlink', 'App\Http\Controllers\VueAjaxLinkController@store')->name('vue.ajaxlink.store');
Route::get('/vue/ajaxlink/{id}/edit', 'App\Http\Controllers\VueAjaxLinkController@edit')->name('vue.ajaxlink.edit');
Route::put('/vue/ajaxlink/{id}', 'App\Http\Controllers\VueAjaxLinkController@update')->name('vue.ajaxlink.update');
Route::delete('/vue/ajaxlink/{id}', 'App\Http\Controllers\VueAjaxLinkController@destroy')->name('vue.ajaxlink.destroy');
Route::get('/vue/ajaxlink/search', 'App\Http\Controllers\VueAjaxLinkController@search')->name('vue.ajaxlink.search');

//axios
Route::get('/vue/ajaxaxios', function () {
    return Inertia::render('AjaxAxios/index');
})->name('vue.ajaxaxios.index');

Route::get('/vue/ajaxaxios/create', function () {
    return Inertia::render('AjaxAxios/create');
})->name('vue.ajaxaxios.create');

/*
Route::get('/vue/ajaxaxios/edit', function () {
    return Inertia::render('AjaxAxios/edit');
})->name('vue.ajaxaxios.edit');
*/
Route::get('/vue/ajaxaxios/{id}/edit', 'App\Http\Controllers\ApiProductController@edit')->name('vue.ajaxaxios.edit');


Route::get('/vue/game/quiz', function () {
    return Inertia::render('Game/quiz');
})->name('vue.game.quiz');

Route::get('/vue/sample/board', function () {
    return Inertia::render('Sample/board');
})->name('vue.sample.board');

Route::get('/vue/sample/calender', function () {
    return Inertia::render('Sample/calender');
})->name('vue.sample.calender');

Route::get('/vue/game/three', function () {
    return Inertia::render('Game/three');
})->name('vue.game.three');

Route::get('/vue/game/snake', function () {
    return Inertia::render('Game/snake');
})->name('vue.game.snake');

Route::get('/vue/game/slot', function () {
    return Inertia::render('Game/slot');
})->name('vue.game.slot');

require __DIR__.'/auth.php';

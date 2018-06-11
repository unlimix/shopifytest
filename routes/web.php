<?php

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

Route::prefix('shopify')->group(function () {
    Route::view('setup', 'pages.setup');
    Route::get('redirect', 'Shopify\SetupController@redirect');
    Route::get('install', 'Shopify\SetupController@install')
        ->middleware('shopify:embedded_app,false');
});

Route::middleware('shopify:embedded_app')->group(function () {
    Route::view('/', 'pages.index');
});

Route::get('/', 'Shopify\IndexController@index');
Route::get('/create-product', 'Shopify\IndexController@createProduct');
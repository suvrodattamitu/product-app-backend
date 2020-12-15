<?php

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

Route::group([

    'prefix'    => 'user',
    'namespace' => 'User'
    
],function(){
    
    //passed
    Route::post('register','AuthController@register');

    //passed
    Route::post('login','AuthController@login');

    //passed
    Route::post('product/add','ProductController@store');

    //passed
    Route::get('products/{token}','ProductController@allproducts');

    //passed
    Route::get('product/edit/{id}/{token}','ProductController@edit');

    //passed
    Route::put('product/update/{id}','ProductController@update');

    //passed
    Route::delete('product/delete/{id}','ProductController@destroy');

});

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/




//Route::get('/', function()
//{
//	return View::make('hello');
//});

Route::get('/', 'IndexController@index');
Route::get('/login.html', function(){
    return View::make('login');
});
Route::post('/login.html', 'IndexController@login');
Route::get('/register.html', function(){
    return View::make('register');
});
Route::post('/register.html', 'IndexController@register');


Route::get('/seller.line.html', 'SellerLineController@index');
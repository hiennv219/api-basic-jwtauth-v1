<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('register', function(){
 return view('api.register');
});


Route::get('login', function(){
 return view('api.login');
});

Route::group(['middleware' => ['cors'], 'prefix' => 'api'], function(){

	Route::post('register', 'APIController@register');

	Route::post('login', 'APIcontroller@login');


	//JWTAUTH IM HERE
	Route::group(['middleware' => 'jwt-auth'], function(){

		Route::post('get_user_details', 'APIController@get_user_details');

	});
});
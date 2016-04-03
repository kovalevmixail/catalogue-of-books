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
    return view('home');
});

Route::get('/book', 'BookController@index');
Route::post('/book', 'BookController@index');
Route::post('/book/save', 'BookController@save');


// Route::resource('/book', 'BookController', ['except' => [
//     'create', 'show', 'edit', 'update'
// ]]);

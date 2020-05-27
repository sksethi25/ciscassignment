<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/router/all', 'RouterController@showAll');
Route::get('/showshape', 'RouterController@showshape');
Route::resource('router', 'RouterController');

Route::get('/', 'RouterController@index');


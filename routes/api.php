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

Route::get('restrouter/showbysapid/{sapid}', 'RouterRestController@showBySapid');
Route::get('restrouter/showbyiprange/{ipstart}/{ipend}', 'RouterRestController@showByIpRange');
Route::delete('restrouter/deletebyip/{ip}', 'RouterRestController@deleteByIp');
Route::resource('restrouter', 'RouterRestController');

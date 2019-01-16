<?php

use Illuminate\Http\Request;

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

/* Credentials */
Route::get('/credentials/list', 'CredentialsController@list');

/* Amendments */
Route::get('/amendments/list', 'AmendmentsController@list');

/* Vote */
Route::get('/vote/current', 'VoteController@current');
Route::post('/vote/submit', 'VoteController@submit');

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
Route::post('/credentials/checkin', 'CredentialsController@checkIn');

/* Amendments */
Route::get('/amendments/list', 'AmendmentsController@list');
Route::post('/amendments/new', 'AmendmentsController@new');
Route::post('/amendments/{amendment}/open', 'AmendmentsController@open');

/* Vote */
Route::get('/vote/current', 'VoteController@current');
Route::post('/vote/submit', 'VoteController@submit');

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

Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/vote', 'VoteController@index')->name('vote');
Route::get('/my-votes', 'VoteController@archive')->name('my-votes');
Route::get('/amendments', 'AmendmentsController@index')->name('amendments');
Route::get('/credentials', 'CredentialsController@index')->name('credentials');
Route::get('/screen', 'ScreenController@index')->name('screen');
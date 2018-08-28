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

// ○☓ゲーム
Route::get('/', 'PlayController@index');
Route::post('/create', 'PlayController@create');

// オセロ
Route::post('/othello/create', 'Othello\OthelloController@input');
Route::get('/othello/reset', 'Othello\OthelloController@resetGame');
Route::get('/othello/pass', 'Othello\OthelloController@pass');
Route::get('/othello', 'Othello\OthelloController@index');

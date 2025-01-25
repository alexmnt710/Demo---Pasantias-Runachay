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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function()
{
	return View::make('word');
});

Route::post('/upload', 'wordController@upload');
Route::post('/generate', 'wordController@generate');


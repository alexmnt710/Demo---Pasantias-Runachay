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


Route::get('/',
	'SendMessageController@showWelcome'
);

Route::post('/send-mensage', function()
{

	$mensage = Input::get('mensage');
	return View::make('hello', array('mensage' => $mensage));
});



//para la demo de Whatsapp
Route::get('/demo-whs', function()
{

	return View::make('wsa');
});

Route::post('/send-mensage-whs',
	'SendMessageController@sendMensage'

);
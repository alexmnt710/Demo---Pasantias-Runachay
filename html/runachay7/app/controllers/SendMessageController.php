<?php

use Illuminate\Support\Facades\View;

class SendMessageController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		//recien ahi puedes codificar 
		return View::make('hello');
	}

	public function sendMensage()
	{
		$telefono = Input::get('telefono');

		return Redirect::to('/demo-whs')->with('success', 'Mensaje enviado correctamente al número: ' . $telefono);
	}

}

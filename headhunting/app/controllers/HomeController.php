<?php

class HomeController extends HelperController {

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
		return View::make('hello');
	}

	/**
	 *
	 * getMailGroups() : getMailGroups
	 *
	 *
	 * @return Object : JSON Of Groups.
	 *
	 */
	public function getMailGroups() {

			$name = Input::get('term');

			// Building Query for Getting Names of the Physician for type 2
			$groups = MailGroup::select(array("name", "id"))
									->where("name", "like", "%{$name}%")
									->get();
			return $this->sendJsonResponseOnly($groups->toArray());
	}
}

<?php

class ClientController extends \BaseController {


	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('Client.newClient')->with(array('title' => 'Add Client'));
	}


	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function createClient()
	{

		if(Auth::user()->getRole() <= 3) {
			Validator::extend('has', function($attr, $value, $params) {

				if(!count($params)) {

					throw new \InvalidArgumentException('The has validation rule expects at least one parameter, 0 given.');
				}

				foreach ($params as $param) {
					switch ($param) {
						case 'num':
							$regex = '/\pN/';
							break;
						case 'letter':
							$regex = '/\pL/';
							break;
						case 'lower':
							$regex = '/\p{Ll}/';
							break;
						case 'upper':
							$regex = '/\p{Lu}/';
							break;
						case 'special':
							$regex = '/[\pP\pS]/';
							break;
						default:
							$regex = $param;
					}

					if(! preg_match($regex, $value)) {
						return false;
					}
				}

				return true;
			});

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email|unique:clients,email',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'phone' => 'max:14',
							'company_name' =>  'max:100',
					)
			);

			if($validate->fails()) {

				return Redirect::to('add-client')
							   ->withErrors($validate)
							   ->withInput();
			} else {

				$client = new Client();
				$client->first_name = Input::get('first_name');
				$client->last_name = Input::get('last_name');
				$client->email = Input::get('email');
				$client->phone = Input::get('phone');
				$client->company_name = Input::get('company_name');
				$client->created_by = Auth::user()->id;

				// Checking Authorised or not
				if($client->save()) {
					return Redirect::to('clients');
				} else {
					return Redirect::to('add-client')->withInput();
				}
			}
		}
	}


	/**
	 *
	 * clientList() : Client List
	 *
	 * @return Object : View
	 *
	 */
	public function clientList() {
		$clients = Client::with(array())->get();
		return View::make('Client.clientList')->with(array('title' => 'Clients List', 'clients' => $clients));
	}


	/**
	 *
	 * viewClient() : View Client
	 *
	 * @return Object : View
	 *
	 */
	public function viewClient($id) {

		if(Auth::user()->getRole() <= 3) {

			$client = Client::with(array('createdby'))->where('id', '=', $id)->get();

			if(!$client->isEmpty()) {
				$client = $client->first();
				return View::make('Client.viewClient')
						   ->with(array('title' => 'View Client', 'client' => $client));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}


	/**
	 *
	 * editClient() : Edit Client
	 *
	 * @return Object : View
	 *
	 */
	public function editClient($id) {

		if(Auth::user()->getRole() <= 3) {
			
			$client = Client::with(array())->where('id', '=', $id)->get();

			if(!$client->isEmpty()) {
				$client = $client->first();
				return View::make('Client.editClient')
						   ->with(array('title' => 'Edit Client', 'client' => $client));
			} else {

				return Redirect::route('dashboard');
			}

		} else {

			return Redirect::route('dashboard');
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateClient($id)
	{
		if(Auth::user()->getRole() <= 3) {
			Validator::extend('has', function($attr, $value, $params) {

				if(!count($params)) {

					throw new \InvalidArgumentException('The has validation rule expects at least one parameter, 0 given.');
				}

				foreach ($params as $param) {
					switch ($param) {
						case 'num':
							$regex = '/\pN/';
							break;
						case 'letter':
							$regex = '/\pL/';
							break;
						case 'lower':
							$regex = '/\p{Ll}/';
							break;
						case 'upper':
							$regex = '/\p{Lu}/';
							break;
						case 'special':
							$regex = '/[\pP\pS]/';
							break;
						default:
							$regex = $param;
					}

					if(! preg_match($regex, $value)) {
						return false;
					}
				}

				return true;
			});

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'phone' => 'max:14',
							'company_name' =>  'max:100',
					)
			);
			if($validate->fails()) {

				return Redirect::route('edit-client', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {

				$client = Client::find($id);

				$email = Input::get('email');
				if($email && $email != $client->email){
					if(!Client::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-client', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$client->email = $email;
				}

				$client->first_name = Input::get('first_name');
				$client->last_name = Input::get('last_name');
				$client->phone = Input::get('phone');
				$client->company_name = Input::get('company_name');

				// Checking Authorised or not
				if($client->save()) {

					return Redirect::route('client-list');
				} else {

					return Redirect::route('edit-client')->withInput();
				}
			}
		}
	}


	/**
	 *
	 * deleteClient() : Delete Client
	 *
	 * @return Object : View
	 *
	 */
	public function deleteClient($id) {
		if(Auth::user()->getRole() <= 3) {
			if(Client::find($id)->delete()) {
				return Redirect::route('client-list');
			}
		}
	}


}

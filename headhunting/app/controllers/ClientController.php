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
	public function clientUpload()
	{
		return View::make('Client.uploadClient')->with(array('title' => 'Upload Client'));
	}


	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function uploadClientCsv()
	{
		$file = fopen($_FILES['client_csv']['tmp_name'], 'r');
		$count = 0;
		while (($line = fgetcsv($file)) !== FALSE) {
			if ($count){
				try{
					if(Client::where('email', '=', $line[0])->get()->isEmpty()) {
						$client = new Client();
						$client->email = $line[0];
						$client->first_name = $line[1];
						$client->last_name = $line[2];
						$client->phone = $line[3];
						$client->phone_ext = $line[4];
						$client->company_name = $line[5];
						$client->created_by = Auth::user()->id;
						$client->save();
						$mail_group = new MailGroupMember();
						$mail_group->group_id = 1;
						$mail_group->user_id = $client->id;
						$mail_group->save();
					}
				} catch(Exception $e){
					print "failing for ".$line[1];
				}
			} 
			$count++;
		}
		fclose($file);
		return Redirect::route('client-list');
	}
	

	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function createClient()
	{
		if(Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email|unique:clients,email',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'phone' => 'max:14',
							'phone_ext' => 'max:10',
							'company_name' =>  'max:100'
					), 
					array(
        				'email.unique'=>Client::where('email', 'like', Input::get('email'))->exists()?'This client is already working with '.Client::where('email', 'like', Input::get('email'))->first()->createdby->first_name:"Email is already in use.",
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
				$client->phone_ext = Input::get('phone_ext');
				$client->company_name = Input::get('company_name');
				$client->created_by = Auth::user()->id;

				// Checking Authorised or not
				if($client->save()) {
					$mail_group = new MailGroupMember();
					$mail_group->group_id = 1;
					$mail_group->user_id = $client->id;
					$mail_group->save();
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
		if(Auth::user()->hasRole(1)) {
			$clients = Client::all();
		} else {
			$clients = Client::where('created_by', '=', Auth::user()->id)->get();
		}
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

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {

			if(Auth::user()->hasRole(1)) {
				$client = Client::with(array('createdby'))->where('id', '=', $id)->get();
			} else {
				$client = Client::with(array('createdby'))->where('id', '=', $id)->where('created_by', '=', Auth::user()->id)->get();
			}
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

		//if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {
		if(Auth::user()->hasRole(1)) {
			
			if(Auth::user()->hasRole(1)) {
				$client = Client::where('id', '=', $id)->get();
			} else {
				$client = Client::where('id', '=', $id)->where('created_by', '=', Auth::user()->id)->get();
			}

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

		// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'phone' => 'max:14',
							'phone_ext' => 'max:10',
							'company_name' =>  'max:247',
					)
			);
			if($validate->fails()) {

				return Redirect::route('edit-client', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {


				if(Auth::user()->hasRole(1)) {
					$client = Client::find($id);
				} else {
					$client = Client::where('id', '=', $id)->where('created_by', '=', Auth::user()->id)->get();
				}

				if(!$client) {
					return Redirect::route('dashboard');			
				}

				$email = Input::get('email');
				if($email && $email != $client->email){
					if(!Client::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-client', array('id' => $id))
						               ->withInput()
									   ->withErrors(array(
					        				'email'=>Client::where('email', 'like', Input::get('email'))->exists()?'This client is already working with '.Client::where('email', 'like', Input::get('email'))->first()->createdby->first_name:"Email is already in use."
									    ));
					}
					$client->email = $email;
				}

				$client->first_name = Input::get('first_name');
				$client->last_name = Input::get('last_name');
				$client->phone = Input::get('phone');
				$client->phone_ext = Input::get('phone_ext');
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
		//if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {
			if(Auth::user()->hasRole(1)) {
				$client = Client::where('id', '=', $id)->get()->first();
				if( !empty($client) && 	MailGroupMember::where('user_id', '=', $client->id)->where('group_id', '=', 1)->delete() && $client->delete()) {
					return Redirect::route('client-list');
				}
			}
			//else {
			//	$client = Client::where('id', '=', $id)->where('created_by', '=', Auth::user()->id)->get()->first();
			//}
			
			//if( !empty($client) && 	MailGroupMember::where('user_id', '=', $client->id)->where('group_id', '=', 1)->delete() && $client->delete()) {
			//	return Redirect::route('client-list');
			//}
		//}
	}

}

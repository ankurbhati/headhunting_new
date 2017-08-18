<?php

class ClientController extends HelperController {


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
		$message = '';
		$already_exist = '';
		$added_count = 0;
		while (($line = fgetcsv($file)) !== FALSE) {
			if ($count){
				try{
					$line[0] = str_ireplace(",", "", str_ireplace(",", "", trim($line[0])));
					if(!filter_var($line[0], FILTER_VALIDATE_EMAIL)) {
					    continue;
					}
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
						$added_count++;
						$mail_group = new MailGroupMember();
						$mail_group->group_id = 1;
						$mail_group->user_id = $client->id;
						$mail_group->save();
					} else {
						$already_exist .= $line[0]." Already exists<br />";			
					}
				} catch(Exception $e){
					$message .= "Error while adding email: ".$line[0]."<br />";
				}
			} 
			$count++;
		}
		fclose($file);
		$message = $already_exist.$message.'<b><br />';

		/* User activity */
		$description = Config::get('activity.client_multi_upload');
		$authUser = Auth::user();
		$formatted_description = sprintf(
			$description,
			'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
			$added_count
		);
		$this->saveActivity('4', $formatted_description);

		Session::flash('upload_result', $message);
		Session::flash('flashmessagetxt', 'Uploaded Successfully!!');
		return Redirect::route('client-upload');
	}
	

	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function createClient()
	{
		if(Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)) {

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

					/* User activity */
					$description = Config::get('activity.client_single_upload');
					$authUser = Auth::user();
					$formatted_description = sprintf(
						$description,
						'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
						'<a href="/view-client/'.$client->id.'">'.$client->email.'</a>'
					);
					$this->saveActivity('5', $formatted_description);

					Session::flash('flashmessagetxt', 'Added Successfully!!'); 
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
		$q = Client::query();
		if(!(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))) {
			$q->where('created_by', '=', Auth::user()->id);
		}

		if(!empty(Input::get('company_name'))) {
			$q->where('company_name', 'like', "%".Input::get('company_name')."%");
		}
		if(!empty(Input::get('email'))) {
			$q->where('email', 'like', "%".Input::get('email')."%");
		} 
		if(!empty(Input::get('first_name'))){
			$q->where('first_name', 'like', "%".Input::get('first_name')."%");	
		}
		if(!empty(Input::get('last_name'))) {
			$q->where('last_name', 'like', "%".Input::get('last_name')."%");	
		}
		if(!empty(Input::get('phone'))) {
			$q->where('phone', 'like', "%".Input::get('phone')."%");	
		}
		if(!empty(Input::get('phone_ext'))) {
			$q->where('phone_ext', 'like', "%".Input::get('phone_ext')."%");	
		}
		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}

		if(!empty(Input::get('csv_download_input'))) {
			$arrSelectFields = array('email', 'first_name', 'last_name', 'phone', 'phone_ext', 'company_name');

	        $q->select($arrSelectFields);
	        $data = $q->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('email', 'first_name', 'last_name', 'phone', 'phone_ext', 'company_name');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Email', 'First Name', 'Last Name', 'Phone', 'Phone Ext', 'Company Name');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}
		
		$clients = $q->paginate(100);

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

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) {

			if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
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
		if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
			
			if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
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
		if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8)) {

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
					Session::flash('flashmessagetxt', 'Updated Successfully!!'); 
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
			if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
				$client = Client::where('id', '=', $id)->first();
				$job_posts = JobPost::where('client_id', '=', $client->id);
				if(!$job_posts->get()->isEmpty()) {
					$job_posts->update(['client_id' => Config::get('app.dummy_client_id')]);	
				}
				if( !empty($client) && 	MailGroupMember::where('user_id', '=', $client->id)->where('group_id', '=', 1)->delete() && $client->delete()) {
					Session::flash('flashmessagetxt', 'Deleted Successfully!!'); 
					return Redirect::route('client-list');
				}
			}
	}


	/**
	 * validating candidate while creating.
	 *
	 * @return Response
	 */
	public function validateClient()
	{

		$response = array();
    	$response['error'] = false;
		// Server Side Validation.
		$validate=Validator::make (
			Input::all(), array(
					'email' => 'required|email|max:50|email|unique:clients,email',
			)
		);

		if($validate->fails()) {
			$response['error'] = true;
    		$response['message'] = 'Client Already Exists';
		} else {
			$response['message'] = 'success';
		}
		return $this->sendJsonResponseOnly($response);
	}
}

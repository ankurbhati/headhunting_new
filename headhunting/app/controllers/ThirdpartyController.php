<?php

class ThirdpartyController extends HelperController {


	private $resume_target_dir = 'uploads/documents/';
	private $document_size = 5000000;
	
	/**
	 * Show the form for creating a new Thirdparty.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('Thirdparty.newThirdparty')->with(array('title' => 'Add Third Party'));
	}

	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function thirdpartyUpload()
	{
		return View::make('Thirdparty.uploadThirdparty')->with(array('title' => 'Upload Third Party'));
	}


	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function uploadThirdpartyCsv()
	{
		$file = fopen($_FILES['third_party_csv']['tmp_name'], 'r');
		$count = 0;
		$failed = 0;
		$user_id = Auth::user()->id;
		$message = '';
		$already_exist = '';
		$added_count = 0;
		$existing_added_count = 0;

		while (($line = fgetcsv($file)) !== FALSE) {
			if ($count == 0){
				$count++;
				continue;
			}
			try{
				$line[1] = str_ireplace(",", "", str_ireplace(",", "", trim($line[1])));
				if(!filter_var($line[1], FILTER_VALIDATE_EMAIL)) {
				    continue;
				}
				$thirdparty = Thirdparty::where('email', '=', $line[1])->get();

				if(!$thirdparty->isEmpty()) {
					$thirdparty = $thirdparty->first();
					$thirdpartyuser = Thirdpartyuser::where('source_id', '=', $thirdparty->id)->where('user_id', '=', $user_id)->get();
					if(!$thirdpartyuser->isEmpty()) {
						$thirdpartyuser = $thirdpartyuser->first();
						$failed++;
						$already_exist .= $line[1]." Already belongs to you<br />";
					} else {
						$thirdpartyuser = new Thirdpartyuser();
						$thirdpartyuser->user_id = $user_id;
						$thirdpartyuser->source_id = $thirdparty->id;
						$thirdpartyuser->save();
						$existing_added_count++;
					}
				} else {
					$third_party = new Thirdparty();
					$third_party->poc = $line[0];
					$third_party->email = $line[1];
					$org_array = $this->getThirdPartyOrganisation($third_party->email);
					if (!$org_array[0]) {
						// Setting status as mca and nsa data not provided
						$third_party->status = 2;
					}
					$third_party->source_organisation_id = $org_array[1];
					$third_party->phone = $line[2];
					$third_party->phone_ext = $line[3];
					$third_party->created_by = $user_id;
					$third_party->save();
					$mail_group = new MailGroupMember();
					$mail_group->group_id = 3;
					$mail_group->user_id = $third_party->id;
					$mail_group->save();
					$thirdpartyuser = new Thirdpartyuser();
					$thirdpartyuser->user_id = Auth::user()->id;
					$thirdpartyuser->source_id = $third_party->id;
					$thirdpartyuser->save();
					$added_count++;
				}
			} catch(Exception $e) {
				$message .= "Error while adding email: ".$line[1]."<br />";
			} 
			$count++;
		}
		fclose($file);
		$message = $already_exist.$message.'<b><br />Report : '.$failed.' already exists out of '.($count-1)."</b>";

		/* User activity */
		$description = Config::get('activity.third_party_multi_upload');
		$authUser = Auth::user();
		$formatted_description = sprintf(
			$description,
			'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
			$added_count,
			$existing_added_count
		);
		$this->saveActivity('2', $formatted_description);

		Session::flash('flashmessagetxt', 'Uploaded Successfully!!');
		Session::flash('upload_result', $message);
		return Redirect::route('vendor-third-party');
	}

	/**
	 * Show the form for creating a new Thirdparty.
	 *
	 * @return Response
	 */
	public function createThirdparty()
	{

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'email' =>  'required|max:50|email',
							'poc' => 'max:50',
							'phone' => 'max:14',
							'phone_ext' => 'max:10'
					)
			);
			
			if($validate->fails()) {

				return Redirect::to('add-third-party')
							   ->withErrors($validate)
							   ->withInput();
			} else {
				$email = Input::get('email');
				$user_id = Auth::user()->id;
				$thirdparty = Thirdparty::where('email', '=', $email)->get();
				if(!$thirdparty->isEmpty()) {
					$thirdparty = $thirdparty->first();
					$thirdpartyuser = Thirdpartyuser::where('source_id', '=', $thirdparty->id)->where('user_id', '=', $user_id)->get();
					if(!$thirdpartyuser->isEmpty()) {
						$thirdpartyuser = $thirdpartyuser->first();
						Session::flash('email_error',  $email.' already belongs to you');
						return Redirect::to('add-third-party')->withInput();
					} else {
						$thirdpartyuser = new Thirdpartyuser();
						$thirdpartyuser->user_id = $user_id;
						$thirdpartyuser->source_id = $thirdparty->id;
						$thirdpartyuser->save();
					}
				} else {
					$thirdparty = new Thirdparty();
					$thirdparty->poc = Input::get('poc');
					$thirdparty->email = $email;
					$thirdparty->phone = Input::get('phone');
					$thirdparty->phone_ext = Input::get('phone_ext');
					$thirdparty->created_by = $user_id;
					$org_array = $this->getThirdPartyOrganisation($thirdparty->email);
					if (!$org_array[0]) {
						// Setting status as mca and nsa data not provided
						$thirdparty->status = 2;
					}
					$thirdparty->source_organisation_id = $org_array[1];
					/*$thirdparty->nca_company_name = Input::get('nca_company_name');
					$thirdparty->nca_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('nca_activation_date')));
					$thirdparty->msa_company_name = Input::get('msa_company_name');
					$thirdparty->msa_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('msa_activation_date')));*/
					
					// Checking Authorised or not
					if($thirdparty->save()) {

						$mail_group = new MailGroupMember();
						$mail_group->group_id = 3;
						$mail_group->user_id = $thirdparty->id;
						$mail_group->save();
						
						/*if(isset($_FILES['nca_document']['tmp_name']) && !empty($_FILES['nca_document']['tmp_name'])) {
							list($msg, $fileType) = $this->check_resume_validity("nca_document");
							if($msg){
								# error
								Session::flash('nca_document_error', $msg);
								return Redirect::route('add-third-party')->withInput();
							} else {
								# No error					
								list($msg, $target_file) = $this->upload_document($thirdparty, "nca_document");
								if($msg) {
									//error, delete candidate or set flash message
								} else {
									$tmp = explode("/", $target_file);
									$thirdparty->nca_document = end($tmp);
								}
							}
						}

						if(isset($_FILES['msa_document']['tmp_name']) && !empty($_FILES['msa_document']['tmp_name'])) {
							list($msg, $fileType) = $this->check_resume_validity("msa_document");
							if($msg){
								# error
								Session::flash('msa_document_error', $msg);
								return Redirect::route('add-third-party')->withInput();
							} else {
								# No error					
								list($msg, $target_file) = $this->upload_document($thirdparty, "msa_document");
								if($msg) {
									//error, delete candidate or set flash message
								} else {
									$tmp = explode("/", $target_file);
									$thirdparty->msa_document = end($tmp);
								}
							}
						}

						$thirdparty->save();*/
						$thirdpartyuser = new Thirdpartyuser();
						$thirdpartyuser->user_id = Auth::user()->id;
						$thirdpartyuser->source_id = $thirdparty->id;
						$thirdpartyuser->save();

						/* User activity */
						$description = Config::get('activity.third_party_single_upload');
						$authUser = Auth::user();
						$formatted_description = sprintf(
							$description,
							'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
							'<a href="/view-third-party/'.$thirdparty->id.'">'.$thirdparty->email.'</a>'
						);
						$this->saveActivity('3', $formatted_description);

						Session::flash('flashmessagetxt', 'Added Successfully!!');
						return Redirect::to('third-parties');
					} else {
						return Redirect::to('add-third-party')->withInput();
					}
				}
			}
		}
	}


	/**
	 *
	 * vendorList() : Vendor List
	 *
	 * @return Object : View
	 *
	 */
	public function thirdpartyList() {

		$q = Thirdparty::query();
		
		if(!empty(Input::get('email'))) {
			$q->where('email', 'like', "%".Input::get('email')."%");
		} 
		if(!empty(Input::get('poc'))){
			$q->where('poc', 'like', "%".Input::get('poc')."%");	
		}
		if(!empty(Input::get('status')) || Input::get('status') == '0' ) {
			$q->where('status', '=', Input::get('status'));	
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
			$arrSelectFields = array('email', 'poc', 'phone', 'phone_ext', 'status');

	        $q->select($arrSelectFields);
	        $data = $q->whereHas('thirdPartyUsers', function($q) {
			    $q->where('user_id','=', Auth::user()->id);
			})->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('email', 'poc', 'phone', 'phone_ext', 'status');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Email', 'Point Of Contact', 'Phone', 'Phone Ext', 'Status(0=>Blacklisted, 1=>Active)');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}

		if(Auth::user()->getRole() == 4 || Auth::user()->getRole() == 5) {
			$thirdparties = $q->whereHas('thirdPartyUsers', function($q)
				{
				    $q->where('user_id','=', Auth::user()->id);
				})->paginate(100);
		} else {
			$thirdparties = $q->paginate(100);
		}

		return View::make('Thirdparty.thirdpartyList')->with(array('title' => 'Third Party List', 'thirdparties' => $thirdparties));
	}

	/**
	 *
	 * vendorList() : Vendor List
	 *
	 * @return Object : View
	 *
	 */
	public function blacklistthirdpartyList() {

		$q = Thirdparty::query();
		
		if(!empty(Input::get('email'))) {
			$q->where('email', 'like', "%".Input::get('email')."%");
		} 
		if(!empty(Input::get('poc'))){
			$q->where('poc', 'like', "%".Input::get('poc')."%");	
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
			$arrSelectFields = array('email', 'poc', 'phone', 'phone_ext', 'status');

	        $q->select($arrSelectFields);
	        $data = $q->whereHas('thirdPartyUsers', function($q) {
			    $q->where('user_id','=', Auth::user()->id)->where('status', '=', '1');
			})->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('email', 'poc', 'phone', 'phone_ext', 'status');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Email', 'Point Of Contact', 'Phone', 'Phone Ext', 'Status(1=>Blacklisted, 0=>Active)');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}



		if(Auth::user()->getRole() == 4 || Auth::user()->getRole() == 5) {
			$thirdparties = $q->whereHas('thirdPartyUsers', function($q)
				{
				    $q->where('user_id','=', Auth::user()->id)->where('status', '=', '1');
				})->paginate(100);
		} else {
			$thirdparties = $q->where('status', '=', '1')->paginate(100);
		}

		return View::make('Thirdparty.blacklistthirdpartyList')->with(array('title' => 'Black List Third Party List', 'thirdparties' => $thirdparties));
	}


	/**
	 *
	 * vendorList() : Vendor List
	 *
	 * @return Object : View
	 *
	 */
	public function thirdpartyListHasDocument($id) {

		$q = Thirdparty::query();
		
		
		if(!empty(Input::get('email'))) {
			$q->where('email', 'like', "%".Input::get('email')."%");
		} 
		if(!empty(Input::get('poc'))){
			$q->where('poc', 'like', "%".Input::get('poc')."%");	
		}
		if(!empty(Input::get('phone'))) {
			$q->where('phone', 'like', "%".Input::get('phone')."%");	
		}
		if($id != 1) {
			if(!empty(Input::get('company_name'))) {
				$q->where('nca_company_name', 'like', "%".Input::get('company_name')."%");	
			}
		} else {
			if(!empty(Input::get('company_name'))) {
				$q->where('msa_company_name', 'like', "%".Input::get('company_name')."%");	
			}
		}

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
			
		if($id == 1) {
			$q->where('msa_document', '!=', "");
		} else {
			$q->where('nca_document', '!=', "");
		}
		
		if(!empty(Input::get('csv_download_input'))) {
			$arrSelectFields = array('email', 'poc', 'phone', 'phone_ext', 'status');

	        $q->select($arrSelectFields);
	        $data = $q->whereHas('thirdPartyUsers', function($q) {
			    $q->where('user_id','=', Auth::user()->id);
			})->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('email', 'poc', 'phone', 'phone_ext', 'status');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Email', 'Point Of Contact', 'Phone', 'Phone Ext', 'Status(0=>Blacklisted, 1=>Active)');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}

		if(Auth::user()->getRole() == 4 || Auth::user()->getRole() == 5) {
			$thirdparties = $q->whereHas('thirdPartyUsers', function($q)
				{
				    $q->where('user_id','=', Auth::user()->id);
				})->paginate(100);
		} else {
			$thirdparties = $q->paginate(100);
		}

		return View::make('Thirdparty.thirdpartyListSource')->with(array('title' => 'Third Party List', 'thirdparties' => $thirdparties, 'id' => $id));
	}


	/**
	 *
	 * viewVendor() : View Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function viewThirdparty($id) {

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5)  || Auth::user()->hasRole(8) ) {

			$thirdparty = Thirdpartyuser::with(array('vendors'))->where('user_id', '=', Auth::user()->id)->where('source_id', '=', $id)->get();
			
			if(!$thirdparty->isEmpty()) {
				$thirdparty = $thirdparty->first()->vendors[0];
				return View::make('Thirdparty.viewThirdparty')
						   ->with(array('title' => 'View Third Party', 'thirdparty' => $thirdparty));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}


	/**
	 *
	 * editVendor() : Edit Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function editThirdparty($id) {

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {

			//$thirdparty = Thirdparty::with(array('createdby'))->where('id', '=', $id)->get();
			$thirdparty = Thirdpartyuser::with(array('vendors'))->where('user_id', '=', Auth::user()->id)->where('source_id', '=', $id)->get();

			if(!$thirdparty->isEmpty()) {
				$thirdparty = $thirdparty->first()->vendors[0];
				return View::make('Thirdparty.editThirdparty')
						   ->with(array('title' => 'Third Party', 'thirdparty' => $thirdparty));
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
	public function updateThirdparty($id)
	{
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			// Server Side Validation.
			$validate=Validator::make (
				Input::all(), array(
					'email' =>  'required|max:50|email',
					'poc' => 'max:50',
					'phone' => 'max:14',
					'phone_ext' => 'max:10',
					'document_type' => 'max:1'
				)
			);
			if($validate->fails()) {

				return Redirect::route('edit-third-party', array('id' => $id))
								->wifthErrors($validate)
								->withInput();
			} else {

				$thirdparty = Thirdparty::find($id);

				/*$email = Input::get('email');
				if($email && $email != $thirdparty->email){
					if(!Thirdparty::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-third-party', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$thirdparty->email = $email;
				}*/
				
				$thirdparty->poc = Input::get('poc');
				$thirdparty->phone = Input::get('phone');
				$thirdparty->phone_ext = Input::get('phone_ext');

				/*$thirdparty->nca_company_name = Input::get('nca_company_name');
				$thirdparty->nca_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('nca_activation_date')));
				$thirdparty->msa_company_name = Input::get('msa_company_name');
				$thirdparty->msa_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('msa_activation_date')));

				if(isset($_FILES['nca_document']['tmp_name']) && !empty($_FILES['nca_document']['tmp_name'])) {
					list($msg, $fileType) = $this->check_resume_validity("nca_document");
					if($msg){
						# error
						Session::flash('nca_document_error', $msg);
						return Redirect::route('add-third-party')->withInput();
					} else {
						# No error					
						list($msg, $target_file) = $this->upload_document($thirdparty, "nca_document");
						if($msg) {
							//error, delete candidate or set flash message
						} else {
							$tmp = explode("/", $target_file);
							$thirdparty->nca_document = end($tmp);
						}
					}
				}

				if(isset($_FILES['msa_document']['tmp_name']) && !empty($_FILES['msa_document']['tmp_name'])) {
					list($msg, $fileType) = $this->check_resume_validity("msa_document");
					if($msg){
						# error
						Session::flash('msa_document_error', $msg);
						return Redirect::route('add-third-party')->withInput();
					} else {
						# No error					
						list($msg, $target_file) = $this->upload_document($thirdparty, "msa_document");
						if($msg) {
							//error, delete candidate or set flash message
						} else {
							$tmp = explode("/", $target_file);
							$thirdparty->msa_document = end($tmp);
						}
					}
				}*/

				// Checking Authorised or not
				if($thirdparty->save()) {
					Session::flash('flashmessagetxt', 'Updated Successfully!!');					
					return Redirect::route('third-party-list');
				} else {
					return Redirect::route('edit-client')->withInput();
				}
			}
		}
	}


	/**
	 *
	 * deleteVendor() : Delete Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function deleteThirdparty($id) {
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			$thirdparty = Thirdparty::find($id);
			if(MailGroupMember::where('user_id', '=', $thirdparty->id)->where('group_id', '=', 3)->delete() && $thirdparty->delete()) {
				Session::flash('flashmessagetxt', 'Deleted Successfully!!');
				return Redirect::route('third-party-list');
			}
		}
	}

	/**
	 *
	 * deleteVendor() : Delete Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function unblockThirdparty($id) {
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			$thirdparty = Thirdparty::find($id);
			$thirdparty->status = 0;
			$thirdparty->save();
			Session::flash('flashmessagetxt', 'Unblocked Successfully!!');
			return Redirect::route('third-party-list');
		}
	}

	/**
	 *
	 * deleteVendor() : Delete Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function blockThirdparty($id) {
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			$thirdparty = Thirdparty::find($id);
			$thirdparty->status = 1;
			$thirdparty->save();
			Session::flash('flashmessagetxt', 'Blocked Successfully!!');
			return Redirect::route('third-party-list');
		}
	}
	
	
	private function check_resume_validity($doc_param) {
		$msg = false;
		$fileType = pathinfo($_FILES[$doc_param]["name"],PATHINFO_EXTENSION);

		// Check file size
		if ($_FILES[$doc_param]["size"] > $this->document_size) {
		    $msg = "Sorry, your file is too large.";
		}

		// Allow certain file formats
		if($fileType != "pdf") {
		    $msg = "Sorry, only pdf files are allowed.";
		}

		return array($msg, $fileType);
	}

	private function upload_document($thirdparty, $doc_param) {
		$msg = false;
		$fileType = pathinfo($_FILES[$doc_param]["name"],PATHINFO_EXTENSION);
		$target_dir = DOCROOT.$this->resume_target_dir.$thirdparty->id.'/';
		$target_file = $target_dir . uniqid() . "." . $fileType;

		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		// if everything is ok, try to upload file
	    if (!move_uploaded_file($_FILES[$doc_param]["tmp_name"], $target_file)) {
	        $msg = "Sorry, there was an error uploading your file.";
	    }

		return array($msg, $target_file);
	}

	private function getThirdPartyOrganisation($email) {
		$result = array();
		$domain = substr($email, strrpos($email, '@')+1);
		$org = ThirdpartyOrganisation::where('domain', '=', $domain)->get();
		if(!$org->isEmpty()) {
			$org = $org->first();
		} else {
			$org = new ThirdpartyOrganisation();
			$org->domain = $domain;
			$org->save();
		}
		if($org->nca_document && $org->msa_document) {
			$result[0] = 1;
		} else {
			$result[0] = 0;
		}
		$result[1] = $org->id;
		return $result;
	}

}
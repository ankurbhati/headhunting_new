<?php

class ThirdpartyOrganisationController extends HelperController {


	private $resume_target_dir = 'uploads/documents/';
	private $document_size = 5000000;
	
	/**
	 * Show the form for creating a new Thirdparty.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('ThirdpartyOrganisation.newThirdpartyOrganisation')->with(array('title' => 'Add Organisation'));
	}

	/**
	 * Show the form for creating a new Thirdparty.
	 *
	 * @return Response
	 */
	public function createThirdpartyOrganisation()
	{

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {

			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'domain' =>  'required|max:50',
							'name' => 'max:100'
					)
			);
			$id = Input::get('hidden_id');
			if($validate->fails()) {
				return Redirect::to("third-party-organisations/$id/0")
							   ->withErrors($validate)
							   ->withInput();
			} else {
				$domain = Input::get('domain');
				$org = ThirdpartyOrganisation::where('domain', '=', $domain)->get();
				if(!$org->isEmpty()) {
					Session::flash('domain_error',  $domain.' already added');
					return Redirect::to('add-third-party-organisation')->withInput();
				} else {
					$org = new ThirdpartyOrganisation();
					$org->name = Input::get('name');
					$org->domain = $domain;
					if($id == 1) { // fetch only NCA
						$org->nca_description = Input::get('nca_description');
						$org->nca_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('nca_activation_date')));
					} 
					if($id == 2) {
						$org->msa_description = Input::get('msa_description');
						$org->msa_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('msa_activation_date')));
					}
					
					// Checking Authorised or not
					if($org->save()) {

						if($id == 1 && isset($_FILES['nca_document']['tmp_name']) && !empty($_FILES['nca_document']['tmp_name'])) {
							list($msg, $fileType) = $this->check_resume_validity("nca_document");
							if($msg){
								# error
								Session::flash('nca_document_error', $msg);
								return Redirect::route('third-party-organisation-list', array('id'=>$id, 'edit'=>0))->withInput();
							} else {
								# No error					
								list($msg, $target_file) = $this->upload_document($org, "nca_document");
								if($msg) {
									//error, delete candidate or set flash message
								} else {
									$tmp = explode("/", $target_file);
									$org->nca_document = end($tmp);
								}
							}
						}

						if($id == 2 && isset($_FILES['msa_document']['tmp_name']) && !empty($_FILES['msa_document']['tmp_name'])) {
							list($msg, $fileType) = $this->check_resume_validity("msa_document");
							if($msg){
								# error
								Session::flash('msa_document_error', $msg);
								return Redirect::route('third-party-organisation-list', array('id'=>$id, 'edit'=>0))->withInput();
							} else {
								# No error					
								list($msg, $target_file) = $this->upload_document($org, "msa_document");
								if($msg) {
									//error, delete candidate or set flash message
								} else {
									$tmp = explode("/", $target_file);
									$org->msa_document = end($tmp);
								}
							}
						}

						$org->save();

						/* User activity */
						$description = Config::get('activity.org_add');
						$authUser = Auth::user();
						$formatted_description = sprintf(
							$description,
							'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
							'<a href="/view-third-party-organisation/'.$org->id.'">'.$org->domain.'</a>'
						);
						$this->saveActivity('11', $formatted_description);

						Session::flash('flashmessagetxt', 'Added Successfully!!');
						return Redirect::to("third-party-organisations/$id/0");
					} else {
						return Redirect::to("third-party-organisations/$id/0")->withInput();
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
	public function thirdpartyOrganisationList($id, $edit=0) {

		$q = ThirdpartyOrganisation::query();

		if($id == 1) { // fetch only NCA
			$q->where('nca_document', '!=', "");
		} else if ($id == 2) { // fetch only MSA
			$q->where('msa_document', '!=', "");
		} else if ($id == 3) { // fetch neither NCA nor MSA
			$q->where('nca_document', '=', null);
			$q->where('msa_document', '=', null);
		} else if ($id == 0) { // Show all

		}

		if($edit == 0) {
			$org = new ThirdpartyOrganisation();
		} else {
			$org = ThirdpartyOrganisation::where('id', '=', $edit)->get();
			if(!$org->isEmpty()) {
				$org = $org->first();
				//return View::make('ThirdpartyOrganisation.editThirdpartyOrganisation')
				//		   ->with(array('title' => 'Edit Organisation', 'org' => $org, 'category' => $category));
			}
		}
		
		if(!empty(Input::get('name'))) {
			$q->where('name', 'like', "%".Input::get('name')."%");
		}

		if(!empty(Input::get('domain'))){
			$q->where('domain', 'like', "%".Input::get('domain')."%");	
		}

		if(!empty(Input::get('nca_description'))) {
			$q->where('nca_description', 'like', "%".Input::get('nca_description')."%");	
		}
		if(!empty(Input::get('msa_description'))) {
			$q->where('msa_description', 'like', "%".Input::get('msa_description')."%");	
		}

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
		if(!empty(Input::get('csv_download_input'))) {
			if($id == 1) { // fetch only NCA
				$arrSelectFields = array('name', 'domain', 'nca_description', 'nca_activation_date', 'created_at');
				// passing the columns which I want from the result set. Useful when we have not selected required fields
	        	$arrColumns = array('name', 'domain', 'nca_description', 'nca_activation_date', 'created_at');	         
	        	// define the first row which will come as the first row in the csv
	        	$arrFirstRow = array('Name', 'Domain', 'Nca Description', 'Nca Activation Date', 'Added At');
			} else if ($id == 2) { // fetch only MSA
				$arrSelectFields = array('name', 'domain', 'msa_description', 'msa_activation_date', 'created_at');
				// passing the columns which I want from the result set. Useful when we have not selected required fields
	        	$arrColumns = array('name', 'domain', 'msa_description', 'msa_activation_date', 'created_at');
	        	// define the first row which will come as the first row in the csv
	        	$arrFirstRow = array('Name', 'Domain', 'Msa Description', 'Msa Activation Date', 'Added At');
			} else if ($id == 3) { // fetch neither NCA nor MSA
				$arrSelectFields = array('name', 'domain', 'created_at');
				// passing the columns which I want from the result set. Useful when we have not selected required fields
	        	$arrColumns = array('name', 'domain', 'created_at');	         
	        	// define the first row which will come as the first row in the csv
	        	$arrFirstRow = array('Name', 'Domain', 'Added At');
			} else if($id == 0) { // Show all
				$arrSelectFields = array('name', 'domain', 'nca_description', 'msa_description', 'nca_activation_date', 'msa_activation_date', 'created_at');
				// passing the columns which I want from the result set. Useful when we have not selected required fields
	        	$arrColumns = array('name', 'domain', 'nca_description', 'msa_description', 'nca_activation_date', 'msa_activation_date', 'created_at');	         
	        	// define the first row which will come as the first row in the csv
	        	$arrFirstRow = array('Name', 'Domain', 'Nca Description', 'Msa Description', 'Nca Activation Date', 'Msa Activation Date', 'Added At');
			}
			
	        $q->select($arrSelectFields);
	        $data = $q->get();

	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}

		$orgs = $q->paginate(100);

		return View::make('ThirdpartyOrganisation.thirdpartyOrganisationList')->with(array('title' => 'Organisation List', 'orgs' => $orgs, 'id' => $id, 'org' => $org));
	}


	/**
	 *
	 * viewVendor() : View Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function viewThirdpartyOrganisation($id, $category) {

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5)  || Auth::user()->hasRole(8) ) {

			$org = ThirdpartyOrganisation::where('id', '=', $id)->get();
			
			if(!$org->isEmpty()) {
				$org = $org->first();
				return View::make('ThirdpartyOrganisation.viewThirdpartyOrganisation')
						   ->with(array('title' => 'View Organisation', 'org' => $org, 'category'=>$category));
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
	public function editThirdpartyOrganisation($id, $category) {

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {

			$org = ThirdpartyOrganisation::where('id', '=', $id)->get();

			if(!$org->isEmpty()) {
				$org = $org->first();
				return View::make('ThirdpartyOrganisation.editThirdpartyOrganisation')
						   ->with(array('title' => 'Edit Organisation', 'org' => $org, 'category' => $category));
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
	public function updateThirdpartyOrganisation($id, $category)
	{
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			// Server Side Validation.
			$validate=Validator::make (
				Input::all(), array(
					'name' =>  'required',
					'domain' =>  'required'
				)
			);

			if($validate->fails()) {
				return Redirect::route('third-party-organisations', array('id' =>$category,'edit'=>$id))
								->withErrors($validate)
								->withInput();
			} else {

				$org = ThirdpartyOrganisation::find($id);

				/*$email = Input::get('email');
				if($email && $email != $thirdparty->email){
					if(!Thirdparty::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-third-party', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$thirdparty->email = $email;
				}*/
				if($category == 1) {
					$org->nca_description = Input::get('nca_description');	
					$org->nca_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('nca_activation_date')));
				}
				if($category == 2) {
					$org->msa_description = Input::get('msa_description');
					$org->msa_activation_date = date("Y-m-d H:i:s", strtotime(Input::get('msa_activation_date')));
				}
				$org->name = Input::get('name');
				Log::info('ffffffffffffffffffff');
				if($category == 1 && isset($_FILES['nca_document']['tmp_name']) && !empty($_FILES['nca_document']['tmp_name'])) {
					Log::info('dddddddddddddddddd');
					list($msg, $fileType) = $this->check_resume_validity("nca_document");
					Log::info('AAAAAAAAAAAAAAAAAAAAA');
					if($msg){
						Log::info('BBBBBBBBBBBBBBBBB');
						# error
						Session::flash('nca_document_error', $msg);
						return Redirect::route('third-party-organisations', array('id' =>$category,'edit'=>$id))->withInput();
					} else {
						Log::info('cccccccccccccccccccccccc');
						# No error					
						list($msg, $target_file) = $this->upload_document($org, "nca_document");
						if($msg) {
							Log::info('ddddddddddd');
							//error, delete candidate or set flash message
						} else {
							$tmp = explode("/", $target_file);
							$org->nca_document = end($tmp);
						}
					}
				}

				if($category == 2 && isset($_FILES['msa_document']['tmp_name']) && !empty($_FILES['msa_document']['tmp_name'])) {
					list($msg, $fileType) = $this->check_resume_validity("msa_document");
					if($msg){
						# error
						Session::flash('msa_document_error', $msg);
						return Redirect::route('third-party-organisations', array('id' =>$category,'edit'=>$id))->withInput();
					} else {
						# No error					
						list($msg, $target_file) = $this->upload_document($org, "msa_document");
						if($msg) {
							//error, delete candidate or set flash message
						} else {
							$tmp = explode("/", $target_file);
							$org->msa_document = end($tmp);
						}
					}
				}

				// Checking Authorised or not
				if($org->save()) {
					Session::flash('flashmessagetxt', 'Updated Successfully!!');					
					return Redirect::route('third-party-organisation-list', array('id'=>$category, 'edit'=>0));
				} else {
					return Redirect::route('third-party-organisation-list', array('id' =>$category,'edit'=>$id));
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
	public function deleteThirdpartyOrganisation($id, $category) {
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) ) {
			$org = ThirdpartyOrganisation::find($id);
			if($org->delete()) {
				Session::flash('flashmessagetxt', 'Deleted Successfully!!');
				return Redirect::route('third-party-organisation-list', array('id'=>$category, 'edit'=>0));
			}
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
		if(! in_array(strtolower($fileType), ["pdf", "jpg", "jpeg"]) ) {
		    $msg = "Sorry, only pdf, jpg and jpeg files are allowed.";
		}

		return array($msg, $fileType);
	}

	private function upload_document($org, $doc_param) {
		$msg = false;
		$fileType = pathinfo($_FILES[$doc_param]["name"],PATHINFO_EXTENSION);
		$target_dir = DOCROOT.$this->resume_target_dir.$org->id.'/';
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

}

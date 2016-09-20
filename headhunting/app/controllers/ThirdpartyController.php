<?php

class ThirdpartyController extends \BaseController {


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
	 * Show the form for creating a new Thirdparty.
	 *
	 * @return Response
	 */
	public function createThirdparty()
	{

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) ) {
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
							'email' =>  'required|max:50|email|unique:sources,email',
							'poc' => 'max:50',
							'phone' => 'max:14',
							'document_type' => 'max:1'
					)
			);
			
			if($validate->fails()) {

				return Redirect::to('add-third-party')
							   ->withErrors($validate)
							   ->withInput();
			} else {
				$thirdparty = new Thirdparty();
				$thirdparty->poc = Input::get('poc');
				$thirdparty->email = Input::get('email');
				$thirdparty->phone = Input::get('phone');
				$thirdparty->document_type = Input::get('document_type');
				$thirdparty->created_by = Auth::user()->id;
				
				// Checking Authorised or not
				if($thirdparty->save()) {
					$mail_group = new MailGroupMember();
					$mail_group->group_id = 3;
					$mail_group->user_id = $thirdparty->id;
					$mail_group->save();
					if($thirdparty->document_type != 0 && isset($_FILES['upload_document']['tmp_name']) && !empty($_FILES['upload_document']['tmp_name'])) {
						list($msg, $fileType) = $this->check_resume_validity();
						if($msg){
							# error
							Session::flash('upload_document_error', $msg);
							return Redirect::route('add-third-party')->withInput();
						} else {
							# No error					
							list($msg, $target_file) = $this->upload_document($thirdparty);
							if($msg) {
								//error, delete candidate or set flash message
							} else {
								$tmp = explode("/", $target_file);
								$thirdparty->document_url = end($tmp);
							}
						}
					}
					$thirdparty->save();
					return Redirect::to('third-parties');
				} else {
					return Redirect::to('add-third-party')->withInput();
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
		$thirdparties = Thirdparty::all();
		return View::make('Thirdparty.thirdpartyList')->with(array('title' => 'Third Party List', 'thirdparties' => $thirdparties));
	}


	/**
	 *
	 * viewVendor() : View Vendor
	 *
	 * @return Object : View
	 *
	 */
	public function viewThirdparty($id) {

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) ) {

			$thirdparty = Thirdparty::with(array('createdby'))->where('id', '=', $id)->get();

			if(!$thirdparty->isEmpty()) {
				$thirdparty = $thirdparty->first();
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

		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) ) {

			$thirdparty = Thirdparty::with(array('createdby'))->where('id', '=', $id)->get();

			if(!$thirdparty->isEmpty()) {
				$thirdparty = $thirdparty->first();
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
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) ) {
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
					'poc' => 'max:50',
					'phone' => 'max:14',
					'document_type' => 'max:1'
				)
			);
			if($validate->fails()) {

				return Redirect::route('edit-third-party', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {

				$thirdparty = Thirdparty::find($id);

				$email = Input::get('email');
				if($email && $email != $thirdparty->email){
					if(!Thirdparty::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-third-party', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$thirdparty->email = $email;
				}
				
				$thirdparty->poc = Input::get('poc');
				$thirdparty->phone = Input::get('phone');
				$thirdparty->document_type = Input::get('document_type');
				$thirdparty->created_by = Auth::user()->id;
				if($thirdparty->document_type != 0 && isset($_FILES['upload_document']['tmp_name']) && !empty($_FILES['upload_document']['tmp_name'])) {
					list($msg, $fileType) = $this->check_resume_validity();
					if($msg){
						# error
						Session::flash('upload_document_error', $msg);
						return Redirect::route('add-third-party')->withInput();
					} else {
						# No error					
						list($msg, $target_file) = $this->upload_document($thirdparty);
						if($msg) {
							//error, delete candidate or set flash message
						} else {
							$tmp = explode("/", $target_file);
							$thirdparty->document_url = end($tmp);
						}
					}
				}

				// Checking Authorised or not
				if($thirdparty->save()) {					
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
		if( Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) ) {
			$thirdparty = Thirdparty::find($id);
			if(MailGroupMember::where('user_id', '=', $thirdparty->id)->where('group_id', '=', 3)->delete() && $thirdparty->delete()) {
				return Redirect::route('third-party-list');
			}
		}
	}
	
	
	private function check_resume_validity(){
		$msg = false;
		$fileType = pathinfo($_FILES["upload_document"]["name"],PATHINFO_EXTENSION);

		// Check file size
		if ($_FILES["upload_document"]["size"] > $this->document_size) {
		    $msg = "Sorry, your file is too large.";
		}

		// Allow certain file formats
		if($fileType != "pdf") {
		    $msg = "Sorry, only pdf files are allowed.";
		}

		return array($msg, $fileType);
	}

	private function upload_document($thirdparty) {
		$msg = false;
		$fileType = pathinfo($_FILES["upload_document"]["name"],PATHINFO_EXTENSION);
		$target_dir = DOCROOT.$this->resume_target_dir.$thirdparty->id.'/';
		$target_file = $target_dir . uniqid() . "." . $fileType;

		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		// if everything is ok, try to upload file
	    if (!move_uploaded_file($_FILES["upload_document"]["tmp_name"], $target_file)) {
	        $msg = "Sorry, there was an error uploading your file.";
	    }

		return array($msg, $target_file);
	}

}

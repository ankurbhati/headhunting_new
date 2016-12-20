<?php

class CandidateController extends HelperController {


	private $resume_target_dir = 'uploads/resumes/';
	private $resume_size = 5000000;


	/**
	 * Show the form for creating a new Vendor.
	 *
	 * @return Response
	 */
	public function create()
	{
		$country = Country::all()->lists('country', 'id');
		$visa = Visa::all()->lists('title', 'id');
		//$vendor = Vendor::all()->lists('vendor_domain', 'id');
		$work_states = WorkStates::all()->lists('title', 'id');

		return View::make('Candidate.newCandidate')->with(array('title' => 'Add Candidate', 'country' => $country, 'visa' => $visa//, 'vendor' => $vendor
			, 'work_state' => $work_states));
	}

	/**
	 * Show the form for creating a new Vendor.
	 *
	 * @return Response
	 */
	public function createCandidate()
	{

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)|| Auth::user()->hasRole(4)|| Auth::user()->hasRole(5) || Auth::user()->hasRole(8)) {
			
			// Server Side Validation.
			$validate=Validator::make (
				Input::all(), array(
						'email' => 'required|email|max:50|email|unique:candidates,email',
						'first_name' => 'max:50',
						'last_name' => 'max:50',
						'phone' => 'max:14',
						'city' => 'max:100',
						'country_id' => 'max:9',
						'state_id' => 'max:9',
						'designation' => 'max:255',
						'rate' => 'numeric',
						'third_party_id' => 'email|max:247',
						'visa_id' => 'max:1'
				)
			);

			if($validate->fails()) {

				return Redirect::to('add-candidate')
							   ->withErrors($validate)
							   ->withInput();
			} else {
				$fileType = false;
				//resume
				if(isset($_FILES['resume']['tmp_name']) && !empty($_FILES['resume']['tmp_name'])) {

					list($msg, $fileType) = $this->check_resume_validity();

					if($msg){
						# error
						Session::flash('resume_error', $msg);
						return Redirect::route('add-candidate')->withInput();
					} else {
						# No error
						$resume_upload = true;
					}
				}

				$candidate = new Candidate();
				$candidate->email = trim(Input::get('email'));
				$candidate->first_name = Input::get('first_name');
				$candidate->last_name = Input::get('last_name');
				$candidate->phone = Input::get('phone');
				$candidate->country_id = Input::get('country_id');
				$candidate->state_id = Input::get('state_id');
				$candidate->designation = Input::get('designation');
				$candidate->visa_id = Input::get('visa_id');
				$candidate->work_state_id = Input::get('work_state_id');
				$candidate->added_by = Auth::user()->id;

				$city = Input::get('city');

				if(isset($city) && !empty($city)) {
					$city_record = City::where('name', 'like', $city)->first();

					if($city_record) {
						$candidate->city_id = $city_record->id;
					} else {

						$city_obj = new City();
						$city_obj->name = $city;
						$city_obj->save();
						$candidate->city_id = $city_obj->id;
					}

				}

				if($candidate->work_state_id == 3) {
					$thirdparty = Thirdparty::where('email', '=', trim(Input::get('third_party_id')))->get();
					if(!$thirdparty->isEmpty()) {
						$candidate->source_id = $thirdparty->first()->id;
					} else {
						$thirdparty = new Thirdparty();
						$thirdparty->email = trim(Input::get('third_party_id'));
						$thirdparty->save();
						$mail_group = new MailGroupMember();
						$mail_group->group_id = 3;
						$mail_group->user_id = $thirdparty->id;
						$mail_group->save();
						$thirdpartyuser = new Thirdpartyuser();
						$thirdpartyuser->user_id = Auth::user()->id;
						$thirdpartyuser->source_id = $thirdparty->id;
						$thirdpartyuser->save();
						$candidate->source_id = $thirdparty->id;
					}
				}


				// Checking Authorised or not
				try {
					if($candidate->save()) {
						Session::flash('flashmessagetxt', 'Candidate Added Successfully!!');
                        $rate = Input::get('rate');
						if(isset($rate) && !empty($rate)){
							$candidate_rate = new CandidateRate();
							$candidate_rate->value = $rate;
							$candidate_rate->candidate_id = $candidate->id;
							$candidate_rate->save();	
						}
						$candidate_resume = new CandidateResume();
						$candidate_resume->candidate_id = $candidate->id;
						$candidate_resume->designation = $candidate->designation;
						$candidate_resume->visa = $candidate->visa_id?Visa::find($candidate->visa_id)->title:"";
						$candidate_resume->region = $candidate->state_id?State::find($candidate->state_id)->state:"";
						
						//resume
						if($fileType) {
							list($msg, $target_file) = $this->upload_resume($candidate);
							if($msg) {
								//error, delete candidate or set flash message
							} else {
								if ($fileType == "doc") {
									$candidate_resume->resume = $this->read_doc($target_file);
								} else if($fileType == "docx") {
									$candidate_resume->resume = $this->read_docx($target_file);
								} else {
									$candidate_resume->resume = $this->read_pdf($target_file);
								}
								$tmp = explode("/", $target_file);
								$candidate_resume->resume_path = end($tmp);
							}
						}
						if(!$candidate_resume->save()){
							//error, delete candidate or set flash message
						};
						$candidate_resume->addToIndex();
						return Redirect::route('candidate-list');
					} else {

						return Redirect::route('add-candidate')->withInput();
					}

				} catch(Exception $e) {
					print $e->getMessage();exit;
					return Redirect::route('add-candidate')->withInput();
				}

			}

		}

	}


	/**
	 *
	 * candidateList() : Candidate List
	 *
	 * @return Object : View
	 *
	 */
	public function candidateList() {
		
		$visa = Visa::all()->lists('title', 'id');

		$y = Candidate::query();

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('added_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(added_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');
		
		$q = Candidate::query();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(!empty(Input::get('email'))) {
				$q->where('email', 'like', "%".Input::get('email')."%");
			} 
			if(!empty(Input::get('first_name'))){
				$q->where('first_name', 'like', "%".Input::get('first_name')."%");	
			}
			if(!empty(Input::get('last_name'))) {
				$q->where('last_name', 'like', "%".Input::get('last_name')."%");	
			}
			if(!empty(Input::get('visa_id'))) {
				$q->where('visa_id', '=', Input::get('visa_id'));	
			}
			if(!empty(Input::get('added_by'))) {
				$q->where('added_by', '=', Input::get('added_by'));	
			}
		}

		$candidates = $q->leftJoin('candidate_resumes', function($join) {
	      $join->on('candidates.id', '=', 'candidate_resumes.candidate_id');
	    })->leftJoin('users', function($join){
			$join->on('added_by', '=', 'users.id');
		})
	    ->select('candidates.*', 'candidate_resumes.resume', 'candidate_resumes.resume_path', DB::raw('CONCAT(users.first_name, " ", users.last_name) as added_by_name'))->paginate(100);
		return View::make('Candidate.candidateList')->with(array('title' => 'Candidates List', 'candidates' => $candidates, 'visa'=>$visa, 'addedBy' => $addedByList));
	}


	/**
	 *
	 * viewCandidate() : View Candidate
	 *
	 * @return Object : View
	 *
	 */
	public function viewCandidate($id, $jobId = 0, $searchingText="") {

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)|| Auth::user()->hasRole(4)|| Auth::user()->hasRole(5) || Auth::user()->hasRole(8)) {

			$candidate = Candidate::with(array('visa', 'createdby', 'city', 'state', 'country', 'workstate', 'candidaterate'))->where('id', '=', $id)->get();

			if(!$candidate->isEmpty()) {
				$candidate = $candidate->first();
				$resume = CandidateResume::where('candidate_id', $candidate->id)->first();
				$rate = CandidateRate::where('candidate_id', $candidate->id)->first();
				return View::make('Candidate.viewCandidate')
						   ->with(array('title' => 'View Candidate', 'candidate' => $candidate, 'resume' => $resume, 'jobId' => $jobId, 'rate' => $rate, 'searchingText' => urldecode($searchingText)));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}


	/**
	 *
	 * editCandidate() : Edit Candidate
	 *
	 * @return Object : View
	 *
	 */
	public function editCandidate($id) {

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)|| Auth::user()->hasRole(4)|| Auth::user()->hasRole(5) || Auth::user()->hasRole(8)) {

			$country = Country::all()->lists('country', 'id');

			$candidate = Candidate::with(array('visa', 'createdby', 'city', 'state', 'country', 'workstate'))->where('id', '=', $id)->get();
			$visa = Visa::all()->lists('title', 'id');
			$work_states = WorkStates::all()->lists('title', 'id');

			if(!$candidate->isEmpty()) {
				$candidate = $candidate->first();
				$resume = CandidateResume::where('candidate_id', $candidate->id)->first();
				$rate = CandidateRate::where('candidate_id', $candidate->id)->first();
				return View::make('Candidate.editCandidate')
						   ->with(
						   		array('title' => 'Edit Candidate', 'candidate' => $candidate, 'resume' => $resume, 'country' => $country, 'visa' => $visa,
						   			'work_state' => $work_states
						   			, 'rate' => $rate
				));
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
	public function updateCandidate($id)
	{
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)|| Auth::user()->hasRole(4)|| Auth::user()->hasRole(5) || Auth::user()->hasRole(8)) {

			// Server Side Validation.
			$validate=Validator::make (
				Input::all(), array(
						'email' => 'required|email|max:50|email',
						'first_name' => 'max:50',
						'last_name' => 'max:50',
						'phone' => 'max:14',
						'city' => 'max:100',
						'country_id' => 'max:9',
						'state_id' => 'max:9',
						'designation' => 'max:255',
						'rate' => 'numeric',
						'third_party_id' => 'email|max:247',
						'visa_id' => 'max:1'
				)
			);
			if($validate->fails()) {

				return Redirect::route('edit-candidate', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {

				$fileType = false;
				//resume
				if(isset($_FILES['resume']['tmp_name']) && !empty($_FILES['resume']['tmp_name'])) {
					list($msg, $fileType) = $this->check_resume_validity();
					if($msg){
						# error
						Session::flash('resume_error', $msg);
						return Redirect::route('edit-candidate', array('id' => $id))->withInput();
					} else {
						# No error
						$resume_upload = true;
					}
				}

				$candidate = Candidate::find($id);

				$email = trim(Input::get('email'));
				if($email && $email != $candidate->email){
					if(!Candidate::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-candidate', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
					$candidate->email = $email;
				}

				$candidate->first_name = Input::get('first_name');
				$candidate->last_name = Input::get('last_name');
				$candidate->phone = Input::get('phone');
				$candidate->country_id = Input::get('country_id');
				$candidate->state_id = Input::get('state_id');
				$candidate->designation = Input::get('designation');
				$candidate->visa_id = Input::get('visa_id');
				$candidate->work_state_id = Input::get('work_state_id');
				$candidate->added_by = Auth::user()->id;

				$city = Input::get('city');

				if(isset($city) && !empty($city)) {
					$city_record = City::where('name', 'like', $city)->first();

					if($city_record) {
						$candidate->city_id = $city_record->id;
					} else {

						$city_obj = new City();
						$city_obj->name = $city;
						$city_obj->save();
						$candidate->city_id = $city_obj->id;
					}

				}

				if($candidate->work_state_id == 3) {
					$thirdparty = Thirdparty::where('email', '=', trim(Input::get('third_party_id')))->get();
					if(!$thirdparty->isEmpty()) {
						$candidate->source_id = $thirdparty->first()->id;
					} else {
						$thirdparty = new Thirdparty();
						$thirdparty->email = trim(Input::get('third_party_id'));
						$thirdparty->save();
						$mail_group = new MailGroupMember();
						$mail_group->group_id = 3;
						$mail_group->user_id = $thirdparty->id;
						$mail_group->save();
						$thirdpartyuser = new Thirdpartyuser();
						$thirdpartyuser->user_id = Auth::user()->id;
						$thirdpartyuser->source_id = $thirdparty->id;
						$thirdpartyuser->save();
						$candidate->source_id = $thirdparty->id;
					}
				}
				
				// Checking Authorised or not
				try {
					if($candidate->save()) {
						Session::flash('flashmessagetxt', 'Updated Successfully!!');
						$rate = Input::get('rate');
						if(isset($rate) && !empty($rate)) {
							$candidate_rate = CandidateRate::where('candidate_id', $candidate->id)->first();
							if(!$candidate_rate) {
								$candidate_rate = new CandidateRate();
							}
							$candidate_rate->value = $rate;
							$candidate_rate->candidate_id = $candidate->id;
							$candidate_rate->save();	
						}
						$resume_obj = '';
						$candidate_resume = CandidateResume::where('candidate_id', '=', $candidate->id)->first();
						if(!$candidate_resume) {
							$resume_obj = "new";
							$candidate_resume = new CandidateResume();
						}
						$candidate_resume->candidate_id = $candidate->id;
						$candidate_resume->designation = $candidate->designation;
						$candidate_resume->visa = $candidate->visa_id?Visa::find($candidate->visa_id)->title:"";
						$candidate_resume->region = $candidate->state_id?State::find($candidate->state_id)->state:"";
						
						//resume
						if($fileType) {
							list($msg, $target_file) = $this->upload_resume($candidate);
							if($msg) {
								//error, delete candidate or set flash message
							} else {
								if ($fileType == "doc") {
									$candidate_resume->resume = $this->read_doc($target_file);
								} else if($fileType == "docx") {
									$candidate_resume->resume = $this->read_docx($target_file);
								} else {
									$candidate_resume->resume = $this->read_pdf($target_file);
								}
								$tmp = explode("/", $target_file);
								$candidate_resume->resume_path = end($tmp);
							}
						}
						if(!$candidate_resume->save()){
							//error, delete candidate or set flash message
						};
						($resume_obj == "new") ? $candidate_resume->addToIndex() : $candidate_resume->reindex();

						return Redirect::route('candidate-list');
					} else {

						return Redirect::route('edit-candidate', array('id' => $id))->withInput();
					}

				} catch(Exception $e) {
					print $e->getMessage();exit;

					return Redirect::route('edit-candidate', array('id' => $id))->withInput();
				}
			}
		}
	}


	/**
	 *
	 * deleteCandidate() : Delete Candidate
	 *
	 * @return Object : View
	 *
	 */
	public function deleteCandidate($id) {
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)|| Auth::user()->hasRole(4)|| Auth::user()->hasRole(5) || Auth::user()->hasRole(8)) {
			$candidate = Candidate::find($id);
			$resume = CandidateResume::where('candidate_id', $candidate->id)->first();
			if($resume && $resume->removeFromIndex() && $resume->delete() ){

			}  
			if($candidate->delete()) {
				Session::flash('flashmessagetxt', 'Deleted Successfully!!');
				return Redirect::route('candidate-list');
			}
		}
	}


	public function read_doc($file){
		$fileHandle = fopen($file, "r");
        $line = @fread($fileHandle, filesize($file));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
       
        foreach($lines as $thisline) {
            $pos = strpos($thisline, chr(0x00));
            #if (($pos !== FALSE)||(strlen($thisline)==0)) {
            if (strlen($thisline)==0) {
            } else {
            	$outtext .= htmlspecialchars($thisline."<br />", ENT_QUOTES);
            }
        }

        return htmlspecialchars_decode($outtext);
        //return $striped_content(htmlspecialchars_decode($outtext));
	}

	public function read_docx($file){

        $striped_content = '';
        $content = '';

        $zip = zip_open($file);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;


            //$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            $content = $content.zip_entry_read($zip_entry, zip_entry_filesize($zip_entry))."<br />";

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);

        //$content = str_replace('</w:r></w:p>', "\r\n", $content);
        $content = str_replace('</w:r></w:p>', "\r<br />", $content);
        #$striped_content = strip_tags($content);

		return $content;
        #return $striped_content;
    }


    public function read_pdf($file) {
    	$parser = new \Smalot\PdfParser\Parser();
		$pdf    = $parser->parseFile($file);
		$text = $pdf->getText();
		return $text;
    }


	public function check_resume_validity(){
		$msg = false;
		$fileType = strtolower(pathinfo($_FILES["resume"]["name"],PATHINFO_EXTENSION));

		// Check file size
		if ($_FILES["resume"]["size"] > $this->resume_size) {
		    $msg = "Sorry, your file is too large.";
		}

		// Allow certain file formats
		if($fileType != "doc" && $fileType != "docx" && $fileType != "pdf") {
		    $msg = "Sorry, only doc, docx and pdf files are allowed.";
		}

		return array($msg, $fileType);
	}

	public function upload_resume($candidate) {
		$msg = false;
		$fileType = pathinfo($_FILES["resume"]["name"],PATHINFO_EXTENSION);
		$target_dir = DOCROOT.$this->resume_target_dir.$candidate->id.'/';
		$target_file = $target_dir . uniqid() . "." . $fileType;

		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		// if everything is ok, try to upload file
	    if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
	        $msg = "Sorry, there was an error uploading your file.";
	    }

		return array($msg, $target_file);
	}

	/**
	 * Submits for Job Post.
	 *
	 * @return Response
	 */
	public function jobSubmittel($jobId, $userId) {
		if(JobPostAssignment::where('job_post_id', '=', $jobId)
		                    ->where('assigned_to_id', '=', Auth::user()->id)
												->exists() &&
			 !CandidateApplication::where('candidate_id', '=', $userId)
			 										->where('job_post_id', '=', $jobId)->exists()) {
			$candidateApplication = new CandidateApplication();
			$candidateApplication->setConnection('master');
			$candidateApplication->candidate_id = $userId;
			$candidateApplication->job_post_id = $jobId;
			$candidateApplication->submitted_by = Auth::user()->id;
			$candidateApplication->status = 1;
			$candidateApplication->created_at = date('Y-m-d H:i:s');
			if($candidateApplication->save()) {
				Session::flash('flashmessagetxt', 'Submitted Successfully!!');
				return Redirect::route('list-submittel', array('id' => $jobId));
			} else {
				return Redirect::route('dashboard-view');
			}
		} else {
			return Redirect::route('dashboard-view');
		}
	}

	/**
	 *
	 * getThirdParty() : get getThirdParty
	 *
	 * @return Object : JSON
	 *
	 */
	public function getThirdParty() {
		$thirdparty = Thirdparty::all();
		return $this->sendJsonResponseOnly($thirdparty);
	}

	/**
	 * validating candidate while creating.
	 *
	 * @return Response
	 */
	public function validateCandidate()
	{

		$response = array();
    	$response['error'] = false;
		// Server Side Validation.
		$validate=Validator::make (
			Input::all(), array(
					'email' => 'required|email|max:50|email|unique:candidates,email',
			)
		);

		if($validate->fails()) {
			$response['error'] = true;
    		$response['message'] = 'Candidate Already Exists';
		} else {
			$response['message'] = 'success';
		}
		return $this->sendJsonResponseOnly($response);
	}
}

<?php
/**
 * SaleController.php
 *
 * This file contatins controller class to provide APIs for Users
 *
 * @category   Controller
 * @package    Sale Management
 * @version    SVN: <svn_id>
 * @since      29th May 2014
 *
 */

/**
 * Contrller class will be responsible for All Sale management Related Actions
 *
 * @category   Controller
 * @package    Sale Management
 *
 */
class SaleController extends HelperController {

	/**
	 *
	 * postRequirement() : postRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function postRequirementView() {
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) {
			$jobPost = new JobPost();
			$country = Country::all();

			$count = array();
			foreach( $country as $key => $value) {
				$count[$value->id] = $value->country;
			}
			if(Auth::user()->hasRole(1)){
				$clients = Client::where('status', '=', 1)
								 ->orderBy('updated_at', 'DESC')
								 ->get();
			} else {
				$clients = Client::where('created_by', '=', Auth::user()->id)
								 ->where('status', '=', 1)
								 ->orderBy('updated_at', 'DESC')
								 ->get();	
			}
			$client = array();
			foreach( $clients as $key => $value) {
				$client[$value->id] = $value->first_name."-".$value->email;
			}
			return View::make('sales.postRequirement')->with(array('title' => 'Post Requirement - Headhunting', 'country' => $count, 'jobPost' => $jobPost, 'client' => $client));
		} else {
			return Redirect::to('dashboard');
		}
	}

	/**
	 *
	 * assignRequirement() : assignRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function assignRequirement($id, $assignedTo = "") {
		$jobPostAssignment = new JobPostAssignment();
		$jobPostAssignment->setConnection("master");
		$jobPostAssignment->job_post_id = $id;
		$jobPostAssignment->assigned_by_id = Auth::user()->id;
		$jobPostAssignment->assigned_to_id = ($assignedTo == "")?$jobPostAssignment->assigned_by_id:$assignedTo;
		$jobPostAssignment->status = 2;
		if($jobPostAssignment->save()) {

			/* User activity */
			$description = Config::get('activity.job_post_assign');
			$authUser = Auth::user();
			$assigned_user = User::find($jobPostAssignment->assigned_to_id);
			$job_post = JobPost::find($jobPostAssignment->job_post_id);

			if(Auth::user()->id == $jobPostAssignment->assigned_to_id) {
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
					'self'
				);
			} else {
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
					'<a href="/view-employee/'.$assigned_user->id.'">'.$assigned_user->first_name." ".$assigned_user->last_name.'</a>'
				);
			}
			
			$this->saveActivity('7', $formatted_description);
			$to_notify_user = array($job_post->created_by);
			/* Save Notification */
			$description = Config::get('notification.job_post_assignment');
			if(Auth::user()->id == $jobPostAssignment->assigned_to_id) {
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
					'self'
				);
			} else {
				array_push($to_notify_user, $jobPostAssignment->assigned_to_id);
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
					'<a href="/view-employee/'.$assigned_user->id.'">'.$assigned_user->first_name." ".$assigned_user->last_name.'</a>'
				);
			}
			$lead = $this->getTeamLeadForUser($job_post->created_by);
			if(!empty($lead) && Auth::user()->id != $lead->id) {
				array_push($to_notify_user, $lead->id);
			}
			$subject_description = sprintf(
				$description,
				$authUser->first_name." ".$authUser->last_name,
				$job_post->title,
				$assigned_user->first_name." ".$assigned_user->last_name
			);
  			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);

			Session::flash('flashmessagetxt', 'Assigned Successfully!!'); 
			if(Auth::user()->id == $jobPostAssignment->assigned_to_id) {
				return Redirect::route('assigned-requirement', array($jobPostAssignment->assigned_to_id));
			} else {
				return Redirect::route('list-requirement');
			}
		} else {
			return Redirect::to('dashboard')->with(array("message" => "error"));
		}
	}

	/**
	 *
	 * deleteRequirement() : deleteRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function deleteRequirement($id) {
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) {
			$jobPost = JobPost::find($id)->delete();
			Session::flash('flashmessagetxt', 'Deleted Successfully!!'); 
		}
		return Redirect::route('list-requirement');
	}

	/**
	 *
	 * closeRequirement() : closeRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function closeRequirement($id) {

		if( $_SERVER['REQUEST_METHOD'] == 'GET' && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1))) {
			$feedbacks = array(
				'Rejected By Sales Manager',
				'Submitted To Prime Vendor',
				'Submitted To End Client',
				'Interview Requested',
				'Selected',
				'Rejected For All States'
			);
			return View::make('sales.closeRequirement')->with(array('title' => 'Close Requirement - Headhunting', 'feedbacks'=>$feedbacks));
		} else if($_SERVER['REQUEST_METHOD'] == 'POST' && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1))) {
			$jobPost = JobPost::find($id);
			$jobPost->status = 3;
			$jobPost->feedback = Input::get('feedback');
			$jobPost->save();
			/* Save Notification */
			$description = Config::get('notification.job_post_close');
			$authUser = Auth::user();
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
			);
			$subject_description = sprintf(
				$description,
				$authUser->first_name." ".$authUser->last_name,
				$jobPost->title
			);
			$to_notify_user = array($jobPost->created_by);
			$lead = $this->getTeamLeadForUser($jobPost->created_by);
			if(!empty($lead) && Auth::user()->id != $lead->id) {
				array_push($to_notify_user, $lead->id);
			}
			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
			Session::flash('flashmessagetxt', 'Job Closed Successfully!!'); 
		}
		return Redirect::route('list-requirement');
	}

	/**
	 *
	 * approveRequirement() : approveRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function approveRequirement($id) {
		if($_SERVER['REQUEST_METHOD'] == 'GET' && (Auth::user()->hasRole(3) || Auth::user()->hasRole(1)) ) {
			$jobPost = JobPost::find($id);
			$jobPost->status = 2;
			$jobPost->feedback = Input::get('feedback');
			$jobPost->save();

			/* Save Notification */
			$description = Config::get('notification.job_post_approval');
			$authUser = Auth::user();
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
			);
			$subject_description = sprintf(
				$description,
				$authUser->first_name." ".$authUser->last_name,
				$jobPost->title
			);
			$to_notify_user = array($jobPost->created_by);
			$lead = $this->getTeamLeadForUser($jobPost->created_by);
			if(!empty($lead) && Auth::user()->id != $lead->id) {
				array_push($to_notify_user, $lead->id);
			}
			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
			Session::flash('flashmessagetxt', 'Job Approved Successfully!!'); 
		}
		return Redirect::route('list-requirement');
	}

	/**
	 *
	 * rejectRequirement() : rejectRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function rejectRequirement($id) {
		if($_SERVER['REQUEST_METHOD'] == 'GET' && (Auth::user()->hasRole(3) || Auth::user()->hasRole(1)) ) {
			$jobPost = JobPost::find($id);
			$jobPost->status = 4;
			$jobPost->feedback = Input::get('feedback');
			$jobPost->save();

			/* Save Notification */
			$description = Config::get('notification.job_post_rejection');
			$authUser = Auth::user();
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
			);
			$subject_description = sprintf(
				$description,
				$authUser->first_name." ".$authUser->last_name,
				$jobPost->title
			);
			$to_notify_user = array($jobPost->created_by);
			$lead = $this->getTeamLeadForUser($jobPost->created_by);
			if(!empty($lead) && Auth::user()->id != $lead->id) {
				array_push($to_notify_user, $lead->id);
			}
			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
			Session::flash('flashmessagetxt', 'Job Rejected Successfully!!'); 
		}
		return Redirect::route('list-requirement');
	}

	/**
	 *
	 * reopenRequirement() : reopenRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function reopenRequirement($id) {
		if(Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)) {
			$jobPost = JobPost::find($id);
			$jobPost->status = 1;
			$jobPost->save();
			
			/* Save Notification */
			$description = Config::get('notification.job_post_reopen');
			$authUser = Auth::user();
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
			);
			$subject_description = sprintf(
				$description,
				$authUser->first_name." ".$authUser->last_name,
				$jobPost->title
			);
			$to_notify_user = array($jobPost->created_by);
			$lead = $this->getTeamLeadForUser($jobPost->created_by);
			if(!empty($lead) && Auth::user()->id != $lead->id) {
				array_push($to_notify_user, $lead->id);
			}
  			if(Auth::user()->hasRole(1) ) {
				$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
			} else {
				$this->saveNotification($formatted_description, $to_notify_user, '', True, $subject_description);
			}
			Session::flash('flashmessagetxt', 'Job Reopened Successfully!!'); 
		}
		return Redirect::route('list-requirement');
	}

	/**
	 *
	 * viewRequirement() : viewRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function viewRequirement($id) {
		$feedbacks = array(
			'Rejected By Sales Manager',
			'Submitted To Prime Vendor',
			'Submitted To End Client',
			'Interview Requested',
			'Selected',
			'Rejected For All States'
		);
		$jobPost = JobPost::with(array('country', 'state', 'client', 'city', 'comments'))->find($id);
		return View::make('sales.viewRequirement')->with(array('title' => 'View Requirement - Headhunting', 'feedbacks'=>$feedbacks,'jobPost' => $jobPost,));
	}

	/**
	 *
	 * listRequirement() : listRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function listRequirement($id=0) {

		$job_post_creation_filter = '';
		$q = JobPost::query();
		$usersAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->where('status', '=', 1)->whereHas('userRoles', function($q){
				    $q->where('role_id', '<=', 3);
				})->get();
		$users = array('-1'=> 'Please select');
		foreach( $usersAddedBy as $key => $value) {
			$users[$value->id] = $value->first_name." ".$value->last_name." (".$value->email.")";
		}
		if($id == 0) {
			$q->with(array('country', 'state', 'client', 'user'))
			  ->orderBy('updated_at', 'DESC');
		} else {
			$q->with(array('country', 'state', 'client', 'user'))->whereHas('jobsAssigned', function($q) use ($id)
			{
			    $q->where('assigned_to_id','=', $id);
			})->orderBy('updated_at', 'DESC');
		}

		Log::info(Auth::user()->hasRole(3));

		if(Auth::user()->hasRole(2)) {
			$q->where('created_by', '=', Auth::user()->id);
		} elseif(Auth::user()->hasRole(3)) {
			$team = $this->getTeamUsers(Auth::user()->id);
			array_push($team, Auth::user()->id);
			$q->whereIn('created_by', $team);
		} elseif($id == 0 && !Auth::user()->hasRole(1) && !Auth::user()->hasRole(8)) {
			$q->where('created_by', '=', Auth::user()->id);
		}
		
		if(!empty(Input::get('title'))) {
			$q->where('title', 'like', "%".Input::get('title')."%");
		} 

		if(!empty(Input::get('type_of_employment'))){
			$q->where('type_of_employment', '=', Input::get('type_of_employment'));	
		}

		if(!empty(Input::get('created_by'))) {
			$job_post_creation_filter = Input::get('created_by');
			if($job_post_creation_filter !='-1') {
				$q->where('created_by', '=', $job_post_creation_filter);	
			}
			Cookie::queue('job_post_creation_filter', $job_post_creation_filter);
		} else if(Cookie::get('job_post_creation_filter') && Cookie::get('job_post_creation_filter') != '' && Cookie::get('job_post_creation_filter') != '-1') {
			$job_post_creation_filter = Cookie::get('job_post_creation_filter');
			$q->where('created_by', '=', $job_post_creation_filter);
		}

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}

		if(!empty(Input::get('csv_download_input'))) {
			$arrSelectFields = array('title', 'mode_of_interview', 'duration', 'description');

	        $q->select($arrSelectFields);
	        $data = $q->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('title', 'mode_of_interview', 'duration', 'description');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Title', 'Mode Of Interview', 'Duration(In Months)', 'Description');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}

		$jobPost = $q->paginate(100);


		return View::make('sales.listRequirements')->with(array('title' => 'List Requirement - Headhunting', 'jobPost' => $jobPost, 'id' => $id, 'users' => $users, 'job_post_creation_filter' => $job_post_creation_filter));
	}


	/**
	 *
	 * listRequirement() : listRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function filterRequirementList($id=0, $status=0) {

		$job_post_creation_filter = '';
		$q = JobPost::query();
		if($status == -1) {
			$q->where('status', '=', 2);
			$q->with(array('country', 'state', 'client', 'user'))->whereHas('jobsAssigned', function($q) use ($id)
			{
			    $q->where('assigned_to_id','>',0);
			}, '=', 0)->orderBy('updated_at', 'desc');
		} else {
			$q->where('status', '=', $status);
		}
		$usersAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->where('status', '=', 1)->whereHas('userRoles', function($q){
				    $q->where('role_id', '<=', 3);
				})->get();
		$users = array('-1'=> 'Please select');
		foreach( $usersAddedBy as $key => $value) {
			$users[$value->id] = $value->first_name." ".$value->last_name." (".$value->email.")";
		}

		if($id == 0) {
			$q->with(array('country', 'state', 'client', 'user'))->orderBy('updated_at', 'desc');
		} else {
			$q->with(array('country', 'state', 'client', 'user'))->whereHas('jobsAssigned', function($q) use ($id)
			{
			    $q->where('assigned_to_id','=', $id);
			})->orderBy('updated_at', 'desc');
		}
		

		if(Auth::user()->hasRole(2)) {
			$q->where('created_by', '=', Auth::user()->id);
		} elseif(Auth::user()->hasRole(3)) {
			$team = $this->getTeamUsers(Auth::user()->id);
			array_push($team, Auth::user()->id);
			$q->whereIn('created_by', $team);
		} elseif($id == 0 && !Auth::user()->hasRole(1) && !Auth::user()->hasRole(8)) {
			$q->where('created_by', '=', Auth::user()->id);
		}

		if(!empty(Input::get('title'))) {
			$q->where('title', 'like', "%".Input::get('title')."%");
		}

		if(!empty(Input::get('type_of_employment'))){
			$q->where('type_of_employment', '=', Input::get('type_of_employment'));	
		}

		if(!empty(Input::get('created_by'))) {
			$job_post_creation_filter = Input::get('created_by');
			if($job_post_creation_filter !='-1') {
				$q->where('created_by', '=', $job_post_creation_filter);	
			}
		}

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}

		if(!empty(Input::get('csv_download_input'))) {
			$arrSelectFields = array('title', 'mode_of_interview', 'duration', 'description');

	        $q->select($arrSelectFields);
	        $data = $q->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('title', 'mode_of_interview', 'duration', 'description');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Title', 'Mode Of Interview', 'Duration(In Months)', 'Description');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}

		$jobPost = $q->paginate(100);
		return View::make('sales.listRequirements')->with(array('title' => 'List Requirement - Headhunting', 'jobPost' => $jobPost, 'id' => $id, 'users' => $users, 'job_post_creation_filter'=> $job_post_creation_filter));	
	}

	/**
	 * postRequirement() :  Post Requirements
	 *
	 * @param String Email : REQUIRED Email
	 * @param String Password : REQUIRED User Password
	 * @param Integer type : OPTIONAL TYPE of User Account.
	 *
	 * @return Object Json
	 */
	public function postRequirement() {

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) {
			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'title' =>  'required|max:50',
							'type_of_employment' => 'required|numeric',
							'country_id' => 'required',
							'state_id' =>  'required',
							'description' =>  'required',
							'client_id' => 'required',
							'mode_of_interview' => 'max:247'
					)
			);

			if($validate->fails()) {

				return Redirect::to('post-requirement')
								->withErrors($validate)
								->withInput();
			} else {
				$inputs = Input::except(array('_token', '_wysihtml5_mode', 'city'));
				$jobPost = new JobPost();
				$jobPost->setConnection('master');
				foreach($inputs as $key => $value) {
					$jobPost->$key = $value;
				}

				$city = Input::get('city');

				if(isset($city) && !empty($city)) {
					$city_record = City::where('name', 'like', $city)->first();

					if($city_record) {
						$jobPost->city_id = $city_record->id;
					} else {
						$city_obj = new City();
						$city_obj->name = $city;
						$city_obj->save();
						$jobPost->city_id = $city_obj->id;
					}

				}

				$jobPost->created_by = Auth::user()->id;
				$jobPost->status = 1;

				if($jobPost->save()) {

					/* User activity */
					$description = Config::get('activity.job_post_creation');
					$authUser = Auth::user();
					$formatted_description = sprintf(
						$description,
						'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
						'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
					);
					$this->saveActivity('6', $formatted_description);

					/* Save Notification */
					$description = Config::get('notification.job_post_creation');
					$authUser = Auth::user();
					$formatted_description = sprintf(
						$description,
						'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
						'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
					);
					$subject_description = sprintf(
						$description,
						$authUser->first_name." ".$authUser->last_name,
						$jobPost->title
					);
					$lead = $this->getTeamLeadForUser(Auth::user()->id);
					if($lead){
						$this->saveNotification($formatted_description, [$lead->id], '', True, $subject_description);	
					} else {
						$this->saveNotification($formatted_description, [], '', True, $subject_description);
					}

					Session::flash('flashmessagetxt', 'Job Posted Successfully!!'); 
					return Redirect::route('list-requirement');
				} else {
					return Redirect::route('post-requirement')->withInput();
				}
			}
		}
	}

	/**
	 * updateRequirement() :  Update Requirements
	 *
	 * @param String Email : REQUIRED Email
	 * @param String Password : REQUIRED User Password
	 * @param Integer type : OPTIONAL TYPE of User Account.
	 *
	 * @return Object Json
	 */
	public function updateRequirement($id) {
		if($id != "") {
			$jobPost = JobPost::find($id);
		}
		if((Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) {
			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'title' =>  'required|max:50',
							'type_of_employment' => 'required|numeric',
							'country_id' => 'required',
							'state_id' =>  'required',
							'description' =>  'required',
							'client_id' => 'required',
							'mode_of_interview' => 'max:247'
					)
			);

			if($validate->fails()) {

				return Redirect::to('post-requirement')
				->withErrors($validate)
				->withInput();
			} else {

				$inputs = Input::except(array('_token', '_wysihtml5_mode', 'status', 'city', 'created_by'));
				$jobPost->setConnection('master');
				foreach($inputs as $key => $value) {
					$jobPost->$key = $value;
				}

				$city = Input::get('city');

				if(isset($city) && !empty($city)) {
					$city_record = City::where('name', 'like', $city)->first();

					if($city_record) {
						$jobPost->city_id = $city_record->id;
					} else {

						$city_obj = new City();
						$city_obj->name = $city;
						$city_obj->save();
						$jobPost->city_id = $city_obj->id;
					}

				}
				$jobPost->created_by = Auth::user()->id;
				$jobPost->status = $jobPost->status;

				if($jobPost->save()) {
					Session::flash('flashmessagetxt', 'Job Updated Successfully!!'); 
					return Redirect::route('list-requirement');
				} else {
					return Redirect::route('post-requirement', array($id))->withInput();
				}
			}
		}
	}


	/**
	 *
	 * editRequirementView() : editRequirementView
	 *
	 * @return Object : View
	 *
	 */
	public function editRequirementView($id) {
		if($id != "") {
			$jobPost = JobPost::find($id);
		}
		if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) {
			$country = Country::all();
			$count = array();
			foreach( $country as $key => $value) {
				$count[$value->id] = $value->country;
			}

			if(Auth::user()->hasRole(1)){
				$clients = Client::all();
			} else {
				$clients = Client::where('created_by', '=', Auth::user()->id)->get();	
			}
			$client = array();
			foreach( $clients as $key => $value) {
				$client[$value->id] = $value->first_name."-".$value->email;
			}

			return View::make('sales.postRequirement')->with(array('title' => 'Post Requirement - Headhunting', 'country' => $count, 'jobPost' => $jobPost, 'client' => $client));
		} else {
			return Redirect::to('dashboard');
		}
	}

	/**
	 *
	 * listSubmittel() : listSubmittel
	 *
	 * @return Object : View
	 *
	 */
	public function listSubmittel($id=0) {

		$login_user = Auth::user();
		$team = $this->getTeamUsers($login_user->id);
		$user_role = $login_user->getRole();
		# Redirecting user if not allowed
		if($user_role > 5) {
			return Redirect::to('dashboard');
		}

		$q = CandidateApplication::query()->orderBy('updated_at', 'DESC');

		$y = CandidateApplication::query();
		$z = JobPost::query();
		$submittle_status = Config::get('app.job_post_submittle_status');

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('submitted_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(submitted_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		$addedToList = $z->leftJoin('users', function($join){
			$join->on('created_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(created_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		if(!empty(Input::get('job_title'))) {
			$job_title = Input::get('job_title');
			$q->whereHas('requirement', function($q) use($job_title){
				$q->where('title',  'like', "%".$job_title."%");
			});
		}
		if(!empty(Input::get('submitted_to'))) {
			$submitted_to = Input::get('submitted_to');
			$q->whereHas('requirement', function($q) use($submitted_to){
    			$q->where('created_by',  '=', $submitted_to);
			});
		}
		if(!empty(Input::get('submitted_by'))) {
			$q->where('submitted_by', '=', Input::get('submitted_by'));	
		}
		if(!empty(Input::get('status'))) {
			$q->where('status', '=', Input::get('status'));	
		}
		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
		if($id == 0) {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('requirement'))->paginate(100);	
			}
		} else {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use ($login_user) {
											$q->where('created_by',  '=', $login_user->id);
										 })->where('job_post_id', '=', $id)
										   ->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use ($login_user, $team) {
    											$q->whereIn('created_by',  $team);
											})->where('job_post_id', '=', $id)
											->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)
										   ->where('job_post_id', '=', $id)
										   ->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)
										   ->where('job_post_id', '=', $id)
										   ->paginate(100);
			} else {
				$candidateApplications = $q->with(array('candidate', 'requirement'))
										   ->where('job_post_id', '=', $id)
										   ->paginate(100);
			}
		}
		#DB::enableQueryLog();
		#dd(DB::getQueryLog());
		//print_r(count($candidateApplications));die();
		return View::make('sales.listSubmittels')->with( array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications, 'submittle_status'=>$submittle_status, 'addedByList' => $addedByList, 'team' => $team, 'login_user' => $login_user, 'addedToList' => $addedToList));
	}


	/**
	 *
	 * listSubmittel() : listSubmittel
	 *
	 * @return Object : View
	 *
	 */
	public function interviewScheduledListSubmittel($id=0) {

		$status_search = false;
		$login_user = Auth::user();
		$team = $this->getTeamUsers($login_user->id);
		$user_role = $login_user->getRole();
		# Redirecting user if not allowed
		if($user_role > 5) {
			return Redirect::to('dashboard');
		}
		$q = CandidateApplication::query();	

		$y = CandidateApplication::query();
		$z = JobPost::query();
		$submittle_status = Config::get('app.job_post_submittle_status');

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('submitted_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(submitted_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		$addedToList = $z->leftJoin('users', function($join){
			$join->on('created_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(created_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		if(!empty(Input::get('job_title'))) {
			$job_title = Input::get('job_title');
			$q->whereHas('requirement', function($q) use($job_title){
				$q->where('title',  'like', "%".$job_title."%");
			});
		}
		if(!empty(Input::get('submitted_to'))) {
			$submitted_to = Input::get('submitted_to');
			$q->whereHas('requirement', function($q) use($submitted_to){
    			$q->where('created_by',  '=', $submitted_to);
			});
		}

		if(!empty(Input::get('submitted_by'))) {
			$q->where('submitted_by', '=', Input::get('submitted_by'));	
		}

		$q->where('status', '=', array_search('Interview Scheduled', $submittle_status));

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
        
		if($id == 0) {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('requirement'))->paginate(100);	
			}
		} else {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->where('job_post_id', '=', $id)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('candidate', 'requirement'))->where('job_post_id', '=', $id)->paginate(100);
			}
		}
		
		$team = $this->getTeamUsers(Auth::user()->id);
		
		return View::make('sales.listSubmittels')->with( array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications, 'submittle_status'=>$submittle_status, 'addedByList' => $addedByList, 'team' => $team, 'login_user' => $login_user, 'status_search' => $status_search, 'addedToList' => $addedToList));
	}


	/**
	 *
	 * listSubmittel() : listSubmittel
	 *
	 * @return Object : View
	 *
	 */
	public function selectedListSubmittel($id=0) {

		$login_user = Auth::user();
		$team = $this->getTeamUsers($login_user->id);
		$user_role = $login_user->getRole();
		# Redirecting user if not allowed
		if($user_role > 5) {
			return Redirect::to('dashboard');
		}
		$status_search = false;
		$q = CandidateApplication::query();

		$y = CandidateApplication::query();
		$z = JobPost::query();
		$submittle_status = Config::get('app.job_post_submittle_status');

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('submitted_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(submitted_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		$addedToList = $z->leftJoin('users', function($join){
			$join->on('created_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(created_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		if(!empty(Input::get('job_title'))) {
			$job_title = Input::get('job_title');
			$q->whereHas('requirement', function($q) use($job_title){
				$q->where('title',  'like', "%".$job_title."%");
			});
		}
		if(!empty(Input::get('submitted_to'))) {
			$submitted_to = Input::get('submitted_to');
			$q->whereHas('requirement', function($q) use($submitted_to){
    			$q->where('created_by',  '=', $submitted_to);
			});
		}
		if(!empty(Input::get('submitted_by'))) {
			$q->where('submitted_by', '=', Input::get('submitted_by'));	
		}

		$q->where('status', '=', array_search('Purchase Order', $submittle_status));

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
        
        if($id == 0) {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('requirement'))->paginate(100);	
			}
		} else {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->where('job_post_id', '=', $id)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('candidate', 'requirement'))->where('job_post_id', '=', $id)->paginate(100);
			}
		}
		
		$team = $this->getTeamUsers(Auth::user()->id);
		
		return View::make('sales.listSubmittels')->with( array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications, 'submittle_status'=>$submittle_status, 'addedByList' => $addedByList, 'team' => $team, 'login_user' => $login_user, 'status_search' => $status_search, 'addedToList' => $addedToList));
	}


	/**
	 *
	 * listSubmittel() : listSubmittel
	 *
	 * @return Object : View
	 *
	 */
	public function primeSubmittel($id=0) {

		$login_user = Auth::user();
		$team = $this->getTeamUsers($login_user->id);
		$user_role = $login_user->getRole();
		# Redirecting user if not allowed
		if($user_role > 5) {
			return Redirect::to('dashboard');
		}
		$status_search = false;
		$q = CandidateApplication::query();

		$y = CandidateApplication::query();
		$z = JobPost::query();
		$submittle_status = Config::get('app.job_post_submittle_status');

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('submitted_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(submitted_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		$addedToList = $z->leftJoin('users', function($join){
			$join->on('created_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(created_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		if(!empty(Input::get('job_title'))) {
			$job_title = Input::get('job_title');
			$q->whereHas('requirement', function($q) use($job_title){
				$q->where('title',  'like', "%".$job_title."%");
			});
		}
		if(!empty(Input::get('submitted_to'))) {
			$submitted_to = Input::get('submitted_to');
			$q->whereHas('requirement', function($q) use($submitted_to){
    			$q->where('created_by',  '=', $submitted_to);
			});
		}
		if(!empty(Input::get('submitted_by'))) {
			$q->where('submitted_by', '=', Input::get('submitted_by'));	
		}

		$q->where('status', '=', array_search('Forwarded To Prime Vendor', $submittle_status));

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}
        
        if($id == 0) {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('requirement'))->paginate(100);	
			}
		} else {
			if ($user_role == 2) { // sales- show submittles of job created by him
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user){
    				$q->where('created_by',  '=', $login_user->id);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 3) { // sales manager - show either created by him or his team
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereHas('requirement', function($q) use($login_user, $team){
    				$q->whereIn('created_by',  $team);
				})->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 4) { // recruter- show submitted by him only
				$addedByList = array();
				$candidateApplications = $q->where('submitted_by',  '=', $login_user->id)->where('job_post_id', '=', $id)->paginate(100);
			} else if ($user_role == 5) { // recruter manager
				array_push($team, $login_user->id);
				$candidateApplications = $q->whereIn('submitted_by', $team)->where('job_post_id', '=', $id)->paginate(100);
			} else {
				$candidateApplications = $q->with(array('candidate', 'requirement'))->where('job_post_id', '=', $id)->paginate(100);
			}
		}
		
		$team = $this->getTeamUsers(Auth::user()->id);
		
		return View::make('sales.listSubmittels')->with( array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications, 'submittle_status'=>$submittle_status, 'addedByList' => $addedByList, 'team' => $team, 'login_user' => $login_user, 'status_search' => $status_search, 'addedToList' => $addedToList));
	}

	/**
	 *
	 * addCommentView() : addCommentView
	 *
	 * @return Object : View
	 *
	 */
	public function addCommentView($jobId) {
		$jobPost = JobPost::with(array('comments', 'comments.user'))->select(array('id', 'title'))->where('id', '=', $jobId)->get();
		if(!$jobPost->isEmpty()) {
			$jobPost = $jobPost->first();
			return View::make('sales.postCommentRequirement')->with(array('title' => 'Job Post Comments - Headhunting', 'jobPost' => $jobPost));
		}
	}

	/**
	 *
	 * addCommentView() : addCommentView
	 *
	 * @return Object : View
	 *
	 */
	public function addComment($jobId, $view = 2) {
		$jobPost = JobPost::where('id', '=', $jobId)->get();
		if(!$jobPost->isEmpty()) {
			$jobPost = $jobPost->first();
			$validate=Validator::make (
					Input::all(), array(
							'comment' =>  'required',
							'job_post_id' => 'required|numeric'
					)
			);

			if($validate->fails()) {

				return Redirect::to('add-comment-job-post', array('jobId' => $jobId))
											 ->withErrors($validate)
											 ->withInput();
			} else {

				$jobPostComment = new JobPostComment();
				$jobPostComment->comment = Input::get('comment');
				$jobPostComment->job_post_id = Input::get('job_post_id');
				$jobPostComment->added_by = Auth::user()->id;
				$jobPostComment->created_at = date('Y-m-d H:i:s');
				if($jobPostComment->save()) {


					/* Save Notification */
					$description = Config::get('notification.job_post_comment');
					$authUser = Auth::user();
					$formatted_description = sprintf(
						$description,
						'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
						'<a href="/view-requirement/'.$jobPost->id.'">'.$jobPost->title.'</a>'
					);
					$subject_description = sprintf(
						$description,
						$authUser->first_name." ".$authUser->last_name,
						$jobPost->title
					);
					$to_notify_user = array($jobPost->created_by);
					$lead = $this->getTeamLeadForUser($jobPost->created_by);
					if(!empty($lead) && Auth::user()->id != $lead->id) {
						array_push($to_notify_user, $lead->id);
					}

					Log::info(json_encode($to_notify_user));
					$jobsAssigned = $jobPost->jobsAssigned;

					foreach ($jobsAssigned as $key => $value) {
						array_push($to_notify_user, $value->assigned_to_id);
					}

					Log::info(json_encode($to_notify_user));

		  			if(Auth::user()->hasRole(1) ) {
						$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
					} else {
						$this->saveNotification($formatted_description, $to_notify_user, '', True, $subject_description);
					}

					if(!empty($view) && $view == 1) {
						return Redirect::route('view-requirement', array('id' => $jobPostComment->job_post_id));
					} else {
						$jobPost = JobPost::with(array('comments', 'comments.user'))->select(array('id', 'title'))->where('id', '=', $jobId)->get()->first();
						Session::flash('flashmessagetxt', 'Comment Added Successfully!!'); 
						return View::make('sales.postCommentRequirement')->with(array('title' => 'Job Post Comments - Headhunting', 'jobPost' => $jobPost));
					}
				}
			}
		}
	}

	private function JobPostSubmittleStatus($candidateApplication, $status, $message='', $mail_content=''){
		$jpsStatus_obj = new JobPostSubmittleStatus();
		$jpsStatus_obj->job_post_submittle_id = $candidateApplication;
		$jpsStatus_obj->message = $message;
		$jpsStatus_obj->status = $status;
		$jpsStatus_obj->added_by = Auth::user()->id;
		$jpsStatus_obj->mail_content = $mail_content;
		$jpsStatus_obj->save();
		return $jpsStatus_obj;
	}

	public function rejectSubmittle($id) {
		if($_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$candidate_application = CandidateApplication::find($id);
			$candidate = Candidate::find($candidate_application->candidate_id);
			$lead = $this->getTeamLeadForUser($candidate_application->submitted_by);
			$authUser = Auth::user();
			if($authUser->id==$candidate_application->submitted_by || $authUser->hasRole(1) || (!empty($lead) && $lead->id = $authUser->id)) {

				$candidate_application->status = 10;
				$candidate_application->save();

				$jpsStatus_obj = $this->JobPostSubmittleStatus($candidate_application->id, 10, '');

				/* Save Notification */
				$to_notify_user = array();
				$description = Config::get('notification.job_post_submittle_rejected_by_manager');
				if(Auth::user()->id != $candidate_application->submitted_by) {
					array_push($to_notify_user, $candidate_application->submitted_by);
				}
				$job_post = JobPost::find($candidate_application->job_post_id);
				$to_notify_user = array($job_post->created_by);
				if(!empty($lead) && $authUser->id != $lead->id) {
					array_push($to_notify_user, $lead->id);
				}
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-candidate/'.$candidate->id.'">'.$candidate->first_name." ".$candidate->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>'
				);
				$subject_description = sprintf(
					$description,
					$authUser->first_name." ".$authUser->last_name,
					$candidate->first_name." ".$candidate->last_name,
					$job_post->title
				);
	  			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
				Session::flash('flashmessagetxt', 'Candidate Recommendation Rejected Successfully!!'); 
			}
		}
		return Redirect::route('list-submittel');
	}

	public function approveSubmittle($id) {
		if($_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$candidate_application = CandidateApplication::find($id);
			$candidate = Candidate::find($candidate_application->candidate_id);
			$lead = $this->getTeamLeadForUser($candidate_application->submitted_by);
			$authUser = Auth::user();
			if($authUser->id==$candidate_application->submitted_by || $authUser->hasRole(1) || (!empty($lead) && $lead->id = $authUser->id)) {

				$candidate_application->status = 1;
				$candidate_application->save();

				$jpsStatus_obj = $this->JobPostSubmittleStatus($candidate_application->id, 1, '');

				/* Save Notification */
				$to_notify_user = array();
				$description = Config::get('notification.job_post_submittle_approval');
				if(Auth::user()->id != $candidate_application->submitted_by) {
					array_push($to_notify_user, $candidate_application->submitted_by);
				}
				$job_post = JobPost::find($candidate_application->job_post_id);
				$to_notify_user = array($job_post->created_by);
				if(!empty($lead) && $authUser->id != $lead->id) {
					array_push($to_notify_user, $lead->id);
				}
				$formatted_description = sprintf(
					$description,
					'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
					'<a href="/view-candidate/'.$candidate->id.'">'.$candidate->first_name." ".$candidate->last_name.'</a>',
					'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>'
				);
				$subject_description = sprintf(
					$description,
					$authUser->first_name." ".$authUser->last_name,
					$candidate->first_name." ".$candidate->last_name,
					$job_post->title
				);
	  			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
				Session::flash('flashmessagetxt', 'Candidate Recommendation Approved Successfully!!'); 
			}
		}
		return Redirect::route('list-submittel');
	}

	public function updateSubmittleStatus() {
		if($_SERVER['REQUEST_METHOD'] == 'POST' ) {

			if( !empty(Input::get('cand_app')) && !empty(Input::get('job_status')) ){
				$cand_app = Input::get('cand_app');
				$status = Input::get('job_status');
				$message = Input::get('cand_app_msg');


				$submittle_status = Config::get('app.job_post_submittle_status');
				$candidate_application = CandidateApplication::find($cand_app);
				if(Input::has('client_rate') && Input::get('client_rate') != '') {

					$candidate_application->client_rate = Input::get('client_rate');
					//$candidate_application->submission_rate = Input::get('submission_rate');
				}
				$body_content = Input::get('mail_cont');
				$mail_subject = Input::get('mail_sub');

				$candidate = Candidate::find($candidate_application->candidate_id);
				$client = Client::find($candidate_application->requirement->client_id);
				$lead = $this->getTeamLeadForUser($candidate_application->requirement->created_by);
				$authUser = Auth::user();
				if($authUser->id == $candidate_application->requirement->created_by) {

					$candidate_application->status = $status;
					if($status == 6){
						$candidate_application->interview_scheduled_date = datetime::createfromformat('m/d/Y',Input::get('interview_scheduled_date'))->format('Y-m-d');
					}
					$candidate_application->save();
					if($status == 3){
						$mail_content = json_encode(array('content'=>$body_content, 'subject'=> $mail_subject));
						$jpsStatus_obj = $this->JobPostSubmittleStatus($candidate_application->id, $status, $message, $mail_content);
						//print_r(json_decode($mail_content, true));exit();
						$resume = CandidateResume::where('candidate_id', $candidate->id)->first();
						# send mail
						$setting = Setting::find(1);
						$disclaimer = ($setting->exists())?$setting->disclaimer:'';
						$signature = ($authUser->signature)?$authUser->signature:"";
						$body_content .= "<br />".$signature."<br />".$disclaimer;

						if(file_exists(public_path('/uploads/resumes/'.$candidate->id.'/'.$resume->resume_path))) {
							//$thirdPartyBccList = array();
							Log::info('/uploads/resumes/'.$candidate->id.'/'.$resume->resume_path);
							$fileExtension = File::extension('/uploads/resumes/'.$candidate->id.'/'.$resume->resume_path);
							$file = new Symfony\Component\HttpFoundation\File\File(public_path('/uploads/resumes/'.$candidate->id.'/'.$resume->resume_path));
							$mime = $file->getMimeType();

							Log::info($fileExtension." <<<, >>>> ".$mime);
							Config::set('mail.username', "crm@apetan.com");
							Config::set('mail.password', "!017@server");
							Config::set('mail.from.address', "crm@apetan.com");
							Config::set('mail.from.name', "crm" );
			       			Config::set('mail.host', "192.168.123.2");


							if(Config::get('app.env') === 'prod') {
								Log::info("Mail Sent for Submittels.");
								//Log::info("Client Email: ".$client->email);
								Mail::send([], [], function($message) use( &$authUser, &$body_content, &$mail_subject, &$candidate, &$client, &$resume, &$mime, &$fileExtension, &$lead) {
								//$message->to(trim($client->email), $client->first_name.' '.$client->last_name)
								$message->to(trim($authUser->email), $authUser->first_name.' '.$authUser->last_name)
										->cc($lead->email, $lead->first_name.' '.$lead->last_name)
										->subject($mail_subject)
										->setBody($body_content, 'text/html')
										->attach(
											\Swift_Attachment::fromPath(public_path("/uploads/resumes/".$candidate->id."/".$resume->resume_path), $mime)
										->setFilename("resume.".$fileExtension));
								});
							}	
						} else {
							//$thirdPartyBccList = array();
							Config::set('mail.username', $authUser->email);
							Config::set('mail.from.address', $authUser->email);
							Config::set('mail.from.name', $authUser->first_name .' '.$authUser->last_name );
			       			Config::set('mail.password', $authUser->email_password);

							if(Config::get('app.env') === 'prod') {
								Mail::send([], [], function($message) use( &$authUser, &$body_content, &$mail_subject, &$client, &$authUser) {
								$message->to(trim($authUser->email), $authUser->first_name.' '.$authUser->last_name)
										->subject($mail_subject)
										->setBody($body_content, 'text/html');
								});
							}
						}
						
					} else {
						$jpsStatus_obj = $this->JobPostSubmittleStatus($candidate_application->id, $status, $message);
					}

					/* Save Notification */
					$to_notify_user = array();
					$description = Config::get('notification.job_post_submittle_updation');
					if(Auth::user()->id != $candidate_application->submitted_by) {
						array_push($to_notify_user, $candidate_application->submitted_by);
					}
					$job_post = JobPost::find($candidate_application->job_post_id);
					if(!empty($lead) && $authUser->id != $lead->id) {
						array_push($to_notify_user, $lead->id);
					}
					$formatted_description = sprintf(
						$description,
						'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
						'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
						'"'.$submittle_status[$status].'"'
					);
					$subject_description = sprintf(
						$description,
						$authUser->first_name." ".$authUser->last_name,
						$job_post->title,
						'"'.$submittle_status[$status].'"'
					);
		  			$this->saveNotification($formatted_description, $to_notify_user, '', False, $subject_description);
					Session::flash('flashmessagetxt', 'Candidate Recommendation Updated Successfully!!'); 
				}
			}
		}
		return Redirect::route('list-submittel');		
	}

}

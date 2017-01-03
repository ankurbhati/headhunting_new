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
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				'<a href="/view-requirement/'.$job_post->id.'">'.$job_post->title.'</a>',
				'<a href="/view-employee/'.$assigned_user->id.'">'.$assigned_user->first_name." ".$assigned_user->last_name.'</a>'
			);
			$this->saveActivity('7', $formatted_description);

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
			$jobPost->status = 2;
			$jobPost->feedback = Input::get('feedback');
			$jobPost->save();
			Session::flash('flashmessagetxt', 'Job Closed Successfully!!'); 
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

		$q = JobPost::query();
		
		if($id == 0) {
			$q->with(array('country', 'state', 'client', 'user'))->orderBy('updated_at', 'desc');
		} else {
			$q->with(array('country', 'state', 'client', 'user'))->whereHas('jobsAssigned', function($q) use ($id)
			{
			    $q->where('assigned_to_id','=', $id);
			})->orderBy('updated_at', 'desc');
		}


		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(!empty(Input::get('title'))) {
				$q->where('title', 'like', "%".Input::get('title')."%");
			} 
			if(!empty(Input::get('type_of_employment'))){
				$q->where('type_of_employment', '=', Input::get('type_of_employment'));	
			}
			if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
				$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
				$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
				$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
			}
		}

		$jobPost = $q->paginate(100);
		return View::make('sales.listRequirements')->with(array('title' => 'List Requirement - Headhunting', 'jobPost' => $jobPost, 'id' => $id));	
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
				$jobPost->status = 2;

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

			$clients = Client::all();
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

		$q = CandidateApplication::query();

		$y = CandidateApplication::query();

		$addedByList = $y->leftJoin('users', function($join){
			$join->on('submitted_by', '=', 'users.id');
		})->select(DB::raw('DISTINCT(submitted_by) as id'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as name'))->lists('name', 'id');

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
		}

		if($id == 0) {
			$candidateApplications = $q->paginate(100);
		} else {
			$candidateApplications = $q->with(array('candidate', 'requirement'))->where('job_post_id', '=', $id)->paginate(100);
		}
		return View::make('sales.listSubmittels')->with(array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications, 'addedByList'=>$addedByList));
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
	public function addComment($jobId) {
		$jobPost = JobPost::where('id', '=', $jobId)->get();
		if(!$jobPost->isEmpty()) {

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
					$jobPost = JobPost::with(array('comments', 'comments.user'))->select(array('id', 'title'))->where('id', '=', $jobId)->get()->first();
					Session::flash('flashmessagetxt', 'Comment Added Successfully!!'); 
					return View::make('sales.postCommentRequirement')->with(array('title' => 'Job Post Comments - Headhunting', 'jobPost' => $jobPost));
				}
			}
		}
	}
}

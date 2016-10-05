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
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {
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
		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {
			$jobPost = JobPost::find($id)->delete();
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
		$jobPost = JobPost::with(array('country', 'state', 'client', 'city'))->find($id);
		return View::make('sales.viewRequirement')->with(array('title' => 'View Requirement - Headhunting', 'jobPost' => $jobPost,));
	}

	/**
	 *
	 * listRequirement() : listRequirement
	 *
	 * @return Object : View
	 *
	 */
	public function listRequirement($id=0) {
		if($id == 0) {
			$jobPost = JobPost::with(array('country', 'state', 'client', 'user'))->get();
		} else {
			$jobPost = JobPost::with(array('country', 'state', 'client', 'user'))->whereHas('jobsAssigned', function($q) use ($id)
			{
			    $q->where('assigned_to_id','=', $id);
			})->get();
		}
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

		if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) {
			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'title' =>  'required|max:50',
							'type_of_employment' => 'required|numeric',
							'country_id' => 'required',
							'state_id' =>  'required',
							'description' =>  'required|max:1000',
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
		if((Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) {
			// Server Side Validation.
			$validate=Validator::make (
					Input::all(), array(
							'title' =>  'required|max:50',
							'type_of_employment' => 'required|numeric',
							'country_id' => 'required',
							'state_id' =>  'required',
							'description' =>  'required|max:1000',
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
		if((Auth::user()->hasRole(1)|| Auth::user()->hasRole(2)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) {
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
		if($id == 0) {
			$candidateApplications = CandidateApplication::all();
		} else {
			$candidateApplications = CandidateApplication::with(array('candidate', 'requirement'))
																									 ->where('job_post_id', '=', $id)
																									 ->get();
		}
		return View::make('sales.listSubmittels')->with(array('title' => 'List Job Submittels - Headhunting', 'candidateApplications' => $candidateApplications));
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
					return View::make('sales.postCommentRequirement')->with(array('title' => 'Job Post Comments - Headhunting', 'jobPost' => $jobPost));
				}
			}
		}
	}
}

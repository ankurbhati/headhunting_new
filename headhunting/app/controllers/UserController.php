<?php
/**
 * UserController.php
 *
 * This file contatins controller class to provide APIs for Users
 *
 * @category   Controller
 * @package    User Management
 * @version    SVN: <svn_id>
 * @since      29th May 2014
 *
 */

/**
 * Contrller class will be responsible for All User management Related Actions
 *
 * @category   Controller
 * @package    User Management
 *
 */
class UserController extends HelperController {

	/**
	 *
	 * loginView() : login View
	 *
	 * @return Object : View
	 *
	 */

	private $report_target_dir = 'uploads/reports/';
	private $report_size = 5000000;

	public function loginView() {
		return View::make('User.login')->with(array('title' => 'Login Page'));
	}

	/**
	 *
	 * addEmployee() : login View
	 *
	 * @return Object : View
	 *
	 */
	public function addEmployee() {

		$roles = Role::all();
		$rols = array();
		foreach( $roles as $key => $value) {
			$rols[$value->id] = $value->role;
		}


		$country = Country::all();
		$count = array();
		foreach( $country as $key => $value) {
			$count[$value->id] = $value->country;
		}
		return View::make('User.newUser')->with(array('title' => 'Add Employee', 'roles' => $rols, 'country' => $count));
	}

	/**
	 *
	 * employeeList() : Employee List
	 *
	 * @return Object : View
	 *
	 */
	public function employeeList() {

		$roles = Role::all();
		$rols = array();
		foreach( $roles as $key => $value) {
			$rols[$value->id] = $value->role;
		}


		$q = User::query();
		$q->with(array('userRoles'))->where('id', '!=', Auth::user()->id);

		if(!empty(Input::get('email'))) {
			$q->where('email', 'like', "%".Input::get('email')."%");
		} 
		if(!empty(Input::get('first_name'))){
			$q->where('first_name', 'like', "%".Input::get('first_name')."%");	
		}
		if(!empty(Input::get('last_name'))) {
			$q->where('last_name', 'like', "%".Input::get('last_name')."%");	
		}
		if(!empty(Input::get('designation'))) {
			$q->where('designation', 'like', "%".Input::get('designation')."%");	
		}

		if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
			$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
			$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
			$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
		}

		if(!empty(Input::get('csv_download_input'))) {
			$arrSelectFields = array('email', 'first_name', 'last_name', 'phone_no', 'phone_ext', 'designation');

	        $q->select($arrSelectFields);
	        $data = $q->get();

	        // passing the columns which I want from the result set. Useful when we have not selected required fields
	        $arrColumns = array('email', 'first_name', 'last_name', 'phone_no', 'phone_ext', 'designation');
	         
	        // define the first row which will come as the first row in the csv
	        $arrFirstRow = array('Email', 'First Name', 'Last Name', 'Phone', 'Phone Ext', 'Designation');
	         
	        // building the options array
	        $options = array(
	          'columns' => $arrColumns,
	          'firstRow' => $arrFirstRow,
	        );

	        return $this->convertToCSV($data, $options);
		}
		
		$users = $q->paginate(100);

		return View::make('User.employeeList')->with(array('title' => 'Employee List', 'users' => $users, 'roles' => $rols));
	}

	/**
	 *
	 * getStates() : get States
	 *
	 * @return Object : JSON
	 *
	 */
	public function getStates($id) {
		$states = State::where('country_id', '=', $id)->get();
		return $this->sendJsonResponseOnly($states);
	}

	/**
	 *
	 * getPeers() : get getPeers
	 *
	 * @return Object : JSON
	 *
	 */
	public function getPeers($id) {
		$peerRole = Role::find($id)->pear_role_id;
		$users = UserRole::with(array('user'))->where("role_id", "=", $peerRole)->get();
		return $this->sendJsonResponseOnly($users);
	}

	/**
	 *
	 * getTeam() : get getPeers
	 *
	 * @return Object : JSON
	 *
	 */
	public function getTeam($id = 0) {

		$jobPost = "";
		$managerUsers = array();
		$users = array();
		$currentUserRole = Auth::user()->getRole();
		if($currentUserRole === 1 || $currentUserRole === 8) {
			$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
				    $q->where('role_id', '<', 6)
				      ->where('user_id', '!=', Auth::user()->id);
			})->paginate(100);
		} else {
			$users = UserPeer::with(array('user', 'peer.userRoles'))->where("peer_id", "=", Auth::user()->id)->paginate(100);
		}
		if($id > 0) {
			$jobPost = JobPost::find($id);
			if($currentUserRole == 2 || $currentUserRole == 3 || $currentUserRole == 5) {
				$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
				    $q->where('role_id', '<=', 5)
				      ->where('role_id', '>=', 4);
				})->paginate(100);
			}
		}

		
		return View::make('User.teamList')->with(array('title' => 'Team List', 'users' => $users, 'jobPostId' => $id, 'jobPost' => $jobPost, 'managerUsers' => $managerUsers));
	}


	/**
	 *
	 * getTeam() : get getPeers
	 *
	 * @return Object : JSON
	 *
	 */
	public function getSalesTeam($id = 0) {

		$jobPost = "";
		$managerUsers = array();
		$users = array();
		$currentUserRole = Auth::user()->getRole();
		$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
			    $q->where('role_id', '>=', 2)
			      ->where('role_id', '<=', 3)
			      ->where('user_id', '!=', Auth::user()->id);
		})->paginate(100);
		
		return View::make('User.teamList')->with(array('title' => 'Sales Team List', 'users' => $users, 'jobPostId' => $id, 'jobPost' => $jobPost, 'managerUsers' => $managerUsers, 'label' => 'Sales Team'));
	}

	/**
	 *
	 * getTeam() : get getPeers
	 *
	 * @return Object : JSON
	 *
	 */
	public function getRecruitmentTeam($id = 0) {

		$jobPost = "";
		$managerUsers = array();
		$users = array();
		$currentUserRole = Auth::user()->getRole();
		$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
			    $q->where('role_id', '>=', 4)
			      ->where('role_id', '<=', 5)
			      ->where('user_id', '!=', Auth::user()->id);
		})->paginate(100);

		
		return View::make('User.teamList')->with(array('title' => 'Recruitment Team List', 'users' => $users, 'jobPostId' => $id, 'jobPost' => $jobPost, 'managerUsers' => $managerUsers));
	}

	/**
	 *
	 * getCities() : get States
	 *
	 * @return Object : JSON
	 *
	 */
	public function getCities($id) {

		$cities = City::where('state_id', '=', $id)->get();
		return $this->sendJsonResponseOnly($cities);
	}

	/**
	 *
	 * deleteEmp() : Delete Employee
	 *
	 * @return Object : View
	 *
	 */
	public function deleteEmp($id) {
		if(Auth::user()->getRole() == 1) {
			if(User::find($id)->delete()) {
				Session::flash('flashmessagetxt', 'Employee Deleted Successfully!!');
				return Redirect::route('employee-list');
			}
		}
	}

	/**
	 *
	 * editEmp() : Edit Employee
	 *
	 * @return Object : View
	 *
	 */
	public function editEmp($id) {

		if(Auth::user()->getRole() == 1 || Auth::user()->getRole() == 8 || Auth::user()->id == $id ) {
			$roles = Role::all();
			$rols = array();

			foreach( $roles as $key => $value) {
				$rols[$value->id] = $value->role;
			}
			$country = Country::all();
			$count = array();

			foreach( $country as $key => $value) {
				$count[$value->id] = $value->country;
			}
			$user = User::with(array('userRoles'))->where('id', '=', $id)->get();

			if(!$user->isEmpty()) {
				$user = $user->first();
				return View::make('User.editUser')
						   ->with(array('title' => 'Edit Employee', 'user' => $user, 'country' => $count, 'roles' => $rols));
			} else {

				return Redirect::route('dashboard');
			}

		} else {

			return Redirect::route('dashboard');
		}
	}


	/**
	 *
	 * updatePass() : update Password
	 *
	 * @return Object : View
	 *
	 */
	public function updatePass($id) {

		if(Auth::user()->getRole() == 1 || Auth::user()->getRole() == 8 || Auth::user()->id == $id ) {

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
							'password' =>  'required|min:6',
							'confirm_password' =>  'required|min:6|same:password',
					)
			);

			if($validate->fails()) {
				return Redirect::route('change-password', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {
				$user = User::find($id);
				$user->setConnection('master');
				$password = Input::get('password');

				// Changing Password to Hash
				if(isset($password) && !empty($password)) {

					$user->password = Hash::make($password);
				}
				if($user->save()) {
					Session::flash('flashmessagetxt', 'Password Updated Successfully!!');
					if(Auth::user()->id == $id) {
						Auth::logout();
						return Redirect::to('/')
									   ->withInput(array('status' => 'success', 'message' => 'Your password has been updated
									   		Please relogin using new password.'));
					} else {
						return Redirect::route('dashboard-view');
					}
				}
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}

	/**
	 *
	 * updatePassView() : update Password View
	 *
	 * @return Object : View
	 *
	 */
	public function updatePassView($id) {

		if(Auth::user()->getRole() == 1 || Auth::user()->getRole() == 8 || Auth::user()->id == $id ) {

			$user = User::where("id", "=", $id)->get();

			if(!$user->isEmpty()) {
				return View::make('User.changePassword')
						   ->with(array('title' => 'Change Password'));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}

	/**
	 *
	 * viewEmp() : Edit Employee
	 *
	 * @return Object : View
	 *
	 */
	public function viewEmp($id) {

		if(Auth::user()->hasRole(1) || Auth::user()->getRole() == 8 || Auth::user()->id == $id ) {

			$user = User::with(array('userRoles', 'country', 'state'))->where('id', '=', $id)->get();

			if(!$user->isEmpty()) {
				$user = $user->first();
				return View::make('User.viewUser')
						   ->with(array('title' => 'View Employee', 'user' => $user));
			} else {

				return Redirect::route('dashboard-view');
			}

		} else {
			return Redirect::route('dashboard-view');
		}
	}

	/**
	 *
	 * editEmp() : Edit Employee
	 *
	 * @return Object : View
	 *
	 */
	public function updateEmp($id) {

		if(Auth::user()->getRole() == 1 || Auth::user()->getRole() == 8 || Auth::user()->id == $id) {
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
					'email' => 'required|email|max:50',
					'first_name' => 'required|max:50',
					'last_name' => 'required|max:50',
					'phone_no' => 'max:14',
					'phone_ext' => 'max:10',
					'designation' => 'required|max:50',
					'gender' => 'required',
				)
			);

			if($validate->fails()) {
				return Redirect::to('edit-member', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {
				$inputs = Input::except(array('_token', 'roles', 'doj', 'dor', 'mentor_id', '_wysihtml5_mode'));
				$user = User::find($id);

				if(Input::has("email") && Input::get('email') != $user->email){
					$email = Input::get('email');
					if(!User::where('email', '=', $email)->get()->isEmpty()) {
						return Redirect::route('edit-member', array('id' => $id))
						               ->withInput()
									   ->withErrors(array('email' => "Email is already in use"));
					}
				}
				$user->setConnection('master');

				foreach($inputs as $key => $value) {
					$user->$key = $value;
				}

				if(Input::has("doj") && Input::get('doj') != "") {
					$user->doj = date("Y-m-d H:i:s", strtotime(Input::get('doj')));
				}

				if(Input::has("dor") && Input::get('dor') != "") {
					$user->dor = date("Y-m-d H:i:s", strtotime(Input::get('dor')));
				}

				// Checking Authorised or not
				if($user->save()) {
					$userRoles = UserRole::where("user_id", "=", $id)->first();
					$userRoles->setConnection('master');
					$userRoles->role_id = Input::get('roles');
					$userRoles->user_id = $user->id;
					if($userRoles->save()) {
						if(Input::has("mentor_id")) {
							echo $mentorId = Input::get("mentor_id");
							if($mentorId > 0 && $mentorId != "") {
								$userPeer = UserPeer::where("user_id", "=", $user->id)->get();
								if($userPeer->isEmpty()) {
									$userPeer = new UserPeer();
								} else {
									$userPeer = $userPeer->first();
								}
								$userPeer->setConnection('master');
								$userPeer->peer_id =$mentorId;
								$userPeer->user_id =$user->id;
								$userPeer->save();
							}
						}
						Session::flash('flashmessagetxt', 'Details Updated Successfully!!');
						return Redirect::route('dashboard-view');
					}
				} else {
					return Redirect::route('edit-member')->withInput();
				}
			}
		}
	}

	/**
	 *
	 * home() : login View
	 *
	 * @return Object : View
	 *
	 */
	public function home() {
		$jobPosts = JobPost::where('status', '=', '2')->where('created_at', 'like', date('Y-m-d')."%")->get();
		return View::make('User.home')->with(array('title' => 'Dashboard', 'jobPosts' => $jobPosts));
	}

	/**
	 * login() :  User Login
	 *
	 * @param String Email : REQUIRED Email
	 * @param String Password : REQUIRED User Password
	 * @param Integer type : OPTIONAL TYPE of User Account.
	 *
	 * @return Object Json
	 */
	public function login() {

		// Server Side Validation.
		$validate=Validator::make (
				Input::all(), array(
					'email' =>  'required|max:50|email',
					'password' =>  'required|min:6',
				)
		);

		if($validate->fails()) {
			return Redirect::to('/')
					->withErrors($validate)
					->withInput();
		} else {
			$user = User::on('master')->where('email', '=', Input::get('email'))->first();
			if(!empty($user) && $user instanceof User) {

				// checking for Remember Flag.
				$remember = Input::has("remember") ? true : false;

				// Checking for Authorisation / Login
		    	$loginParams = array(
					'email' =>  Input::get('email'),
					'password' =>  Input::get('password'),
		    		'status' => 1
				);

				$auth = Auth::attempt($loginParams, $remember);
			} else {
				return Redirect::to('/')
				   			   ->withErrors(array('email' => 'Your Email or pasword is incorrect'));
			}
			// Checking Authorised or not
			if($auth) {

				return Redirect::to('dashboard');
			} else {
								return Redirect::to('/')
				   			   				   ->withErrors(array('email' => 'Your Email or pasword is incorrect'));

			}
		}
	}

	/**
	 * logout() : to Logout Logged in User.
	 *
	 * @param Integer userId : userId
	 *
	 * @return Object JSON
	 */
	public function logout() {

		if(Auth::check()) {

			Auth::logout();
			return Redirect::to('/')
						   ->withInput(array('status' => 'success', 'message' => 'Thanks for Comming'));
		}
	}

	/**
	 * addEmp() :  User Add
	 *
	 * @param String Email : REQUIRED Email
	 * @param String Password : REQUIRED User Password
	 * @param Integer type : OPTIONAL TYPE of User Account.
	 *
	 * @return Object Json
	 */
	public function addEmp() {

		if(Auth::user()->getRole() == 1 || Auth::user()->getRole() == 8 ) {
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
							'email' =>  'required|max:50|email|unique:users,email',
							'first_name' => 'required|max:50',
							'last_name' => 'required|max:50',
							'phone_no' => 'max:14',
							'phone_ext' => 'max:10',
							'designation' => 'required|max:50',
							'password' =>  'required|min:6',
							'confirm_password' =>  'required|min:6|same:password',
					)
			);

			if($validate->fails()) {

				return Redirect::to('add-employee')
							   ->withErrors($validate)
							   ->withInput();
			} else {

				$inputs = Input::except(array('_token', 'roles', 'confirm_password', 'mentor_id'));
				$user = new User();
				$user->setConnection('master');
				foreach($inputs as $key => $value) {
					$user->$key = $value;
				}
				$password = Input::get('password');

				// Changing Password to Hash
				if(isset($password) && !empty($password)) {

					$user->password = Hash::make($password);
				}

				$user->status = 1;

				// Checking Authorised or not
				if($user->save()) {
					$userRoles = new UserRole();
					$userRoles->setConnection('master');
					$userRoles->role_id = Input::get('roles');
					$userRoles->user_id = $user->id;
					if($userRoles->save()) {
						if(Input::has("mentor_id")) {
							$mentorId = Input::get("mentor_id");
							$userPeer = new UserPeer();
							$userPeer->setConnection('master');
							$userPeer->peer_id =$mentorId;
							$userPeer->user_id =$user->id;
							$userPeer->save();
						}
						Session::flash('flashmessagetxt', 'Employee Added Successfully!!');
						return Redirect::to('dashboard');
					}
				} else {
					return Redirect::to('add-employee')->withInput();
				}
			}
		}
	}

	/**
	 *
	 * employeeList() : mass Mail List
	 *
	 * @return Object : View
	 *
	 */
	public function massMailList() {

		$q = MassMail::query();
		
		//if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(!empty(Input::get('subject'))) {
				$q->where('subject', 'like', "%".Input::get('subject')."%");
			} 
			if(!empty(Input::get('description'))){
				$q->where('description', 'like', "%".Input::get('description')."%");	
			}
			if(!empty(Input::get('status'))){
				$status = Input::get('status');
				if($status <=2 || $status == 5 ) {
					$q->where('status', '=', $status);
				} else {
					$q->whereIn('status', array(3, 4));
				}
			}
			if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
				$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
				$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
				$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
			}
		//}

		if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8) ){
			$mass_mails = $q->with('sendby')->orderBy('id', 'DESC')->paginate(100);
		} else {
			$mass_mails = $q->with('sendby')->where('send_by', '=', Auth::user()->id)->orderBy('id', 'DESC')->paginate(100);
		}
		return View::make('User.massMailList')->with(array('title' => 'Mass Mail List', 'mass_mails' => $mass_mails));
	}


/**
	 *
	 * cancelMailList() : mass Mail List
	 *
	 * @return Object : View
	 *
	 */
	public function cancelMassMail($id) {

		if(Auth::user()->hasRole(1)) {
			$massMail = MassMail::find($id);
			if($massMail->status == 1) {
				$massMail->setConnection('master');
				$massMail->status = 5;

				if($massMail->save()) {
					Session::flash('flashmessagetxt', 'Mail Canceled Successfully!!');
				}
			} else {
				Session::flash('flashmessagetxt', 'Mail under Progress!!');
			}

		}
		return Redirect::route('mass-mail-list');
	}


	/**
	 *
	 * viewMassMail() : View Mass Mail
	 *
	 * @return Object : View
	 *
	 */
	public function viewMassMail($id) {

		if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
			$mass_mail = MassMail::with(array('sendby'))->where('id', '=', $id)->get();
		} else {
			$mass_mail = MassMail::with(array('sendby'))->where('id', '=', $id)->where('send_by', '=', Auth::user()->id)->get();
		}
		if(!$mass_mail->isEmpty()) {
			$mass_mail = $mass_mail->first();
			return View::make('User.viewMassMail')
					   ->with(array('title' => 'View Mass Mail', 'mass_mail' => $mass_mail));
		} else {
			return Redirect::route('dashboard-view');
		}
	}


	/**
	 *
	 * mass_mail() : mass mail view
	 *
	 * @return Object : View
	 *
	 */
	public function massMail() {

		if (Request::isMethod('post')) {

			if(Input::get('candidate_list')) {
				$candidate_list = Input::get('candidate_list');
				$mail_groups = MailGroup::all()->lists('name', 'id');
				return View::make('User.massMail')->with(array('title' => 'Mass Mail', 'candidate_list'=> $candidate_list));
			} else {
				if(Input::get('candidate_ids')) {
					$mass_mail = new MassMail();
					$mass_mail->subject = Input::get('subject');
					$mass_mail->description = Input::get('description');
					$mass_mail->send_by = Auth::user()->id;
					$mass_mail->limit_upper = "";
					$mass_mail->candidates = Input::get('candidate_ids');
					if($mass_mail->save()) {
						return Redirect::route('mass-mail-list');
					} else {
						return Redirect::route('mass-mail')->withInput();
					}
				}
				Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
			      $min_field = $parameters[0];
			      $data = $validator->getData();
			      $min_value = $data[$min_field];
			      return $value > $min_value && ($value - $min_value) <= 500;
			    });   

			    // Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
			    //   return str_replace(':field', $parameters[0], $message);
			    // });

				// Server Side Validation.
				$validate=Validator::make (
					Input::all(), array(
						'mail_group_id' =>  'required',
						'description' => 'required',
						'subject' => 'required|max:257',
						'limit_lower' => 'required|integer|min:0|digits_between: 1,6',
	  					'limit_upper' => 'required_with:limit_lower|integer|greater_than_field:limit_lower|digits_between:1,6'
					), 
					array(
						'limit_upper.greater_than_field' => 'Upper Limit should be greater than Lower Limit and difference should not be more than 500 emails'
						)
				);

				if($validate->fails()) {
					return Redirect::to('mass-mail')
								   ->withErrors($validate)
								   ->withInput();
				} else {
					$mass_mail = new MassMail();
					$mass_mail->subject = Input::get('subject');
					$mass_mail->mail_group_id = Input::get('mail_group_id');
					$mass_mail->description = Input::get('description');
					$mass_mail->limit_lower = Input::get('limit_lower');
					$mass_mail->limit_upper = Input::get('limit_upper');
					$mass_mail->send_by = Auth::user()->id;
					if($mass_mail->save()) {
						Session::flash('flashmessagetxt', 'Job Submitted Successfully!!');
						return Redirect::route('mass-mail-list');
					} else {
						return Redirect::route('mass-mail')->withInput();
					}
				}
			}
		} else {
			if( Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)  ) {			
				$mail_groups = MailGroup::all()->lists('name', 'id');
			} else {
				$mail_groups = MailGroup::where("name", "!=", 'Clients')->lists('name', 'id');
			}
			return View::make('User.massMail')->with(array('title' => 'Mass Mail', 'mail_groups'=> $mail_groups));
		}
	}


	/**
	 *
	 * sendMailFromCron() : sendMailFromCron
	 *
	 * @return Object :
	 *
	 */
	public function sendMailFromCron() {
		// picking massmails where status in process i.e 2 is less than equal to 5
		if(MassMail::where('status', '=', '2')->count() <= 5) {

			$mass_mail = MassMail::with(array('mailgroup'))->where('status', '=', '1');

			if($mass_mail->exists()) {
				$mass_mail = $mass_mail->first();
				$mass_mail->status = 2;
				$mass_mail->setConnection('master');
				$mass_mail->save();
				$authUser = User::find($mass_mail->send_by);
				if (isset($mass_mail->mail_group_id) && !empty($mass_mail->mail_group_id)) {

					$model = $mass_mail->mailgroup->model;
					$user_list = array();
					$users = MailGroupMember::where('group_id', '=', $mass_mail->mail_group_id)->lists('user_id');
					if($model == 'Client') {
						$user_list = $model::whereIn('id', $users)
											->where('created_by', '=', $authUser->id)
											->offset($mass_mail->limit_lower)
						                	->limit($mass_mail->limit_upper - $mass_mail->limit_lower)
											->get();
					} else if($model == 'Thirdparty') {
						$user_list = Thirdparty::whereHas('thirdPartyUsers', function($q) use (&$authUser)
										{
										    $q->where('user_id','=', $authUser->id);
										})
										->where('status', '=', 0)
										->offset($mass_mail->limit_lower)
						                ->limit($mass_mail->limit_upper - $mass_mail->limit_lower)
										->get();
						$queries = DB::getQueryLog();
						$last_query = end($queries);
						Log::info(json_encode($last_query));
					} else {
						$user_list = $model::whereIn('id', $users)->offset($mass_mail->limit_lower)
						                ->limit($mass_mail->limit_upper - $mass_mail->limit_lower)->get();
					}
				} else {
					$user_list = array();
					$user_list = Candidate::whereIn('id', explode(",",$mass_mail->candidates))->get();
				}
				$setting = Setting::find(1);
				$disclaimer = ($setting->exists())?$setting->disclaimer:'';
				$signature = ($authUser->signature)?$authUser->signature:"";
				Log::info("Limit Count : ".count($user_list));
				$emails = array();
				try{
					//$thirdPartyBccList = array();
					Config::set('mail.username', $authUser->email);
					Config::set('mail.from.address', $authUser->email);
					Config::set('mail.from.name', $authUser->first_name .' '.$authUser->last_name );
	       			Config::set('mail.password', $authUser->email_password);

	       			$body_content = $mass_mail->description."<br />".$signature."<br />".$disclaimer;

					foreach($user_list as $user) {
						if(!filter_var($user->email, FILTER_VALIDATE_EMAIL) === false) {
							array_push($emails, $user->email);
							if($model != 'Thirdparty') {
								Mail::send([], [], function($message) use(&$mass_mail, &$user, &$body_content)
								{



									$message->to(trim($user->email), $user->first_name . " " . $user->last_name)
									    	->subject($mass_mail->subject)
										    ->setBody($body_content, 'text/html');
								});
							}
						}
                	}
					if($model == 'Thirdparty' && count($emails) > 0) {
						Mail::send([], [], function($message) use(&$mass_mail, &$authUser, &$body_content, &$emails)
						{
						    $message->to(trim($authUser->email), $authUser->first_name . " " . $authUser->last_name)
								    ->bcc($emails)
								    ->subject($mass_mail->subject)
								    ->setBody($body_content, 'text/html');
						});
					}
					$mass_mail->status = 3;
					$mass_mail->save();
				}
				catch (Exception $e) {
    				Log::info('Caught exception: '.  $e->getMessage());
    				$mass_mail->status = 4;
					$mass_mail->save();
				}
			}
		}
	}

	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function workReport()
	{
		return View::make('User.uploadReport')->with(array('title' => 'Upload Report'));
	}

	/**
	 * Show the form for creating a new client.
	 *
	 * @return Response
	 */
	public function uploadWorkReport()
	{
		// Server Side Validation.
		$validate=Validator::make (
			Input::all(), array(
					'work_report' => 'required|mimes:pdf,doc,docx',
					'for_date' => 'required'
			)
		);

		if($validate->fails()) {
			return Redirect::to('work-report')
						   ->withErrors($validate)
						   ->withInput();
		} else {
			list($msg, $fileType) = $this->check_report_validity();
			if($msg){
				# error
				Session::flash('upload_report_error', $msg);
				return Redirect::route('work-report')->withInput();
			}
			$update = false;
			$authUser = Auth::user();
			$for_date = datetime::createfromformat('m/d/Y', Input::get('for_date'))->format('Y-m-d');
			$report = UserReport::where('for_date', $for_date)->where('user_id', $authUser->id)->first();
			if($report) {
				$update = true;
			} else {
				$report = new UserReport();
				$report->for_date = $for_date;
				$report->user_id = $authUser->id;
			}
			list($msg, $target_file) = $this->upload_report($report);
			if($msg) {
				Session::flash('upload_report_error', $msg);
				return Redirect::route('work-report')->withInput();
			} else {
				$tmp = explode("/", $target_file);
				$report->filename = end($tmp);
				$report->save();
			}
			/* User activity */
			if($update){
				$description = Config::get('activity.report_update');	
			}else{
				$description = Config::get('activity.report_upload');	
			}
			$formatted_description = sprintf(
				$description,
				'<a href="/view-employee/'.$authUser->id.'">'.$authUser->first_name." ".$authUser->last_name.'</a>',
				$report->for_date
			);
			if($update){
				$this->saveActivity('13', $formatted_description);
				Session::flash('flashmessagetxt', 'Updated Successfully!!');
			}else{
				$this->saveActivity('12', $formatted_description);
				Session::flash('flashmessagetxt', 'Uploaded Successfully!!');
			}
			return Redirect::route('work-report');
		}
	}


	/**
	 *
	 * activityList() : Activity List
	 *
	 * @return Object : View
	 *
	 */
	public function userReportList($id) {

		if(Auth::user()->hasRole(1) || Auth::user()->id == $id) {
			$q = UserReport::query();
			$q->where('user_id', '=', $id);
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
					$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d');
					$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d');
					$q->whereBetween('for_date', [$fromDateTime, $toDateTime]);
				}

				if(!empty(Input::get('csv_download_input'))) {
					$arrSelectFields = array('for_date');

			        $q->select($arrSelectFields);
			        $data = $q->get();

			        // passing the columns which I want from the result set. Useful when we have not selected required fields
			        $arrColumns = array('for_date');
			         
			        // define the first row which will come as the first row in the csv
			        $arrFirstRow = array('For Date');
			         
			        // building the options array
			        $options = array(
			          'columns' => $arrColumns,
			          'firstRow' => $arrFirstRow,
			        );

			        return $this->convertToCSV($data, $options);
				}

			}
			
			$reports = $q->paginate(100);

			return View::make('User.reportList')->with(array('title' => 'Report List', 'reports' => $reports));
		} else {
			return Redirect::route('dashboard-view');	
		}
	}

	/**
	 *
	 * saveSettings() : saveSettings
	 *
	 * @return Object : saveSettings
	 *
	 */
	public function settings() {
		$setting = Setting::get();
		if(!$setting->isEmpty()) {
			$setting = $setting->first();
		} else {
			$setting = new Setting();
		}
		return View::make('User.settings')->with(array('title' => 'Portal Settings', 'setting' => $setting));
	}


	/**
	 *
	 * loginView() : saveSettings
	 *
	 * @return Object : saveSettings
	 *
	 */
	public function saveSettings() {


		if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8)) {
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
							'disclaimer' =>  'required'
					)
			);
			if($validate->fails()) {

				return Redirect::route('settings', array())
								->withErrors($validate)
								->withInput();
			} else {
				$setting = Setting::get();
				if(!$setting->isEmpty()) {
					$setting = $setting->first();
				} else {
					$setting = new Setting();
				}
				
				$setting->disclaimer = Input::get('disclaimer');
				$setting->guidence = Input::get('guidence');
				
				// Checking Authorised or not
				if($setting->save()) {

					return Redirect::route('dashboard-view');
				} else {

					return Redirect::route('settings', array())
								->withErrors($validate)
								->withInput();
				}
			}
		}
	}
	
	// */1 * * * * /usr/bin/curl -k http://apetan.portal.com/send-mail-from-cron

	/**
	 *
	 * activityList() : Activity List
	 *
	 * @return Object : View
	 *
	 */
	public function activityList($id) {

		
		$types = array(
			'2'=>'third-party-multi-upload',
			'3'=>'third-party-single-upload',
			'4'=>'client-multi-upload',
			'5'=>'client-single-upload',
			'6'=>'job-post-creation',
			'7'=>'job-post-assign',
			'9'=>'job-post-submission',
			'10'=>'candidate-add',
			'11'=>'org-add',
			'12'=>'report_upload',
			'13'=>'report_update'	
		);

		$q = UserActivity::query();
		$q->where('added_by', '=', $id);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(!empty(Input::get('type'))) {
				$q->where('type', '=', Input::get('type'));	
			}

			if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
				$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
				$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
				$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
			}

			if(!empty(Input::get('csv_download_input'))) {
				$arrSelectFields = array('description', 'created_at');

		        $q->select($arrSelectFields);
		        $data = $q->get();

		        // passing the columns which I want from the result set. Useful when we have not selected required fields
		        $arrColumns = array('description', 'created_at');
		         
		        // define the first row which will come as the first row in the csv
		        $arrFirstRow = array('Description', 'Created At');
		         
		        // building the options array
		        $options = array(
		          'columns' => $arrColumns,
		          'firstRow' => $arrFirstRow,
		        );

		        return $this->convertToCSV($data, $options);
			}

		}
		
		$activities = $q->paginate(100);

		return View::make('User.activityList')->with(array('title' => 'Activity List', 'activities' => $activities, 'types' => $types));
	}

	public function check_report_validity(){
		$msg = false;
		$fileType = strtolower(pathinfo($_FILES["work_report"]["name"],PATHINFO_EXTENSION));

		// Check file size
		if ($_FILES["work_report"]["size"] > $this->report_size) {
		    $msg = "Sorry, your file is too large.";
		}

		// Allow certain file formats
		if($fileType != "doc" && $fileType != "docx" && $fileType != "pdf") {
		    $msg = "Sorry, only doc, docx and pdf files are allowed.";
		}

		return array($msg, $fileType);
	}

	public function upload_report($report) {
		$msg = false;
		$fileType = pathinfo($_FILES["work_report"]["name"],PATHINFO_EXTENSION);
		$target_dir = DOCROOT.$this->report_target_dir.$report->id.'/';
		$target_file = $target_dir . uniqid() . "." . $fileType;

		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		// if everything is ok, try to upload file
	    if (!move_uploaded_file($_FILES["work_report"]["tmp_name"], $target_file)) {
	        $msg = "Sorry, there was an error uploading your file.";
	    }

		return array($msg, $target_file);
	}

	public function crobJobForUserReports() {
		date_default_timezone_set('Asia/Kolkata');
		//$current_date = date('Y-m-d', time());
		$current_date = date("Y-m-d", time() - 60 * 60 * 24);
		//$yesterday_date = date("Y-m-d", time() - 60 * 60 * 24);
		$total_report_sent = UserReport::where('for_date', '=', $current_date)->count();
		if($total_report_sent) {
			$business_head = $this->getBussinessHead();
			$users = UserReport::where('for_date', '=', $current_date)->lists('user_id');
            $defaulters = User::whereNotIn('id', $users)->whereHas('userRoles', function($q) {
    			$q->whereNotIn('role_id', array(1,2,7,8));
			})->select('first_name', 'last_name', 'email')->lists('email');
    
            $body_content = 'hi, <br/><br/>';
            $body_content .= '<table><tbody><th style="border: 1px solid grey;">Defaulter Emails For '.$current_date.'</th>';
            foreach($defaulters as $defaulter) {
            	$body_content .= '<tr><td style="border: 1px solid grey;">'.$defaulter.'</td></tr>';
            }
            $body_content .= '</tbody></table>';
            //print $body_content;
            try{
            	//$this->sendNotificationMail($body_content, $business_head, $subject='Employee Reports For'.$current_date);
            }catch(Exception $e){
            	print $e->getMessage();
            }
			print 'In crobJobForUserReports';exit();
		}else{
			// do nothing as its a holiday
		}
	}

	
	public function showNotifications() {

		$status_array = array('0'=>'Unseen', '1'=>'Seen');
		$auth_user = Auth::user();
		$q = Notification::query();
		$q->where('user_id', '=', $auth_user->id);

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
				$fromDateTime = datetime::createfromformat('m/d/Y',Input::get('from_date'))->format('Y-m-d 00:00:00');
				$toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
				$q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
			}

			if(!empty(Input::get('csv_download_input'))) {
				$arrSelectFields = array('message', 'created_at');

		        $q->select($arrSelectFields);
		        $data = $q->get();

		        // passing the columns which I want from the result set. Useful when we have not selected required fields
		        $arrColumns = array('message', 'created_at');
		         
		        // define the first row which will come as the first row in the csv
		        $arrFirstRow = array('Message', 'Created At');
		         
		        // building the options array
		        $options = array(
		          'columns' => $arrColumns,
		          'firstRow' => $arrFirstRow,
		        );

		        return $this->convertToCSV($data, $options);
			}

		}

		if(Request::ajax()){
            $q->limit(10)->update(['status' => '1']);
            $notifications = $q->limit(10)->get();
            return ['count' => $q->count(), 'data' => json_encode($notifications)];
        }
		$q->update(['status' => '1']);
		$notifications = $q->paginate(200);

		return View::make('User.notificationList')->with(array('title' => 'Notification List', 'notifications' => $notifications));
	}

	public function showReports(){

		date_default_timezone_set('Asia/Kolkata');
		$auth_user = Auth::user();
		if(!$auth_user->hasRole(1)) {
			return Redirect::route('dashboard-view');
		}
		$count = 1;
		$current_date = date("Y-m-d", time() - $count * 60 * 60 * 24);
		$q = UserReport::query();
		// $total_report_sent = UserReport::where('for_date', '=', $current_date)->count();
		// while(empty($total_report_sent)) {
		// 	$count++;
		// 	$current_date = date("Y-m-d", time() - $count * 60 * 60 * 24);
		// 	$total_report_sent = UserReport::where('for_date', '=', $current_date)->count();
  //       }

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!empty(Input::get('for_date'))) {
				$forDateTime = datetime::createfromformat('m/d/Y',Input::get('for_date'))->format('Y-m-d');
				$q->where('for_date', '=', $forDateTime);
			} else {
				$q->where('for_date', '=', $current_date);
			}
		} else {
			$q->where('for_date', '=', $current_date);
		}
		$user_reports = $q->paginate(100);
		return View::make('User.userReportList')->with(array('title' => 'User Reports', 'user_reports' => $user_reports));
	}

	public function selfTest(){
		print 'In self test';
		$user = Auth::user();
		Mail::send([], [], function($message) use( &$user) {
			$message->to(trim('ankurbhati03@gmail.com'), 'Ankur Bhati')
			    	->subject('TEST')
			    	->setBody('Hi ankur', 'text/html')
			    	->attach(
						\Swift_Attachment::fromPath(public_path("/uploads/reports/5/58dc1ab009cfb.pdf"), 'application/pdf')
					->setFilename("myfilename.pdf"));
		});
		print 'In first mail';
		/*Mail::send([], [], function($message) use( &$user) {
			$message->to(trim('ankurbhati03@gmail.com'), 'Ankur Bhati')
			    	->subject('TEST')
			    	->attach(
						\Swift_Attachment::fromPath(public_path("/uploads/reports/5/58dc1ab009cfb.pdf"), 'application/pdf')->setFilename("myfilename.pdf")
					)
				    ->setBody('Hi ankur', 'text/html');
		});*/
		print 'After mail';
	}


}
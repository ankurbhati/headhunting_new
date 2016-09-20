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
		$users = User::with(array('userRoles'))->where('id', '!=', Auth::user()->id)->get();
		return View::make('User.employeeList')->with(array('title' => 'Employee List', 'users' => $users));
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
		$users = UserRole::with(array('user'))->where("role_id", "=",$peerRole)->get();
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
		if($currentUserRole === 1) {
			$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
				    $q->where('role_id', '<', 6)
				      ->where('user_id', '!=', Auth::user()->id);
			})->get();
		} else {
			$users = UserPeer::with(array('peer', 'peer.userRoles'))->where("peer_id", "=", Auth::user()->id)->get();
		}

		if($id > 0) {
			$jobPost = JobPost::find($id);
			if($currentUserRole === 2 || $currentUserRole === 3 || $currentUserRole === 5) {
				$managerUsers = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->whereHas('userRoles', function($q){
				    $q->where('role_id', '<=', 5)
				      ->where('role_id', '>=', 4);
				})->get();
			}
		}

		
		return View::make('User.teamList')->with(array('title' => 'Team List', 'users' => $users, 'jobPostId' => $id, 'jobPost' => $jobPost, 'managerUsers' => $managerUsers));
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

		if(Auth::user()->getRole() == 1 || Auth::user()->id == $id ) {
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

		if(Auth::user()->getRole() == 1 || Auth::user()->id == $id ) {

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

		if(Auth::user()->getRole() == 1 || Auth::user()->id == $id ) {

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

		if(Auth::user()->hasRole(1) || Auth::user()->id == $id ) {

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

		if(Auth::user()->getRole() == 1 || Auth::user()->id == $id) {
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
							'designation' => 'required|max:50',
							'gender' => 'required',
					)
			);

			if($validate->fails()) {
				return Redirect::to('edit-member', array('id' => $id))
								->withErrors($validate)
								->withInput();
			} else {
				$inputs = Input::except(array('_token', 'roles', 'doj', 'dor', 'mentor_id'));
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

		if(Auth::user()->getRole() == 1) {
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
							'phone' => 'max:10',
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
	 * mass_mail() : mass mail view
	 *
	 * @return Object : View
	 *
	 */
	public function massMail() {

		if (Request::isMethod('post')) {
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
						'mail_group_id' =>  'required',
						'description' => 'required'
				)
			);

			if($validate->fails()) {
				return Redirect::to('mass-mail')
							   ->withErrors($validate)
							   ->withInput();
			} else {
				$mass_mail = new MassMail();
				$mass_mail->mail_group_id = Input::get('mail_group_id');
				$mass_mail->description = Input::get('description');
				if($mass_mail->save()) {
					return Redirect::route('dashboard-view');
				} else {
					return Redirect::route('mass-mail')->withInput();
				}
			}
		} else {
			$mail_groups = MailGroup::all()->lists('name', 'id');
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
		$mass_mails = MassMail::with(array('mailgroup'))->where('status', '=', '1')->get();
		foreach($mass_mails as $mass_mail) {
			$mass_mail->status = 2;
			$mass_mail->save();
			$model = $mass_mail->mailgroup->model;
			$users = MailGroupMember::where('group_id', '=', $mass_mail->mailgroup->id)->lists('user_id');
			$user_list = $model::whereIn('id', $users)->get();
			foreach($user_list as $user) {
				Config::set('mail.username', Auth::user()->email);
       			Config::set('mail.password', Auth::user()->email_password);

				Mail::queue([], [], function($message) use(&$mass_mail, &$user)
				{
				    $message->to($user->email, $user->first_name . " " . $user->last_name)
				    ->subject('Head hunting')
				    ->setBody($mass_mail->description, 'text/html');
				});

			}
		}
	}

}

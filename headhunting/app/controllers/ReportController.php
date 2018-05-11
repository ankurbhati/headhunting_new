<?php

class ReportController extends HelperController {

	/**
	 *
	 * listReports() : View Reports Form
	 *
	 * @return Object : View
	 *
	 */
	public function listReports() {


		$usersAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->where('status', '=', 1)->whereHas('userRoles', function($q){
			$q->where('role_id', '<=', 5)
			  ->where('role_id', '>', 1);
		})->get();
		$users = array('-1'=> 'Please select');
		foreach( $usersAddedBy as $key => $value) {
			$users[$value->id] = $value->first_name." ".$value->last_name." (".$value->email.")";
		}
		$teamsAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))
							->where('status', '=', 1)
							->whereHas('userRoles', function($q){
								$q->where('role_id', '=', 5);
							})
							->orWhereHas('userRoles', function($q){
								$q->where('role_id', '=', 3);
							})->orderBy('first_name')->get();
		$teams = array('-1'=> 'Please select');
		foreach( $teamsAddedBy as $key => $value) {
			$teams[$value->id] = $value->first_name." ".$value->last_name." Team";
		}


		return View::make('Report.listReports')
					->with(array('title' => 'Reports', 'users' => $users, 'teams' =>$teams));
	}

}

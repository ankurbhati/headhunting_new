<?php
class ReportController extends HelperController
{

    /**
     *
     * listReports() : View Reports Form
     *
     * @return Object : View
     *
     */
    public function listReports()
    {

        $usersAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->where('status', '=', 1)->whereHas('userRoles', function ($q) {
            $q->where('role_id', '<=', 5)
                ->where('role_id', '>', 1);
        })->get();
        $users = array('-1' => 'Please select');
        foreach ($usersAddedBy as $key => $value) {
            $users[$value->id] = $value->first_name . " " . $value->last_name . " (" . $value->email . ")";
        }
        $teamsAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))
            ->where('status', '=', 1)
            ->whereHas('userRoles', function ($q) {
                $q->where('role_id', '=', 5);
            })
            ->orWhereHas('userRoles', function ($q) {
                $q->where('role_id', '=', 3);
            })->orderBy('first_name')->get();
        $teams = array('-1' => 'Please select');
        foreach ($teamsAddedBy as $key => $value) {
            $teams[$value->id] = $value->first_name . " " . $value->last_name . " Team";
        }

        return View::make('Report.listReports')
            ->with(array('title' => 'Reports', 'users' => $users, 'teams' => $teams, 'data' => array()));
    }



    /**
     *
     * listReports() : View Reports Form
     *
     * @return Object : View
     *
     */
    public function getReport()
    {

        $usersAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))->where('status', '=', 1)->whereHas('userRoles', function ($q) {
            $q->where('role_id', '<=', 5)
                ->where('role_id', '>', 1);
        })->get();
        $users = array('-1' => 'Please select');
        foreach ($usersAddedBy as $key => $value) {
            $users[$value->id] = $value->first_name . " " . $value->last_name . " (" . $value->email . ")";
        }
        $teamsAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))
            ->where('status', '=', 1)
            ->whereHas('userRoles', function ($q) {
                $q->where('role_id', '=', 3);
            })
            ->orderBy('first_name')->get();
        $teams = array('-1' => 'Please select');
        foreach ($teamsAddedBy as $key => $value) {
            $teams[$value->id] = $value->first_name . " " . $value->last_name. " Sales Team";
        }

        $teamsAddedBy = User::select(array('id', 'first_name', 'last_name', 'email', 'designation'))
            ->where('status', '=', 1)
            ->whereHas('userRoles', function ($q) {
                $q->where('role_id', '=', 5);
            })
            ->orderBy('first_name')->get();
        foreach ($teamsAddedBy as $key => $value) {
            $teams[$value->id] = $value->first_name . " " . $value->last_name. " Recruitment Team";
        }

        if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
            $fromDateTime = datetime::createfromformat('m/d/Y', Input::get('from_date'))->format('Y-m-d 00:00:00');
            $toDateTime = datetime::createfromformat('m/d/Y', Input::get('to_date'))->format('Y-m-d 23:59:59');
        }

        $team = array();
        $report = array();

        if (Input::has('team_id') && Input::get('team_id') > 0) {
            $team = $this->getTeamById(Input::get('team_id'));
        }

        $request = Input::except(array('page'));
        if (count($request) < 9) {
            $request = array(
                "report_type" => -1,
                "team_id" => -1,
                "user_id" => -1,
                "from_date" => "",
                "to_date" => "",
                "report_status_clients" => -1,
                "report_status_vendors" => -1,
                "report_status_job_posts" => -1,
                "report_status_submittels" => -1,
            );
        }
        //print_r($request);die;
        if (Input::has('report_type') && Input::get('report_type') >= 0) {
            switch ($request['report_type']) {
                case 0:
                    $q = Candidate::query()->orderBy('created_at', 'DESC');
                    
                    if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
                        $q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                    }

                    if (!empty(Input::get('user_id')) && Input::get('user_id') > 0) {
                        $q->where('added_by', '=', Input::get('user_id'));
                    }

                    if (Input::has('team_id') && Input::get('team_id') > 0 && Input::get('user_id') == -1) {
                        $q->whereIn('added_by', $team);
                    }
                    $viewName = 'report.candidateReport';
                    $report = $q->paginate(100)->appends(Input::except('page'));
                    $count = $q->count();
                    break;
                case 1:
                    $q = Client::query()->orderBy('created_at', 'DESC');
                    if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
                        $q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                    }

                    if (!empty(Input::get('user_id')) && Input::get('user_id') > 0) {
                        $q->where('created_by', '=', Input::get('user_id'));
                    }

                    if (!empty(Input::get('report_status_clients')) && Input::get('report_status_clients') > 0) {
                        $q->where('status', '=', Input::get('report_status_clients'));
                    }

                    if (Input::has('team_id') && Input::get('team_id') > 0 && Input::get('user_id') == -1) {
                        $q->whereIn('created_by', $team);
                    }
                    $viewName = 'report.clientReport';
                    $report = $q->paginate(100)->appends(Input::except('page'));
                    $count = $q->count();
                    break;
                case 2:
                    $q = Thirdparty::query()->orderBy('created_at', 'DESC');
                    if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
                        $q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                    }

                    if (!empty(Input::get('user_id')) && Input::get('user_id') > 0) {
                        $q->where('created_by', '=', Input::get('user_id'));
                    }

                    if (!empty(Input::get('report_status_vendors')) && Input::get('report_status_vendors') > 0) {
                        $q->where('status', '=', Input::get('report_status_vendors'));
                    }

                    if (Input::has('team_id') && Input::get('team_id') > 0 && Input::get('user_id') == -1) {
                        $q->whereIn('created_by', $team);
                    }
                    $viewName = 'report.thirdPartyReport';
                    $report = $q->paginate(100)->appends(Input::except('page'));
                    $count = $q->count();
                    break;
                case 3:
                    $q = JobPost::query()->orderBy('created_at', 'DESC');
                    if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
                        $q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                    }

                    if (!empty(Input::get('user_id')) && Input::get('user_id') > 0) {
                        $q->where('created_by', '=', Input::get('user_id'));
                    }

                    if (!empty(Input::get('report_status_job_posts')) && Input::get('report_status_job_posts') > 0) {
                        $q->where('status', '=', Input::get('report_status_job_posts'));
                    }

                    if (Input::has('team_id') && Input::get('team_id') > 0 && Input::get('user_id') == -1) {
                        $q->whereIn('created_by', $team);
                    }
                    $viewName = 'report.jobPostReport';
                    $report = $q->paginate(100)->appends(Input::except('page'));
                    $count = $q->count();
                    break;
                case 4:
                    $q = CandidateApplication::query()->orderBy('created_at', 'DESC');
                    if (!empty(Input::get('from_date')) && !empty(Input::get('to_date'))) {
                        $q->whereBetween('created_at', [$fromDateTime, $toDateTime]);
                    }
                    $data = CandidateApplication::count();
                    break;
                default:
                    return Redirect::to('reports')
                        ->withErrors($validate)
                        ->withInput();
            }
        }

        //echo count($data);die;
        if (count($report) > 0) {
            return View::make('report.listReports')
                ->with(array('title' => 'Reports', 'users' => $users, 'teams' => $teams, 'request' => $request, 'data' => $report))->nest('child', $viewName, array('data' => $report));
        } else {
            return View::make('Report.listReports')
                ->with(array('title' => 'Reports', 'users' => $users, 'teams' => $teams, 'request' => $request, 'data' => $report));
        }
    }

    /**
	 * validating candidate while creating.
	 *
	 * @return Response
	 */
	public function getTeamUsers($id)
	{
		$response = array();
    	$response['error'] = false;
		$response['records'] = $this->getTeamArrayById($id);
		return $this->sendJsonResponseOnly($response);
	}

}

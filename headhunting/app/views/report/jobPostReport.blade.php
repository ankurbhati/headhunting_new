<table id="employeeList" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Job ID</th>
            <th>Job Title <br> Client</th>
            <th>Type Of Employment <br> City, Country <br/>Added At | Last Updated at</th>
            <th>Added By <br>Assigned To</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($data as $jobPosts)
                  <tr class="job-post {{$jobPosts->getClass()}}">
                  <td>APT-0{{$jobPosts->id}}</td>
                      <td><b>{{$jobPosts->title}}</b><br/>
                    @if($jobPosts->client && ($jobPosts->created_by == Auth::user()->id || Auth::user()->isMyTeamById($jobPosts->created_by) || Auth::user()->hasRole(1))){{$jobPosts->client->first_name." ".$jobPosts->client->last_name."-".$jobPosts->client->email}}@else {{"*****"}} @endif
                  </td>
                  <td>
                  <b>{{($jobPosts->type_of_employment == 1)?"Contractual":(($jobPosts->type_of_employment == 2)?"Permanent": "Contract to hire");}}</b>  |  
                  {{($jobPosts->city)?$jobPosts->city->name:''}}, {{$jobPosts->state?$jobPosts->state->state:''}}<br/>
                    <span class="text-label">Posted at : </span>{{($jobPosts->created_at != "" && $jobPosts->created_at != "0000-00-00 00:00:00")?date("d M, Y H:i", strtotime($jobPosts->created_at)):"-"}}  |<br>  
                    <span class="text-label">Last Updated At: </span>{{($jobPosts->updated_at != "" && $jobPosts->updated_at != "0000-00-00 00:00:00")?date("d M, Y H:i", strtotime($jobPosts->updated_at)):"-"}}
                  </td>
                                  <td>@if($jobPosts->user){{$jobPosts->user->first_name." ".$jobPosts->user->last_name."-".$jobPosts->user->email}}@else {{"-"}} @endif <br/>
                    <span class="text-label">Assigned To Users:</span>{{$jobPosts->getAssignedNames()}}
                  </td>
                                  <td>
                  <a target="_new" href="{{ URL::route('list-submittel', array($jobPosts->id)) }}">
                      <span class="short-text">Submittels__{{$jobPosts->candidateApplications()}}</span>
                     </a> 
                    </br>
                    @if($jobPosts->status == 1)
                      <span class="text-pending">Pending</span>
                    @elseif($jobPosts->status == 2)
                      <span class="text-open">Open</span>
                    @elseif($jobPosts->status == 3)
                      <span class="text-open">Closed</span>
                    @elseif($jobPosts->status == 4)
                      <span class="text-open">Rejected</span>
                    @endif
                  </td>
                      <td>
                          <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('view-requirement', array('id' => $jobPosts->id)) }}" title="View Job Post">View</a>
                    @if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPosts) && Auth::user()->id == $jobPosts->created_by) 
                            <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('edit-requirement', array($jobPosts->id)) }}" title="Edit Job Post">
                        Edit
                      </a>
                    @endif
                      <a  target="_blank"class="btn btn-primary btn-white" href="{{ URL::route('advance-search', array($jobPosts->id)) }}" title="Search Candidate">
                        Search
                      </a>
                      <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('add-comment-job-post-view', array($jobPosts->id)) }}" title="Add Comments">
                        Add Comments
                      </a>
                    @if( $jobPosts->status == 2  && (Auth::user()->hasRole(3) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) || Auth::user()->hasRole(1)) )
                      <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('peers', array($jobPosts->id)) }}" title="Assign To Peers">
                        Assign
                      </a>
                    @endif
                    @if($jobPosts->status ==1  && ( Auth::user()->hasRole(3) || Auth::user()->hasRole(1) ) )
                      <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('approve-requirement', array($jobPosts->id)) }}" title="Approve Job Post">
                        Approve
                      </a>
                      <a target="_blank" class="btn btn-primary btn-white btn-red" href="{{ URL::route('reject-requirement', array($jobPosts->id)) }}" title="Reject Job Post">
                        Reject
                      </a>
                    @endif
                    @if($jobPosts->status !=3  && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                      <a target="_blank"  class="btn btn-primary btn-white" href="{{ URL::route('close-requirement', array($jobPosts->id)) }}" title="Close Job Post">
                        Close
                      </a>
                    @endif
                    @if(($jobPosts->status == 3 || $jobPosts->status == 4) && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                      <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('reopen-requirement', array($jobPosts->id)) }}" title="Reopen Job Post">
                        Reopen
                      </a>
                    @endif
                      <a target="_blank"  class="btn btn-primary btn-white" href="{{ URL::route('mass-mail') }}" title="Mass Mail">
                        Mass Mail
                      </a>
                      </td>
                  </tr>
                      @endforeach

        </tbody>
      </table>
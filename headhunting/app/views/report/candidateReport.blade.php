<table id="employeeList" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Email<br/>Full Name</br>Phone</th>
            <th>Work State<br>Visa Id</th>
            <th>Added By</th>
            <th>Added At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($data as $candidate)
            <tr>
                <td>
                    <b>{{$candidate->email}}</b><br/> {{$candidate->first_name. " ".$candidate->last_name
                    }}<br/> {{$candidate->phone}}
                </td>
                <td>
                    {{($candidate->workstate->id == 3)?$candidate->workstate->title:$candidate->workstate->title }}{{($candidate->source_id !=
                    "" && $candidate->workstate->id == 3)?"":""}}<br>{{$candidate->visa->title}}
                </td>
                <td>{{$candidate->createdby->first_name. " ". $candidate->createdby->last_name}}</td>
                <td>
                    {{($candidate->created_at != "" && $candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->created_at)):"-"}}
                </td>
                <td>
                      <a target="_blank" href="{{ URL::route('view-candidate', array('id' => $candidate->id)) }}" title="View Profile" class="btn btn-primary btn-white">View</a>                                    @if(Auth::user()->getRole()
                    <=5 || Auth::user()->hasRole(8))
                        <a target="_blank" href="{{ URL::route('edit-candidate', array($candidate->id)) }}" title="Edit Profile" class="btn btn-primary btn-white">Edit</a>                                        @endif @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                        <a target="_blank" href="{{ URL::route('delete-candidate', array($candidate->id)) }}" title="Delete Profile" class="btn btn-primary btn-white">Delete</a>                                        @endif @if((Auth::user()->getRole()
                        <=3 || Auth::user()->hasRole(8)) && $candidate->resume_path && file_exists(public_path('/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path)))
                            <a target="_blank" href="{{'/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path}}" title="Download Resume" target="_blank" class="btn btn-secondary btn-white">Download Resume</a>                                            @endif
                            <a target="_blank" href="{{ URL::route('candidate-application-list', array('id' => $candidate->id)) }}" title="View Profile"
                                class="btn btn-primary btn-white">View Submission History</a>

                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
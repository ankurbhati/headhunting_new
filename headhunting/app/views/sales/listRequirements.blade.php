@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Job Postings</h3>
                </div><!-- /.box-header -->
                {{ Form::open(array('class' => 'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}
                  <div class="form-group">
                      {{ Form::label('title', 'Job Title: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('title', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Job Title')); }}
                          <span class='errorlogin email-login'>{{$errors->first('title');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  <div class="form-group">
                      {{ Form::label('created_by', 'Added By: ', array('class' => 'col-sm-3
                    control-label')); }}
                      <div class="col-sm-8">{{ Form::select('created_by', $users, Auth::user()->id, array('class' => 'form-control')) }}
                          <span class='errorlogin'>{{$errors->first('type_of_employment');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  <div class="form-group">
                      {{ Form::label('type_of_employment', 'Type Of Employment: ', array('class' => 'col-sm-3
                    control-label')); }}
                      <div class="col-sm-8">{{ Form::select('type_of_employment', array(0=>"Select type of employment", 1=>"Contratual", 2=> "Permanent", 3=>"Contract to hire"),0, array('class' => 'form-control')) }}
                          <span class='errorlogin'>{{$errors->first('type_of_employment');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>

                  <div class="form-group">
                      {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-4">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                          <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                      <div class="col-sm-4">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                          <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>

                  <div class="form-group row ">
                    <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
                    <div class="col-sm-3">
                        {{ Form::button('Search', array('class' => 'btn btn-primary  btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
                    </div>
                    <div class="col-sm-8">
                        {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button', 'style'=>"float:right") ); }}
                    </div>
                  </div>

              {{ Form::close() }}
                <div class="box-body">
                 <div>
                      <p class="result-total"><span class="text-bold">{{$jobPost->getTotal()}} Job Posted:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Job ID</th>
                        <th>Job Title<br/>Client<br>Type Of Employment<br/>City, Country<br/>Added At</th>
                        <th>Added By</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach($jobPost as $jobPosts)
		                      <tr>
                              <td>APT-0{{$jobPosts->id}}</td>
              		            <td><b>{{$jobPosts->title}}</b><br/>
                              @if($jobPosts->client && ($jobPosts->created_by == Auth::user()->id || Auth::user()->isMyTeamById($jobPosts->created_by) || Auth::user()->hasRole(1))){{$jobPosts->client->first_name." ".$jobPosts->client->last_name."-".$jobPosts->client->email}}@else {{"*****"}} @endif
                              <hr>
                              <b>{{($jobPosts->type_of_employment == 1)?"Contractual":(($jobPosts->type_of_employment == 2)?"Permanent": "Contract to hire");}}</b><br/>
                              {{($jobPosts->city)?$jobPosts->city->name:''}}, {{$jobPosts->country?$jobPosts->country->country:''}}<br/>
                              <span class="text-label">Posted at : </span>{{($jobPosts->created_at != "" && $jobPosts->created_at != "0000-00-00 00:00:00")?date("d M, Y H:i", strtotime($jobPosts->created_at)):"-"}}<br/>
                              </td>
              								<td>@if($jobPosts->user){{$jobPosts->user->first_name." ".$jobPosts->user->last_name."-".$jobPosts->user->email}}@else {{"-"}} @endif <br/>
                              <br/>
                                {{"Assigned To Users: ".$jobPosts->getAssignedNames()}}
                              </td>
              								<td>
                                <span><strong>Submittels Count:</strong><span class="text-success"> <strong>{{$jobPosts->candidateApplications()}}</strong></span><br>
                                @if($jobPosts->status == 1)
                                  <span class="text-pending">Pending</span>
                                @elseif($jobPosts->status == 2)
                                  <span class="text-open">Open</span>
                                @elseif($jobPosts->status == 3)
                                  <span class="text-open">Closed</span>
                                @endif
                              </td>
              		            <td>
  		                        	<a class="btn btn-primary btn-white" href="{{ URL::route('view-requirement', array('id' => $jobPosts->id)) }}" title="View Job Post">View</a>
                                @if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPosts) && Auth::user()->id == $jobPosts->created_by) 
  		                        	  <a class="btn btn-primary btn-white" href="{{ URL::route('edit-requirement', array($jobPosts->id)) }}" title="Edit Job Post">
                                    Edit
                                  </a>
                                @endif
                                @if($id == 0 && $jobPosts->jobsAssignedToMe()->count() == 0)
  		                        		<!--<a href="{{ URL::route('assign-requirement', array($jobPosts->id)) }}" title="Assign To me"><i class="fa fa-plus"></i></a>-->
                                @else
                                  <a class="btn btn-primary btn-white" href="{{ URL::route('advance-search', array($jobPosts->id)) }}" title="Search Candidate">
                                    Search
                                  </a>
  		                        	@endif
  		                        	@if(Auth::user()->getRole() <= 2 || Auth::user()->hasRole(8))
  		                        		<a class="btn btn-secondary btn-white" href="{{ URL::route('delete-requirement', array($jobPosts->id)) }}" title="Delete Job Post">
                                    Delete
                                  </a>
  		                        	@endif
                                  <a class="btn btn-primary btn-white" href="{{ URL::route('add-comment-job-post-view', array($jobPosts->id)) }}" title="Add Comments">
                                    Add Comments
                                  </a>
                                @if( $jobPosts->status == 2  && (Auth::user()->hasRole(3) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) || Auth::user()->hasRole(1)) )
                                  <a class="btn btn-primary btn-white" href="{{ URL::route('peers', array($jobPosts->id)) }}" title="Assign To Peers">
                                    Assign
                                  </a>
                                @endif
                                @if($jobPosts->status ==1  && ( Auth::user()->hasRole(3) || Auth::user()->hasRole(1) ) )
                                  <a class="btn btn-primary btn-white" href="{{ URL::route('approve-requirement', array($jobPosts->id)) }}" title="Approve Job Post">
                                    Approve
                                  </a>
                                @endif
                                @if($jobPosts->status !=3  && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                                  <a  class="btn btn-primary btn-white" href="{{ URL::route('close-requirement', array($jobPosts->id)) }}" title="Close Job Post">
                                    Close
                                  </a>
                                @endif
                                @if($jobPosts->status == 3 && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                                  <a  class="btn btn-primary btn-white" href="{{ URL::route('reopen-requirement', array($jobPosts->id)) }}" title="Reopen Job Post">
                                    Reopen
                                  </a>
                                @endif
  		                        </td>
		                      </tr>
						          @endforeach

                    </tbody>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Jobs Posted :  <span class="text-bold">{{$jobPost->getTotal()}}</span></p>
                  </div>
                  @if (count($jobPost) > 0)
                    <div>
                       
                      {{ $jobPost->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

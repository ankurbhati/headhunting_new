@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Job Submittels</h3>
                </div><!-- /.box-header -->



                {{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}

                <div class="form-group">
                    {{ Form::label('submitted_by', 'Submitted By: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::select('submitted_by', array('' => 'Please select one Submitter') + $addedByList, "", array('class' => 'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('submitted_by');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('status', 'Status: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::select('status', $submittle_status, "", array('class' => 'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-2">{{ Form::text('from_date', "", array('placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-2">{{ Form::text('to_date', "", array('placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group row ">
                    <div class="col-sm-11" style="text-align:center;">{{ Form::submit('Search', array('class' => 'btn
                        btn-primary btn-white', 'id' => 'login-button') ); }}</div>

               </div>
            {{ Form::close() }}




                <div class="box-body">
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Candidate Name</th>
                        <th>Candidate Email</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($candidateApplications as $candidateApplication)
		                      <tr>
		                        <td>{{$candidateApplication->requirement->title}}</td>
            								<td>{{($candidateApplication->requirement->type_of_employment == 1)?"Contractual": ($candidateApplication->requirement->type_of_employment == 2)?"Permanent": "Contract to hire";}}</td>
            								<td>{{$candidateApplication->candidate->first_name. " ".$candidateApplication->candidate->last_name}}</td>
            								<td>{{$candidateApplication->candidate->email}}</td>
            								<td>{{$submittle_status[$candidateApplication->status]}}</td>
                            <?php $submittle_status_rec = JobPostSubmittleStatus::where('job_post_submittle_id', '=',$candidateApplication->id)->orderBy('created_at', 'desc')->first()?>
                            <td>{{(!empty($submittle_status_rec->message))?$submittle_status_rec->message:'-'}}</td>
                            <td>{{($candidateApplication->created_at != "" && $candidateApplication->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidateApplication->created_at)):"-"}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-requirement', array('id' => $candidateApplication->requirement->id)) }}" title="View Job Post"><i class="fa fa-fw fa-eye"></i></a>
                              <a href="{{ URL::route('view-candidate', array('id' => $candidateApplication->candidate->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
                              <a href="{{ URL::route('add-comment-job-post-view', array($candidateApplication->requirement->id)) }}" title="Add Comments"><i class="fa fa-fw fa-edit"></i></a>
                              @if ($candidateApplication->status == 0 && ($login_user->hasRole(1) || $login_user->id == $candidateApplication->submitted_by || (!empty($lead) && $login_user->id == $lead->id) ) )
                                <a href="{{ URL::route('approve-submittle', array($candidateApplication->id)) }}" title="Approve Candidate Recomendation">
                                  <i class="glyphicon glyphicon-ok"></i>
                                </a>
                              @endif
                              @if ( ($candidateApplication->status == 1 || $candidateApplication->status == 3 || $candidateApplication->status == 5 || $candidateApplication->status == 6) && ($login_user->id == $candidateApplication->requirement->created_by))
                                <a href="javascript:void(0);" class="updatejobstatus" data-status="{{$candidateApplication->status}}" data-candapp="{{$candidateApplication->id}}" title="Update Status">
                                  <i class="glyphicon glyphicon-ok"></i>
                                </a>                              
                              @endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Job Posts</p>
						@endforelse

                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Candidate Name</th>
                        <th>Candidate Email</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  
                  <div id="myModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                      <div class="modal-header" style="margin-bottom: 5px">
                        <span class="closemodal">&times;</span>
                        <h4>Update Status</h4>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="{{ URL::route('update-submittle-status')}}" name="model-form">
                        <input type="hidden" value="" name="cand_app">
                        <label style="padding: 0px 10px 10px 0px">Reason: </label><textarea value="" name="cand_app_msg"></textarea></br>
                        <input id="login-button" style="margin: 0px 0px 15px 20px" class="btn btn-info" type="submit" value="Submit">
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  @if (count($candidateApplications) > 100)
                    <div>
                       
                      {{ $candidateApplications->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

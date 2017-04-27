@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Job Submittels</h3>
                </div><!-- /.box-header -->
                {{ Form::open(array('class' => 'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}

                <div class="form-group">
                    {{ Form::label('submitted_by', 'Submitted By: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::select('submitted_by', array('' => 'Please select one Submitter') + $addedByList, "", array('class' => 'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('submitted_by');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                @if(!(isset($status_search)))
                <div class="form-group">
                    {{ Form::label('status', 'Status: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::select('status', $submittle_status, "", array('class' => 'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                @endif

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
                  <div>
                        <p class="result-total"><span class="text-bold">{{$candidateApplications->getTotal()}} results</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Job Title / <br>Type Of Employment</th>
                        <th>Candidate Name / <br/>
                            Candidate Email
                        </th>
                        <th>Status</th>
                        <th>Client Rate/<br>
                            Recruter's Rate
                        </th>
                        <th>Submitted At /
                            <br/>
                            Submitted By
                        </th>
                        <th>Message</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($candidateApplications as $candidateApplication)
		                      <tr>
		                        <td>
                              {{$candidateApplication->requirement->title}}
                              <br/>
                              {{($candidateApplication->requirement->type_of_employment == 1)?"Contractual": ($candidateApplication->requirement->type_of_employment == 2)?"Permanent": "Contract to hire";}}
                            </td>
            								<td>
                              <a href="{{ URL::route('view-candidate', array('id' => $candidateApplication->candidate->id)) }}" target="_blank">
                                {{$candidateApplication->candidate->first_name. " ".$candidateApplication->candidate->last_name}}<br/>{{$candidateApplication->candidate->email}}
                              </a>
                              <div>
                                <input type="hidden" name="requirement_id_{{$candidateApplication->id}}" value="{{$candidateApplication->requirement->id}}">
                                <input type="hidden" name="requirement_title_{{$candidateApplication->id}}" value="{{$candidateApplication->requirement->title}}">
                                <input type="hidden" name="candidate_name_{{$candidateApplication->id}}" value="{{$candidateApplication->candidate->first_name. " ".$candidateApplication->candidate->last_name}}">
                                <input type="hidden" name="candidate_email_{{$candidateApplication->id}}" value="{{$candidateApplication->candidate->email}}">
                                <input type="hidden" name="candidate_phone_{{$candidateApplication->id}}" value="
                                {{$candidateApplication->candidate->phone}}">
                              </div>
                            </td>
                            <td class="text-status-{{$candidateApplication->status}}">
                              {{$submittle_status[$candidateApplication->status]}}
                              @if($candidateApplication->status == array_search('Interview Scheduled', $submittle_status) && !empty($candidateApplication->interview_scheduled_date))
                                {{'('.(date("Y-m-d", strtotime($candidateApplication->interview_scheduled_date))).')'}}
                              @endif
                            </td>
                            <td>{{!empty($candidateApplication->client_rate)?"$".$candidateApplication->client_rate:"-"}}<br>
                            {{!empty($candidateApplication->submission_rate)?"$".$candidateApplication->submission_rate:"-"}}
                            </td>
                            <td>
                            {{$candidateApplication->submittedBy->first_name." ".$candidateApplication->submittedBy->last_name}}
                            <br/>
                            {{($candidateApplication->created_at != "" && $candidateApplication->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidateApplication->created_at)):"-"}}</td>
                            <td>
                            {{(!empty($candidateApplication->applicationStatus->first()->message))?$candidateApplication->applicationStatus->first()->message:'-'}}</td>
		                        <td>
                              @if ($candidateApplication->status == 0 && ($login_user->hasRole(1) || $login_user->id == $candidateApplication->submitted_by || (in_array($candidateApplication->submitted_by, $lead)) ) )
                                <a href="{{ URL::route('approve-submittle', array($candidateApplication->id)) }}" title="Approve Candidate Recomendation"  class="btn btn-secondary btn-white">
                                  Approve Submittels
                                </a>
                              @endif
                              @if ( ($candidateApplication->status == 1 || $candidateApplication->status == 3 || $candidateApplication->status == 5 || $candidateApplication->status == 6) && ($login_user->id == $candidateApplication->requirement->created_by))
                                <a href="javascript:void(0);" class="updatejobstatus btn btn-primary btn-white" data-status="{{$candidateApplication->status}}" data-candapp="{{$candidateApplication->id}}"  class="btn btn-secondary btn-white" title="Update Status">
                                  Update Status
                                </a>                              
                              @endif
		                        	<a href="{{ URL::route('view-requirement', array('id' => $candidateApplication->requirement->id)) }}" title="View Job Post" class="btn btn-primary btn-white">Job Post</a>
                              <a href="{{ URL::route('view-candidate', array('id' => $candidateApplication->candidate->id)) }}" title="View Profile"  class="btn btn-primary btn-white">Candidate</a>
                              <a href="{{ URL::route('add-comment-job-post-view', array($candidateApplication->requirement->id)) }}" title="Add Comments"  class="btn btn-secondary btn-white"> Add Comments</a>
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Job Posts</p>
						      @endforelse

                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Job Title / <br>Type Of Employment</th>
                        <th>Candidate Name / <br/>
                            Candidate Email
                        </th>
                        <th>Status</th>
                        <th>Client Rate/<br>
                            Recruter's Rate
                        </th>
                        <th>Submitted At /
                            <br/>
                            Submitted By
                        </th>
                        <th>Message</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  
                  <div id="myModal" class="modal container">
                    <!-- Modal content -->
                    <div class="modal-content box">
                      <div class="modal-header box-header" style="margin-bottom: 5px">
                        <span class="closemodal pull-right" style="padding:7px 15px; font-size:24px;">&times;</span>
                        <h3 class="box-title">Job Submittel Update Status</h3>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="{{ URL::route('update-submittle-status')}}" name="model-form">
                          <div id="modal-form-content"></div>
                          <input type="hidden" value="" name="cand_app">
                          <div class="client-form-rate col-xs-12">
                            <div class="form-group row">
                              <div>
                                <label for="crate">Client Rate: </label>
                              </div>
                              <input id="crate" class="form-control" type="number" min="0" value="" name="client_rate" />
                            </div>
                          </div>

                          <div class="submit_endclient-form-rate col-xs-12">
                            <div class="form-group row">
                              <div>
                                <label for="mail_sub">Mail Subject: </label>
                              </div>
                              <input id="mail_sub" class="form-control" type="text" value="" name="mail_sub" />
                            </div>
                            <div class="form-group" style="margin-bottom:15px;">
                                <label for="mail_cont">Mail Content: </label>
                                <div>
                                    <textarea id="mail_cont" class="form-control" value="" name="mail_cont"></textarea>
                                </div>
                                <span class="text-info">Resume will be sent as an attachment</span>
                              </div>
                            </div>
                          </div>

                          <div id='interview_scheduled_date' class="form-group" style="margin: 10px 0px; margin-left:12px;">
                            <label for="interview_date">Interview Date: </label><input type="text" id="interview_date" value="" name="interview_scheduled_date" class="interview_date form-control" placeholder="Enter Interview Date" style="margin: 5px 0px; width: 26%;">
                          </div>
                          <div class="form-group col-xs-12">
                            <label for="reason">Reason: </label>
                            <textarea id="reason" class="form-control" value="" name="cand_app_msg"></textarea>
                          </div>
                          <input id="login-button" style="margin: 10px 0px; margin-left:12px;" class="btn btn-primary btn-white" type="submit" value="Submit">
                          <button type="reset" value="Reset" style="float: right; margin: 10px 0px;  margin-right:12px;" class="btn btn-primary btn-white">Reset</button>
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

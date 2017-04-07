@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <a class="btn btn-secondary btn-white" href="{{ URL::route('advance-search', array($jobId)) }}">
                          Back To Search Candidate
                        </a>
            <div class="box">
                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$candidate_resumes->getTotal()}} results for <strong>{{$searching_text}}</strong></span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" name="checkall" value="">Check All</th>
                        <th>Name<br>Email<br/>Designation<br/>Visa</th>
                        <th>Resume</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @foreach($candidate_resumes as $candidate)
		                      <tr>
                            <td><input type="checkbox" class="checkcandidate" value="{{$candidate->candidate->id}}"></td>
                            <td>
                                <a href="{{ URL::route('view-candidate', array('id' => $candidate->candidate_id, 'jobId' => $jobId, 'searchingText' => $searching_text_to_send)) }}" target="_blank">
                                {{$candidate->candidate->first_name." ".$candidate->candidate->last_name}}
                                <br>{{$candidate->candidate->email}}</a>
                                <br>
                                <strong><span class="text-label">Designation : </span>{{$candidate->candidate->designation}}<br>
                                @if($candidate->candidate->visa)<span class="text-label">Visa : </span>{{$candidate->candidate->visa->title}}@else{{"-"}}@endif</strong>

                            </td>
                            <td>
                                <p>{{$candidate->resume?'"'.str_replace("<br />", "", substr($candidate->resume, 0, 80)).'..."':""}}</p>
                            </td>
                            <td>{{($candidate->candidate->created_at != "" && $candidate->candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->candidate->created_at)):"-"}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-candidate', array('id' => $candidate->candidate_id, 'jobId' => $jobId, 'searchingText' => $searching_text_to_send)) }}" title="View Profile" class="btn btn-primary btn-white">View </a>
                              @if(Auth::user()->getRole() <= 3 && $candidate->resume_path && file_exists(public_path('/uploads/resumes/'.$candidate->candidate_id.'/'.$candidate->resume_path)))
                              <a href="{{'/uploads/resumes/'.$candidate->candidate_id.'/'.$candidate->resume_path}}" title="Download Resume"  class="btn btn-secondary btn-white">Download</a>
                              @endif
                              @if($jobId > 0)
                                <a href="javascript:void(0);" data-url="{{ URL::route('job-submittel', array('jobId' => $jobId, 'userId' => $candidate->candidate_id)) }}" title="Mark Submittel" title="Update Status"  class="btn btn-primary btn-white updatejobsubmittle">
                                  Submittel
                                </a>
                              @endif
		                        </td>
		                      </tr>
						      @endforeach
                    </tbody>
                    <tfoot>
                        <th><input type="checkbox" name="checkall" value="">Check All</th>
                        <th>Name<br>Email<br>Designation</th>
                        <th>Resume</th>
                        <th>Added At</th>
                        <th>Action</th>
                    </tfoot>
                  </table>

                  <div id="myModal" class="modal container">
                    <!-- Modal content -->

                    <div class="modal-content box">
                      <div class="modal-header box-header" style="margin-bottom: 5px">
                        <span class="closemodal pull-right" style="padding:7px 15px; font-size:24px;">&times;</span>
                        <h3 class="box-title">Job Submittel</h3>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="" name="model-form">
                        <div id="modal-form-content"></div>
                        <div class="form-group row">
                          <div>
                          <label for="crate">Client Rate: </label>
                          </div>
                          <input id="crate" class="form-control" type="number" min="0" value="" name="client_rate" />
                        </div>
                        <div class="form-group row">
                          <div style="margin-bottom:15px;">
                          <label for="srate">Submission Rate: </label>
                          <input id="srate"  class="form-control" type="number" min="0" value="" name="submission_rate" />
                        </div>
                        <input id="login-button" style="margin: 0px 0px 15px 20px;" class="btn btn-primary btn-white" type="submit" value="Submit">
                        <button type="reset" value="Reset" style="margin-right:20px;" class="btn btn-secondary btn-white pull-right">Reset</button>
                        </form>
                      </div>
                    </div>
                  </div>

                  @if (count($candidate_resumes) > 0)
                    <div>
                       
                      {{ $candidate_resumes->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          <div style="color:red; display:none;" id="errormsg">Please select candidate</div>
          <div class="form-group row" style="display:block;">
              <div class="col-sm-12">
                <form name="candidate_mass_mail" method="post" action="/mass-mail">
                  <input type="hidden" name="candidate_list" value="" />
                  <input type="submit" value="Send Mass Mail" id="login-button" class="btn
                  btn-info pull-right">
                </form>
              </div>
         </div>
        </section><!-- /.content -->
@stop
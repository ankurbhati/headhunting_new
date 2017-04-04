@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title col-sm-9">Search Results for <strong style="background-color:#d8d8d8; padding:0 15px;">{{$searching_text}}</strong></h3>
                  <div class="col-sm-3">
                				<a class="btn btn-primary pull-right" href="{{ URL::route('advance-search', array($jobId)) }}">
                					<i class="fa fa-search"></i> <span>Back To Search Candidate</span>
                				</a>
                	</div>
                </div><!-- /.box-header -->
                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$candidate_resumes->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" name="checkall" value="">Check All</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Added At</th>
                        <th>Resume</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($candidate_resumes as $candidate)
		                      <tr>
                            <td><input type="checkbox" class="checkcandidate" value="{{$candidate->candidate->id}}"></td>
                            <td>
                                {{$candidate->candidate->first_name." ".$candidate->candidate->last_name}}
                            </td>
                            <td>
                                {{$candidate->candidate->email}}
                            </td>
                            <td>{{($candidate->candidate->created_at != "" && $candidate->candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->candidate->created_at)):"-"}}</td>
                            <td>
                                <p>{{$candidate->resume?'"'.str_replace("<br />", "", substr($candidate->resume, 0, 80)).'..."':""}}</p>
                            </td>
		                        <td>
		                        	<a href="{{ URL::route('view-candidate', array('id' => $candidate->candidate_id, 'jobId' => $jobId, 'searchingText' => $searching_text_to_send)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i>View </a>
                              @if(Auth::user()->getRole() <= 3 && $candidate->resume_path && file_exists(public_path('/uploads/resumes/'.$candidate->candidate_id.'/'.$candidate->resume_path)))
                              | <a href="{{'/uploads/resumes/'.$candidate->candidate_id.'/'.$candidate->resume_path}}" title="Download Resume"><i class="glyphicon glyphicon-download"></i>Download</a>
                              @endif
                              @if($jobId > 0)
                               | <a href="{{ URL::route('job-submittel', array('jobId' => $jobId, 'userId' => $candidate->candidate_id)) }}" title="Mark Submittel"><i class="fa fa-fw fa-save"></i>Submittel</a>

                              <a href="javascript:void(0);" data-url="{{ URL::route('job-submittel', array('jobId' => $jobId, 'userId' => $candidate->candidate_id)) }}" class="updatejobsubmittle" title="Mark Submittel" title="Update Status">
                                  <i class="fa fa-fw fa-save"></i>Submittel
                              </a>

                              @endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Candidate</p>
						@endforelse
                    </tbody>
                    <tfoot>
                        <th><input type="checkbox" name="checkall" value="">Check All</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Added At</th>
                        <th>Resume</th>
                        <th>Action</th>
                    </tfoot>
                  </table>

                  <div id="myModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                      <div class="modal-header" style="margin-bottom: 5px">
                        <span class="closemodal">&times;</span>
                        <h4>Job Submittel</h4>
                      </div>
                      <div class="modal-body">
                        <form method="post" action="" name="model-form">
                        <div id="modal-form-content"></div>
                        <div>
                          <label>Client Rate: </label><input type="number" min="0" value="" name="client_rate" />
                        </div>
                        <div>
                          <label>Submission Rate: </label><input type="number" min="0" value="" name="submission_rate" />
                        </div>
                        <input id="login-button" style="margin: 0px 0px 15px 20px" class="btn btn-primary btn-white" type="submit" value="Submit">
                        <button type="reset" value="Reset" style="float: right;" class="btn btn-primary btn-white">Reset</button>
                        </form>
                      </div>
                    </div>
                  </div>

                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total results found :  <span class="text-bold">{{$candidate_resumes->getTotal()}}</span></p>
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

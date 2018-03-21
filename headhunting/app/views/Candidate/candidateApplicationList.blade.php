@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Candidate Application List for "{{$candidateApplication[0]->candidate->first_name . " ". $candidateApplication[0]->candidate->last_name}}"</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                <div>
                    <p class="result-total"><span class="text-bold">{{count($candidateApplication)}} results :</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Candidate Email<br/>Full Name</th>
                        <th>Job Id<br/>
                            Job Title</th>
                        <th>Submitted By</th>
                        <th>Client Rate</th>
                        <th>Recruter's Rate</th>
                        <th>Submission Status</th>

                        <th>Submitted on</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @foreach($candidateApplication as $app)
		                      <tr>
                                <td><a href="{{ URL::route('view-candidate', array('id' => $app->candidate->id)) }}" target="_blank">{{$app->candidate->email}}</a><br/>
                                {{$app->candidate->first_name . ' '.$app->candidate->last_name }}</td>
                                 <td><a target="_blank" title="View Requirement" href="{{ URL::route('view-requirement', array('id' => $app->requirement->id)) }}">APT-0{{$app->requirement->id}}</a><br/>
                                 {{$app->requirement->title}}</td>
                                  <td>{{$app->submittedBy->first_name . ' '.$app->submittedBy->last_name}}</td>
                                  <td>$ {{$app->client_rate}}</td>
                                  <td>$ {{$app->submission_rate}}</td>
                                   <td class="text-status-{{$app->status}}">
                                    {{$submittle_status[$app->status]}}
                                    @if($app->status == array_search('Interview Scheduled', $submittle_status) && !empty($app->interview_scheduled_date))
                                        {{'('.(date("Y-m-d", strtotime($candidateApplication->interview_scheduled_date))).')'}}
                                    @endif
                                    </td>
                                    <td>
                                    {{($app->created_at != "" && $app->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($app->created_at)):"-"}}
                                    </td>
		                      </tr>
        				@endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Candidate Email<br/>Full Name</th>
                        <th>Job Id<br/>
                            Job Title</th>
                        <th>Submitted By</th>
                        <th>Client Rate</th>
                        <th>Recruter's Rate</th>
                        <th>Submission Status</th>

                        <th>Submitted on</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
@stop
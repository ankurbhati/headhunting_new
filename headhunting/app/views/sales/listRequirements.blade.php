@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Job Postings</h3>
                </div><!-- /.box-header -->




                  {{ Form::open(array('class' =>
  'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
                  <div class="form-group">
                      {{ Form::label('title', 'Job Title: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('title', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Job Title')); }}
                          <span class='errorlogin email-login'>{{$errors->first('title');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  <div class="form-group">
                      {{ Form::label('type_of_employment', 'Type Of Employment: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::select('type_of_employment', array(0=>"Select type of employment", 1=>"Contratual", 2=> "Permanent", 3=>"Contract to hire"),"", array('class' => 'form-control')) }}
                          <span class='errorlogin'>{{$errors->first('type_of_employment');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>

                  <div class="form-group row ">
                      <div class="col-sm-11" style="text-align:center;">{{ Form::submit('Search', array('class' => 'btn
                          btn-info', 'id' => 'requirement-button') ); }}</div>

                 </div>

              {{ Form::close() }}




                <div class="box-body">
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Job ID</th>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Added At</th>
                        <th>City, Country</th>
                        <th>Client</th>
                        <th>Added By</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($jobPost as $jobPosts)
		                      <tr>
                              <td>{{$jobPosts->id}}</td>
              		            <td>{{$jobPosts->title}}</td>
              								<td>{{($jobPosts->type_of_employment == 1)?"Contractual": ($jobPosts->type_of_employment == 2)?"Permanent": "Contract to hire";}}</td>
              								<td>{{($jobPosts->created_at != "" && $jobPosts->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($jobPosts->created_at)):"-"}}</td> 
                              <td>{{$jobPosts->city->name}}, {{$jobPosts->country->country}}</td>
                              <td>@if($jobPosts->client){{$jobPosts->client->first_name." ".$jobPosts->client->last_name."-".$jobPosts->client->email}}@else {{"-"}} @endif</td>
              								<td>@if($jobPosts->user){{$jobPosts->user->first_name." ".$jobPosts->user->last_name."-".$jobPosts->user->email}}@else {{"-"}} @endif</td>
              								<td>{{($jobPosts->status == 2)?"Closed":"Open";}}</td>
              		                        <td>
		                        	<a href="{{ URL::route('view-requirement', array('id' => $jobPosts->id)) }}" title="View Job Post"><i class="fa fa-fw fa-eye"></i></a>
                              @if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3)) && !empty($jobPosts) && Auth::user()->id == $jobPosts->created_by) 
		                        	  <a href="{{ URL::route('edit-requirement', array($jobPosts->id)) }}" title="Edit Job Post"><i class="fa fa-fw fa-edit"></i></a>
                              @endif
                              @if($id == 0 && $jobPosts->jobsAssignedToMe()->count() == 0)
		                        		<!--<a href="{{ URL::route('assign-requirement', array($jobPosts->id)) }}" title="Assign To me"><i class="fa fa-plus"></i></a>-->
                              @else
                                <a href="{{ URL::route('advance-search', array($jobPosts->id)) }}" title="Search Candidate"><i class="fa fa-search"></i></a>
		                        	@endif
		                        	@if(Auth::user()->getRole() <= 2)
		                        		<a href="{{ URL::route('delete-requirement', array($jobPosts->id)) }}" title="Delete Job Post"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
                              <a href="{{ URL::route('add-comment-job-post-view', array($jobPosts->id)) }}" title="Add Comments"><i class="fa fa-fw fa-edit"></i></a>
                              @if(Auth::user()->hasRole(3))
                              <a href="{{ URL::route('peers', array($jobPosts->id)) }}" title="Assign To Peers"><i class="fa fa-plus"></i> Assign To Peers</a>
                              @endif

                              @if($jobPosts->status == 1 && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3)))
                                <a href="{{ URL::route('close-requirement', array($jobPosts->id)) }}" title="Close Job Post"><i class="fa fa-fw fa-minus"></i></a>
                              @endif
                              @if($jobPosts->status == 2 && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3)))
                                <a href="{{ URL::route('reopen-requirement', array($jobPosts->id)) }}" title="Reopen Job Post"><i class="fa fa-fw fa-plus"></i></a>
                              @endif

		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Job Posts</p>
						@endforelse

                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Job ID</th>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Added At</th>
                        <th>City, State, Country</th>
                        <th>Client</th>
                        <th>Added By</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($jobPost) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $jobPost->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

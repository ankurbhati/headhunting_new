@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view user-view">
	<div class="box col-sm-6">
		<div class="box-heading">Job Post Details</div>
		<div class="box-view">
	    <div class="row"><div class="col-sm-4 view-label">
	        Job Id:
	        </div><div class="col-sm-8 view-value ">
	        		APT-0{{$jobPost->id}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Title:
	        </div><div class="col-sm-8 view-value ">
	        		{{$jobPost->title}}
	        </div>
	    </div>
	    	<div class="row"><div class="col-sm-4 view-label">
					End Client:
					</div><div class="col-sm-8 view-value ">
						{{$jobPost->end_client!=''?$jobPost->end_client:"-"}}
					</div>
			</div>
			<div class="row"><div class="col-sm-4 view-label">
					Client:
					</div><div class="col-sm-8 view-value ">
						@if($jobPost->client && ($jobPost->created_by == Auth::user()->id || Auth::user()->hasRole(1))){{$jobPost->client->first_name." ".$jobPost->client->last_name."-".$jobPost->client->email}}@else {{"*****"}} @endif
					</div>
			</div>
			<div class="row"><div class="col-sm-4 view-label">
					Type Of Employment:
					</div><div class="col-sm-8 view-value ">
						{{($jobPost->type_of_employment == 1)?"Contractual": (($jobPost->type_of_employment == 2)?"Permanent": "Contract to hire");}}
					</div>
			</div>
			@if($jobPost->type_of_employment != 2)
			<div class="row"><div class="col-sm-4 view-label">
					Duration:
					</div><div class="col-sm-8 view-value ">
						{{($jobPost->duration) ? $jobPost->duration . " months" : "-"}}
					</div>
			</div>
			@endif
			<div class="row"><div class="col-sm-4 view-label">
					Mode Of Interview:
					</div><div class="col-sm-8 view-value ">
						{{$jobPost->mode_of_interview}}
					</div>
			</div>
		</div>
	</div>
	<div class="box col-sm-6">
			<div class="box-heading">Job Post Details</div>
			<div class="box-view">
			<div class="row"><div class="col-sm-4 view-label">
					Rate:
					</div><div class="col-sm-8 view-value ">
						{{$jobPost->rate}}
					</div>
			</div>
	    <div class="row"><div class="col-sm-4 view-label">
	        City:
	        </div><div class="col-sm-8 view-value ">
					{{$jobPost->city->name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        State:
	        </div><div class="col-sm-8 view-value ">
						{{$jobPost->state->state}}
	        </div>
	    </div>
			<div class="row"><div class="col-sm-4 view-label">
					Country:
					</div><div class="col-sm-8 view-value ">
						{{$jobPost->country->country}}
					</div>
			</div>

			<div class="row"><div class="col-sm-4 view-label">
					Status:
					</div><div class="col-sm-8 view-value ">
						{{($jobPost->status == 3)?"Closed"."-".$feedbacks[$jobPost->feedback]:"Open";}}
					</div>
			</div>
			</div>
			</div>

	<div class="box col-xs-12">
		<div class="box-heading">Description</div>
		<div class="box-view">
			<div class="row">
	        	<div class="col-sm-12">
						{{$jobPost->description}}
	        	</div>
	    	</div>
	    	</div>
	    	</div>
	<div class="box col-sm-12">
		<div class="box-heading">Job Post Comments</div>
		<div class="box-view">
        	@forelse($jobPost->comments as $comment)
        	<div class="row">
        		<div class="col-xs-12">
					<span class="view-label">{{$comment->user->first_name." ".$comment->user->last_name}} - 
					</span>
					<span class="view-value">{{$comment->comment}}</span>
					<span class="pull-right view-label">Posted At: {{date('Y M, d(D) H:i', strtotime($comment->created_at))}}</span>
				</div>
			</div>
			@empty
				<div class="row">
           			<p class="view-label">No Comments Yet</p>
           		</div>
			@endforelse
	   	</div>
	   		{{ Form::open(array('route' => array('add-comment-job-post', $jobPost->id, 1), 'class' =>
	'form-horizontal','id' => 'comment-form',  'method' => 'POST')) }}
		<div class="form-group">
				{{ Form::label('title', 'Add More Comment: ', array('class' => 'col-sm-12')); }}
				<div class="col-sm-12">
					{{ Form::textarea('comment', '', array('class' =>	'form-control', 'placeholder' => 'Enter Comment', 'required')); }}
					{{ Form::hidden('job_post_id', $jobPost->id); }}
					<span class='errorlogin email-login'>{{$errors->first('comment');}}@if(!empty($message)){{$message}}@endIf</span>
				</div>
		</div>
    <div class="form-group row ">
        <div class="col-sm-12">{{ Form::submit('Add Comment', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>
   </div>
	 {{ Form::close() }}
	</div>

	<div class="box col-xs-12">
	<div class="box-action">
				<a class="btn btn-primary btn-white" href="{{ URL::route('advance-search', array($jobPost->id)) }}">
					Search Candidate</span>
				</a>
				@if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) 
               		<a class="btn btn-primary btn-white" href="{{ URL::route('edit-requirement', array($jobPost->id)) }}" title="Edit Job Post">Edit Requirement</a>
	           	@endif
                @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                    <a class="btn btn-secondary btn-white" href="{{ URL::route('delete-requirement', array($jobPost->id)) }}" title="Delete Job Post">
                    	Delete
                    </a>
                 @endif
                @if( $jobPost->status == 2  && (Auth::user()->hasRole(3) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8) || Auth::user()->hasRole(1)) )
                  <a class="btn btn-primary btn-white" href="{{ URL::route('peers', array($jobPost->id)) }}" title="Assign To Peers">
                    Assign To
                  </a>
                @endif
                @if($jobPost->status ==1  && ( Auth::user()->hasRole(3) || Auth::user()->hasRole(1) ) )
                  <a class="btn btn-primary btn-white" href="{{ URL::route('approve-requirement', array($jobPost->id)) }}" title="Approve Job Post">
                    Approve
                  </a>
                @endif
                @if($jobPost->status !=3  && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                  <a  class="btn btn-primary btn-white" href="{{ URL::route('close-requirement', array($jobPost->id)) }}" title="Close Job Post">
                    Close
                  </a>
                @endif
                @if($jobPost->status == 3 && (Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1)))
                  <a  class="btn btn-primary btn-white" href="{{ URL::route('reopen-requirement', array($jobPost->id)) }}" title="Reopen Job Post">
                    Reopen
                  </a>
                @endif


			</div>
		</div>
	</div>
@stop

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
					Client:
					</div><div class="col-sm-8 view-value ">
						{{$jobPost->client->first_name."-".$jobPost->client->email}}
					</div>
			</div>
			<div class="row"><div class="col-sm-4 view-label">
					Type Of Employment:
					</div><div class="col-sm-8 view-value ">
						{{($jobPost->type_of_employment == 1)?"Contractual": ($jobPost->type_of_employment == 2)?"Permanent": "Contract to hire";}}
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
	</div>
	<div class="box col-xs-12">
	<div class="box-action">
				<a class="btn btn-primary btn-white" href="{{ URL::route('advance-search', array($jobPost->id)) }}">
					Search Candidate</span>
				</a>
				@if((Auth::user()->hasRole(1) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8)) && !empty($jobPost) && Auth::user()->id == $jobPost->created_by) 
               		<a class="btn btn-primary btn-white" href="{{ URL::route('edit-requirement', array($jobPost->id)) }}" title="Edit Job Post">Edit Requirement</a>
            	@endif
				@if($jobPost->jobsAssignedToMe()->count() == 0)
					<a class="btn btn-primary btn-white" href="{{ URL::route('assign-requirement', array($jobPost->id)) }}" title="Assign To me">Assign To Me</a>
				@endif
				@if(Auth::user()->getRole() <= 2 || Auth::user()->hasRole(8))
					<a class="btn btn-primary btn-white" href="{{ URL::route('delete-requirement', array($jobPost->id)) }}" title="Delete Job Post"></i>Remove Job Post</a>
				@endif
			</div>
		</div>
	</div>
@stop

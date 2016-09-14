@extends('layouts.adminLayout')
@section('content')
<div class="user-view">
	@if($jobId > 0)
	<div class="col-sm-2">
				<a class="btn btn-primary" href="{{ URL::route('job-submittel', array('jobId' => $jobId, 'userId' => $candidate->id)) }}">
					<i class="fa fa-save"></i> <span>Mark Submittel</span>
				</a>
	</div>
	@endif
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Email:
				</div><div class="col-sm-8">
	        	{{$candidate->email}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        First Name:
	        </div><div class="col-sm-8">
	        	{{$candidate->first_name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Name:
	        </div><div class="col-sm-8">
	        	{{$candidate->last_name}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Phone:
	        </div><div class="col-sm-8">
	        	{{$candidate->phone}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Date Of Birth:
	        </div><div class="col-sm-8">
	        	{{$candidate->dob}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        City:
	        </div><div class="col-sm-8">
	        	@if($candidate->city){{$candidate->city->name}}@else{{"-"}}@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        State:
	        </div><div class="col-sm-8">
	        	@if($candidate->state){{$candidate->state->state}}@else{{"-"}}@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Country:
	        </div><div class="col-sm-8">
	        	@if($candidate->country){{$candidate->country->country}}@else{{"-"}}@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Zipcode:
	        </div><div class="col-sm-8">
	        	{{$candidate->zipcode}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Address:
	        </div><div class="col-sm-8">
	        	{{$candidate->address}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Ssn:
	        </div><div class="col-sm-8">
	        	{{$candidate->ssn}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Work State:
	        </div><div class="col-sm-8">
	        	@if($candidate->workstate){{$candidate->workstate->title}}@else{{"-"}}@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Visa:
	        </div><div class="col-sm-8">
	        	@if($candidate->visa){{$candidate->visa->title}}@else{{"-"}}@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Visa Expiry:
	        </div><div class="col-sm-8">
	        	{{($candidate->visa_expiry != "" && $candidate->visa_expiry != "0000-00-00")?date("Y-m-d", strtotime($candidate->visa_expiry)):"-"}}
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4">
	        Created By:
	        </div><div class="col-sm-8">
	        	{{$candidate->createdby->first_name. " ".$candidate->createdby->last_name }}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($candidate->created_at != "" && $candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Updated At:
	        </div><div class="col-sm-8">
	        	{{($candidate->updated_at != "" && $candidate->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->updated_at)):"-"}}
	        </div>
	    </div>
			@if($resume)
				<div class="row">
					<div class="col-sm-4">
						<strong>Resume:</strong>
						 <a href="{{'/uploads/resumes/'.$resume->candidate_id.'/'.$resume->resume_path}}" title="Download Resume"><i class="glyphicon glyphicon-download"></i> Download Resume</a>
					</div>
				</div>
        	<div class="row">
			    <div class="col-sm-12" style="border:1px dashed #d8d8d8; margin-top:20px; padding:25px 10px;">
			        	{{htmlspecialchars_decode($resume->resume)}}
			    </div>
	    	</div>
        @else
        @endif
	</div>
</div>
@stop

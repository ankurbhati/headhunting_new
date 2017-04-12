@extends('layouts.adminLayout')
@section('content')

	<div class="row detail-view search-view-user">
	<!-- @if($jobId > 0)
			<div class="col-sm-2">
				<a class="btn btn-primary btn-white" href="{{ URL::route('job-submittel', array('jobId' => $jobId, 'userId' => $candidate->id)) }}">
					<span>Mark Submittel</span>
				</a>
			</div>
		@endif -->
	<div class="box col-sm-6">
		<div class="box-heading">Candidate Details</div>
		<div class="box-view">
		    <div class="row"><div class="col-sm-4 view-label">

		        Email:
					</div><div class="col-sm-8 view-value">
		        	{{$candidate->email}}
		        	<input type="hidden" value="{{$searchingText}}" id="searchedValue">
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        First Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$candidate->first_name}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Last Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$candidate->last_name}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        Phone:
		        </div><div class="col-sm-8 view-value">
		        	{{$candidate->phone}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Job Title:
		        </div><div class="col-sm-8 view-value">
		        	{{$candidate->designation}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Rate:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->candidaterate){{"$".$candidate->candidaterate->value}}@else{{"-"}}@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Work State:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->workstate){{$candidate->workstate->title}}@else{{"-"}}@endif
		        </div>
		    </div>
		    </div>
		    </div>
		    <div class="box col-sm-6">
		<div class="box-heading">Candidate Address</div>
		<div class="box-view">
		    <div class="row"><div class="col-sm-4 view-label">
		        City:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->city){{$candidate->city->name}}@else{{"-"}}@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        State:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->state){{$candidate->state->state}}@else{{"-"}}@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Country:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->country){{$candidate->country->country}}@else{{"-"}}@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Third Party:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->thirdparty){{$candidate->thirdparty->email}}@else{{"-"}}@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Visa:
		        </div><div class="col-sm-8 view-value">
		        	@if($candidate->visa){{$candidate->visa->title}}@else{{"-"}}@endif
		        </div>
		    </div>
			<div class="row"><div class="col-sm-4 view-label">
		        Created By:
		        </div><div class="col-sm-8 view-value">
		        	{{$candidate->createdby->first_name. " ".$candidate->createdby->last_name }}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Created At:
		        </div><div class="col-sm-8 view-value">
		        	{{($candidate->created_at != "" && $candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->created_at)):"-"}}
		        </div>
		    </div>
		    </div>
		    </div>
			@if($resume)
			<div class="box col-sm-12">
				<div class="box-heading">Candidate Resume</div>
				<div class="box-view">
	        	<div class="row">
				    <div class="col-sm-12 view-value">

		        		<a href="{{'/uploads/resumes/'.$resume->candidate_id.'/'.$resume->resume_path}}" class="btn btn-primary btn-white pull-right" title="Download Resume"><i class="glyphicon glyphicon-download"></i> Download Resume</a>

				        {{htmlspecialchars_decode($resume->resume)}}
				    </div>
		    	</div>
		    	</div>
		    	</div>
	        @endif
		</div>
	</div>
@stop


@if(trim($searchingText))
	<script>
		//var searchingText = {{"'".trim($searchingText)."'"}};
		//function replaceText() {
		/*    var jthis = $(this);
		    $("*").each(function() {
		    	//console.log(jthis); 
		        if(jthis.children().length==0 && jthis.text() != "") {
		        	console.log(searchingText);
		        	console.log(jthis.text());
		            jthis.text(jthis.text().replace("t", 'AAA:')); 
		        } 
		    });
		}
		*/
	</script>
@endif
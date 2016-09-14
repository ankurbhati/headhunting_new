@extends('layouts.adminLayout')
@section('content')
<div class="row user-view">
	<div class="col-sm-1">
	</div>
	<div class="col-sm-10 right-view">
	    <div class="row"><div class="col-sm-4">
	        Title:
	        </div><div class="col-sm-8">
	        		{{$jobPost->title}}
	        </div>
	    </div>
			<div class="row"><div class="col-sm-4">
					Client:
					</div><div class="col-sm-8">
						{{$jobPost->client->first_name."-".$jobPost->client->email}}
					</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
		        Vendor:
		        </div>
		        <div class="col-sm-8">
					{{$jobPost->vendor->vendor_domain."-".$jobPost->vendor->email}}
	    	    </div>
	    	</div>
			<div class="row"><div class="col-sm-4">
					Rate:
					</div><div class="col-sm-8">
						{{$jobPost->rate}}
					</div>
			</div>
	    <div class="row"><div class="col-sm-4">
	        City:
	        </div><div class="col-sm-8">
					{{$jobPost->city->name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        State:
	        </div><div class="col-sm-8">
						{{$jobPost->state->state}}
	        </div>
	    </div>
			<div class="row"><div class="col-sm-4">
					Country:
					</div><div class="col-sm-8">
						{{$jobPost->country->country}}
					</div>
			</div>
			<div class="row"><div class="col-sm-4">
	        Description:
	        </div><div class="col-sm-8">
						{{$jobPost->description}}
	        </div>
	    </div>
			<div class="row" style="padding-top:15px; padding-bottom:15px;">
					<div class="col-sm-3">
						<a class="btn btn-primary" href="{{ URL::route('advance-search', array($jobPost->id)) }}">
							<i class="fa fa-search"></i> <span>Search Candidate</span>
						</a>
					</div>
					<div class="col-sm-3">
						<a class="btn btn-info" href="{{ URL::route('edit-requirement', array($jobPost->id)) }}" title="Edit Job Post"><i class="fa fa-fw fa-edit"></i> Edit Requirement</a>
					</div>
						@if($jobPost->jobsAssignedToMe()->count() == 0)
							<div class="col-sm-3">
								<a class="btn btn-primary" href="{{ URL::route('assign-requirement', array($jobPost->id)) }}" title="Assign To me"><i class="fa fa-plus"></i> Assign To Me</a>
							</div>
						@endif
						@if(Auth::user()->getRole() <= 2)
							<div class="col-sm-3">
								<a class="btn btn-warning" href="{{ URL::route('delete-requirement', array($jobPost->id)) }}" title="Delete Job Post"><i class="fa fa-fw fa-ban text-danger"></i>Remove Requirement</a>
							</div>
						@endif
				</div>
			</div>
	</div>
</div>
@stop

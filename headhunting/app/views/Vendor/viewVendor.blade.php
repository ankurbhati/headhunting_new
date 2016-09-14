@extends('layouts.adminLayout')
@section('content')
<div class="row user-view">
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Email:
	        </div><div class="col-sm-8">
	        	{{$vendor->email}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Vendor Domain:
	        </div><div class="col-sm-8">
	        	{{$vendor->vendor_domain}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Phone:
	        </div><div class="col-sm-8">
	        	{{$vendor->phone}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Is Partner:
	        </div><div class="col-sm-8">
	        	@if($vendor->partner)
                	Yes
                @else
                	No
                @endif
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4">
	        Created By:
	        </div><div class="col-sm-8">
	        	{{$vendor->createdby->first_name. " ".$vendor->createdby->last_name }}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($vendor->created_at != "" && $vendor->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($vendor->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Updated At:
	        </div><div class="col-sm-8">
	        	{{($vendor->updated_at != "" && $vendor->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($vendor->updated_at)):"-"}}
	        </div>
	    </div>
	</div>
</div>
@stop

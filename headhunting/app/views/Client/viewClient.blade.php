@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view requirement-view">
    <div class="box col-sm-6">
        <div class="box-heading">Client Details</div>
        <div class="box-view">
		    <div class="row"><div class="col-sm-4 view-label">
		        Email:
		        </div><div class="col-sm-8 view-value">
		        	{{$client->email}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        First Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$client->first_name}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Last Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$client->last_name}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        Company:
		        </div><div class="col-sm-8 view-value">
		        	{{$client->company_name}}
		        </div>
		    </div>
			<div class="row"><div class="col-sm-4 view-label">
		        Created By:
		        </div><div class="col-sm-8 view-value">
		        	{{$client->createdby->first_name. " ".$client->createdby->last_name }}
		        </div>
		    </div>
		    </div>
		    </div>
	<div class="box col-sm-6">
        <div class="box-heading">Client Details</div>
        <div class="box-view">

	    <div class="row"><div class="col-sm-4 view-label">
	        Phone:
	        </div><div class="col-sm-8 view-value">
	        	{{$client->phone}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Phone Extension:
	        </div><div class="col-sm-8 view-value">
	        	{{$client->phone_ext}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Created At:
	        </div><div class="col-sm-8 view-value">
	        	{{($client->created_at != "" && $client->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Last Updated At:
	        </div><div class="col-sm-8 view-value">
	        	{{($client->updated_at != "" && $client->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->updated_at)):"-"}}
	        </div>
	    </div>
	    </div>
	    </div>
	</div>
@stop

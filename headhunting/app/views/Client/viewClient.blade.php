@extends('layouts.adminLayout')
@section('content')
<div class="row user-view">
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Email:
	        </div><div class="col-sm-8">
	        	{{$client->email}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        First Name:
	        </div><div class="col-sm-8">
	        	{{$client->first_name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Name:
	        </div><div class="col-sm-8">
	        	{{$client->last_name}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Company:
	        </div><div class="col-sm-8">
	        	{{$client->company->company_name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Phone:
	        </div><div class="col-sm-8">
	        	{{$client->phone}}
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4">
	        Created By:
	        </div><div class="col-sm-8">
	        	{{$client->createdby->first_name. " ".$client->createdby->last_name }}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($client->created_at != "" && $client->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Updated At:
	        </div><div class="col-sm-8">
	        	{{($client->updated_at != "" && $client->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->updated_at)):"-"}}
	        </div>
	    </div>
	</div>
</div>
@stop

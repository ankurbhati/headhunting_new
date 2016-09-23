@extends('layouts.adminLayout')
@section('content')
<div class="row user-view" style="margin-top:0px;">
	<div class="col-sm-5 right-view" style="padding-left:15px;">
	    <div class="row"><div class="col-sm-4">
	        Email:
	        </div><div class="col-sm-8">
	        	{{$user->email}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        First Name:
	        </div><div class="col-sm-8">
	        	{{$user->first_name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Name:
	        </div><div class="col-sm-8">
	        	{{$user->last_name}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Designation:
	        </div><div class="col-sm-8">
	        	{{$user->designation}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Status:
	        </div><div class="col-sm-8">
	        	{{($user->status == 1)?"Active":"Deactive"}}
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4">
	        Date Of Joining:
	        </div><div class="col-sm-8">
	        	{{($user->doj != "" && $user->doj != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($user->doj)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Date Of Release:
	        </div><div class="col-sm-8">
	        	{{($user->dor != "" && $user->dor != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($user->dor)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Role:
	        </div><div class="col-sm-8">
	        	{{$user->userRoles[0]->roles->role}}
	        </div>
	    </div>
	    </div>
	    <div class="col-sm-7 right-view" style="border-left:1px solid #000; padding-left:15px;">
	    <div class="row">
	    <div class="col-sm-4">
	        Address
	        </div>
        <div class="col-sm-8">

        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Country:
	        </div><div class="col-sm-8">
	        	{{isset($user->country)?$user->country->country:""}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        State:
	        </div><div class="col-sm-8">
	        	{{(isset($user->state))?$user->state->state:''}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        City:
	        </div><div class="col-sm-8">
	        	{{$user->city}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Address:
	        </div><div class="col-sm-8">
	        	{{($user->address != "")?$user->address:"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Zipcode:
	        </div><div class="col-sm-8">
	        	{{($user->zipcode != "")?$user->zipcode:"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Phone:
	        </div><div class="col-sm-8">
	        	{{$user->phone_no}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Phone Extension:
	        </div><div class="col-sm-8">
	        	{{$user->phone_ext}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Gender:
	        </div><div class="col-sm-8">
	        	{{($user->gender == 0)?"Female":"Male"}}
	        </div>
	    </div>
	</div>
</div>
@stop
@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view user-view">
	<div class="box col-sm-5">
		<div class="box-heading">Employee Details</div>
		<div class="box-view">
		    <div class="row">
		    	<div class="col-sm-4 view-label">
		        	Email:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->email}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        	First Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->first_name}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        	Last Name:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->last_name}}
		        </div>
		    </div>
		    <div class="row">
		    	<div class="col-sm-4 view-label">
		        	Designation:
		        </div>
		        <div class="col-sm-8 view-value">
		        	{{$user->designation}}
		        </div>
		    </div>
			<div class="row">
				<div class="col-sm-4 view-label">
		        	Date Of Joining:
		        </div>
		        <div class="col-sm-8 view-value">
		        	{{($user->doj != "" && $user->doj != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($user->doj)):"-"}}
		        </div>
		    </div>
		    <div class="row">
		    	<div class="col-sm-4 view-label">
		        	Date Of Release:
		        </div>
		        <div class="col-sm-8 view-value">
		        	{{($user->dor != "" && $user->dor != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($user->dor)):"-"}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Gender:
		        </div><div class="col-sm-8 view-value">
		        	{{($user->gender == 0)?"Female":"Male"}}
		        </div>
			</div>
			@if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
			    <div class="row">
			    	<div class="col-sm-4 view-label">
			        	Role:
			        </div>
			        <div class="col-sm-8 view-value">
			        	{{$user->userRoles[0]->roles->role}}
			        </div>
			    </div>
		    @endif
		  </div>
	  </div>
	  <div class="col-sm-7 box">
	  		<div class="box-heading">Employee Address</div>
			<div class="box-view">
		    <div class="row"><div class="col-sm-4 view-label">
		        Country:
		        </div><div class="col-sm-8 view-value">
		        	{{isset($user->country)?$user->country->country:""}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        State:
		        </div><div class="col-sm-8 view-value">
		        	{{(isset($user->state))?$user->state->state:''}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        City:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->city}}
		        </div>
		    </div>

		    <div class="row"><div class="col-sm-4 view-label">
		        Address:
		        </div><div class="col-sm-8 view-value">
		        	{{($user->address != "")?$user->address:"-"}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Zipcode:
		        </div><div class="col-sm-8 view-value">
		        	{{($user->zipcode != "")?$user->zipcode:"-"}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Phone:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->phone_no}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Phone Extension:
		        </div><div class="col-sm-8 view-value">
		        	{{$user->phone_ext}}
		        </div>
		    </div>
		    <div class="row">
		    	<div class="col-sm-4 view-label">
		        	Status:
		        </div>
		        <div class="col-sm-8 view-value">
		        	{{($user->status == 1)?"Active":"Deactive"}}
		        </div>
		    </div>
		</div>
	</div>
	<div class="box col-xs-12">
		<div class="box-action">
	    	<a href="{{ URL::route('edit-member', array($user->id)) }}" title="Edit Profile" class="btn btn-primary btn-white">Edit</a>
	    	@if((Auth::user()->getRole() == 1 || Auth::user()->hasRole(8)) && Auth::user()->id != $user->id)
	    		<a href="{{ URL::route('delete-member', array($user->id)) }}" title="Delete Profile" class="btn btn-primary btn-white">Delete</a>
	    	@endif
	    	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
	    		<a href="{{ URL::route('change-password', array('id' => $user->id)) }}" title="Change Password" class="btn btn-primary btn-white">Change Password</a>
	    	@endif
		</div>
	</div>
</div>
@stop
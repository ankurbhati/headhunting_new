@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        @if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
        {{ Form::text('email', $user->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Your Email', 'required')); }}
        @else
             {{ Form::text('email', $user->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Your Email', 'readonly')); }}
        @endif
        <span class='errorlogin'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('first_name', $user->first_name, array('class' =>
            'form-control', 'placeholder' => 'Enter Employee First Name', 'required')); }} 
            <span class='errorlogin'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('last_name', $user->last_name, array('class' =>
            'form-control', 'placeholder' => 'Enter Employee Last Name', 'required')); }} 
            <span class='errorlogin'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('designation', 'Designation: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('designation', $user->designation, array('class' =>
            'form-control', 'placeholder' => 'Enter Employee Designation')); }}  
            <span class='errorlogin'>{{$errors->first('designation');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    @if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
    <div class="form-group">
        {{ Form::label('doj', 'Date Of Joining: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('doj', (($user->doj != "" && $user->doj != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($user->doj)):""), array('class' =>
            'form-control date-mask', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'dd/mm/yyyy'", 'data-mask')); }} 
            <span class='errorlogin'>{{$errors->first('doj');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('dor', 'Date Of Release: ', array('class' => 'col-sm-3 date-mask
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('dor', (($user->dor != "" && $user->dor != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($user->dor)):""), array('class' =>
            'form-control', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'dd/mm/yyyy'", 'data-mask')); }} 
            <span class='errorlogin'>{{$errors->first('dor');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('status', 'Status: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('status', array(1  =>'Active', 2=> 'Decativate'), $user->status, array('class' => 'form-control')) }} 
            <span class='errorlogin'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('roles', 'Roles: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('roles', $roles, $user->getRole(), array('class' => 'form-control', 'required')) }} 
            <span class='errorlogin'>{{$errors->first('roles');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <input type="hidden" value="{{$user->getRole()}}" id="role">
    @endif
    <div class="form-group">
        {{ Form::label('phone_no', 'Phone: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('phone_no', $user->phone_no, array('class' =>
            'form-control', 'placeholder' => 'Enter Employee Phone Number', 'required', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
            <span class='errorlogin'>{{$errors->first('phone_no');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', $user->phone_ext, array('class' => 'form-control', 'placeholder' => 'ex. (121)')); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
        <div class="form-group">
        {{ Form::label('country_id', 'Country: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('country_id', $country, $user->country_id, array('class' => 'form-control', 'id' => 'country_id')) }} 
            <span class='errorlogin'>{{$errors->first('country_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('state_id', 'State: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('state_id', array('' => "Select State"), "", array('class' => 'form-control', 'id' => 'state_id')) }} 
            <span class='errorlogin'>{{$errors->first('state_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <input type="hidden" value="{{$user->state_id}}" id="state">
    </div>

    <div class="form-group">
        {{ Form::label('city', 'City: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('city',$user->city, array('class' => 'form-control', 'placeholder' => 'Enter City')); }} 
        </div>
    </div>
	<div class="form-group">
        {{ Form::label('address', 'Address: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('address',$user->address, array('class' => 'form-control', 'placeholder' => 'ex. Hno: 1, Jor Bagh')); }} 
        </div>
    </div>
        
    <div class="form-group">
        {{ Form::label('zipcode', 'Zipcode: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('zipcode',$user->zipcode, array('class' => 'form-control', 'placeholder' => 'ex. 110001', "data-inputmask"=>'"mask": "999999"', "data-mask")); }} 
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('gender', 'Gender: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('gender', array(0  =>'Female', 1=> 'Male'), $user->gender, array('class' => 'form-control', 'required')) }} 
            <span class='errorlogin'>{{$errors->first('gender');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('signature', 'Signature: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('signature', $user->signature, array('class' =>
            'form-control', 'placeholder' => 'Enter Employee Signature')); }}  
            <span class='errorlogin'>{{$errors->first('signature');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group" id="mentor_id_view" style="display:none;">
        {{ Form::label('mentor_id', 'Mentor: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('mentor_id', array("", 'Select Your Mentor'), '', array('class' => 'form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('mentor_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Update', array('class' => 'btn
            btn-info pull-right', 'id' => 'update-button') ); }}</div>

   </div>
{{ Form::close() }}
</div>
@stop
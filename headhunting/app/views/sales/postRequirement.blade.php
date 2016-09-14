@extends('layouts.adminLayout')
@section('content')
<div class="content">
@if($jobPost->id != "")
	{{ Form::open(array('route' => array('update-requirement-action', $jobPost->id), 'class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
@else
	{{ Form::open(array('class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
@endif
    <div class="form-group">
        {{ Form::label('title', 'Job Title: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('title', $jobPost->title, array('class' =>
            'form-control', 'placeholder' => 'Enter Job Title')); }}
            <span class='errorlogin email-login'>{{$errors->first('title');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('type_of_employment', 'Type Of Employment: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('type_of_employment', array(1=>"Contratual", 2=> "Permanent"), $jobPost->type_of_employment, array('class' => 'form-control')) }}
            <span class='errorlogin'>{{$errors->first('type_of_employment');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('country_id', 'Country: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('country_id', $country,$jobPost->country_id, array('class' => 'form-control', 'id' => 'country_id')) }}
            <span class='errorlogin'>{{$errors->first('country_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('state_id', 'State: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('state_id', array('' => "Select State"), $jobPost->state_id, array('class' => 'form-control', 'id' => 'state_id')) }}
            <span class='errorlogin'>{{$errors->first('state_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <input type="hidden" value="" id="state">
    </div>

    <div class="form-group">
        {{ Form::label('city', 'City:', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('city', ($jobPost->city)?$jobPost->city->name:"",
					array('class' => 'form-control', 'placeholder' => 'Enter City')) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('client_id', 'Client: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('client_id', $client, $jobPost->client_id, array('class' => 'form-control', 'id' => 'client_id')) }}
            <span class='errorlogin'>{{$errors->first('client_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('vendor_id', 'Vendor: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('vendor_id', $vendor, $jobPost->vendor_id, array('class' => 'form-control', 'id' => 'vendor_id')) }}
            <span class='errorlogin'>{{$errors->first('vendor_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

	<div class="form-group">
        {{ Form::label('rate', 'Rate/hr: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('rate', $jobPost->rate, array('class' =>
            'form-control', 'placeholder' => 'ex. 20')); }}
            <span class='errorlogin'>{{$errors->first('rate');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('description', $jobPost->description, array('class' =>
            'form-control', 'placeholder' => 'ex. 20')); }}
            <span class='errorlogin'>{{$errors->first('description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit(($jobPost->id == "")?'Post Requirement':'Update Requirement', array('class' => 'btn
            btn-info pull-right', 'id' => 'requirement-button') ); }}</div>

   </div>

{{ Form::close() }}
@stop

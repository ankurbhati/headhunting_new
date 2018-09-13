@extends('layouts.adminLayout')
@section('content')
<div class="content">
    <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Post Requirement</h3>
                </div><!-- /.box-header -->

@if($jobPost->id != "")
	{{ Form::open(array('route' => array('update-requirement-action', $jobPost->id), 'class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
@else
	{{ Form::open(array('class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
@endif
    <div class="form-group">
        {{ Form::label('title', 'Job Title: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('title', $jobPost->title, array('class' =>
            'form-control', 'placeholder' => 'Enter Job Title', 'required')); }}
            <span class='errorlogin email-login'>{{$errors->first('title');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('type_of_employment', 'Type Of Employment: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('type_of_employment', array(1=>"Contratual", 2=> "Permanent", 3=>"Contract to hire"), $jobPost->type_of_employment, array('class' => 'form-control', 'required')) }}
            <span class='errorlogin'>{{$errors->first('type_of_employment');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group" id="duration_id">
        {{ Form::label('duration', 'Duration(In months): ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('duration', $jobPost->duration, array('class' => 'form-control')) }}
            <span class='errorlogin'>{{$errors->first('duration');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('country_id', 'Country: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('country_id', $country,$jobPost->country_id, array('class' => 'form-control', 'id' => 'country_id', 'required')) }}
            <span class='errorlogin'>{{$errors->first('country_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('state_id', 'State: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('state_id', array('' => "Select State"), $jobPost->state_id, array('class' => 'form-control', 'id' => 'state_id', 'required')) }}
            <span class='errorlogin'>{{$errors->first('state_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <input type="hidden" value="" id="state">
    </div>

    <div class="form-group">
        {{ Form::label('city', 'City:', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('city', ($jobPost->city)?$jobPost->city->name:"",
					array('class' => 'form-control', 'placeholder' => 'Enter City', 'required')) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('end_client', 'End Client:', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('end_client', ($jobPost->end_client)?$jobPost->end_client:"",
                    array('class' => 'form-control', 'placeholder' => 'Enter End Client')) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('client_id', 'Client: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('client_id', array('' => 'Please select') +  $client, $jobPost->client_id, array('class' => 'form-control', 'id' => 'client_id', 'required')) }}
            <span class='errorlogin'>{{$errors->first('client_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('mode_of_interview', 'Mode Of Interview: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('mode_of_interview', $jobPost->mode_of_interview, array('class' =>
            'form-control', 'placeholder' => 'Mode Of Interviewp', 'required')); }}
            <span class='errorlogin'>{{$errors->first('rate');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
	<div class="form-group">
        {{ Form::label('rate', 'Rate/hr $: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::input('number', 'rate', $jobPost->rate, array('class' =>
            'form-control', 'placeholder' => 'rate per hour integer only (ex: 65)', 'required')); }}
            <span class='errorlogin'>{{$errors->first('rate');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::textarea('description', $jobPost->description, array('class' =>
            'form-control')); }}
            <span class='errorlogin'>{{$errors->first('description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('comment', 'Comment: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('comment', $jobPost->comment, array('class' =>
            'form-control')); }}
            <span class='errorlogin'>{{$errors->first('comment');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit(($jobPost->id == "")?'Post Requirement':'Update Requirement', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>

   </div>
   </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          </div>

{{ Form::close() }}
@stop

@extends('layouts.adminLayout')
@section('content')
<div class="content">
	{{ Form::open(array('class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('mail_group_id', 'Mail Group: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('mail_group_id', $mail_groups, '', array('class' => 'form-control', 'id' => 'mail_group_id')) }}
            <span class='errorlogin'>{{$errors->first('mail_group_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('subject', 'Subject: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('subject', '', array('class' =>
            'form-control', 'placeholder' => 'subject')); }}
            <span class='errorlogin'>{{$errors->first('subject');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Text: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('description', '', array('class' =>
            'form-control', 'placeholder' => 'ex. 20')); }}
            <span class='errorlogin'>{{$errors->first('description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Send Mail', array('class' => 'btn
            btn-info pull-right', 'id' => 'requirement-button') ); }}</div>

    </div>

{{ Form::close() }}
</div>
@stop

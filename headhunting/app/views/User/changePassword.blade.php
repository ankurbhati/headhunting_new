@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'password-form',  'method' => 'POST')) }}
    <div class="form-group">
        {{ Form::label('Password', 'Password: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Employee Password')); }}
            <span class='errorlogin password-login'>{{$errors->first('password');}}</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('Confirm Password', 'ConfirmPassword: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
            {{ Form::password('confirm_password', array('class' => 'form-control', 'placeholder' => 'Confirm Password')); }}
            <span class='errorlogin password-login'>{{$errors->first('confirm_password');}}</span>
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Change Password', array('class' => 'btn
            btn-info pull-right', 'id' => 'password-button') ); }}</div>
   </div>
{{ Form::close() }}
</div>
@stop

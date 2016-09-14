@extends('layouts.loginLayout')
@section('content')
<div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
{{ Form::open(array('route' => 'login-member','class' =>
'login-form','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group has-feedback">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'sr-only')); }}
        {{ Form::text('email', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Your Email')); }} 
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
    </div>
    <div class="form-group has-feedback">
        {{ Form::label('Password', 'Password: ', array('class' => 'sr-only')); }}
		{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Your Password')); }}
		<span class="glyphicon glyphicon-lock form-control-feedback"></span> 
        <span class='errorlogin password-login'>{{$errors->first('password');}}</span>
        
    </div>
    <div class="row">
    	<div class="col-xs-8">
			<a href="#">I forgot my password</a><br>        	
        </div><!-- /.col -->
        <div class="col-xs-4">
            {{ Form::button('Sign In', array('class' => 'btn btn-primary btn-block btn-flat', 'type' => 'submit', 'id' => 'login-button') ); }}
        </div><!-- /.col -->
    </div>
{{ Form::close() }}
</div>
@stop        
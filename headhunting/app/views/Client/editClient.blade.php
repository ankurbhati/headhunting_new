@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">
        {{ Form::text('email', $client->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Client Email', 'required')); }}
        <span class='errorlogin'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('first_name', $client->first_name, array('class' =>
            'form-control', 'placeholder' => 'Enter Client First Name', 'required' => 'required')); }} 
            <span class='errorlogin'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('last_name', $client->last_name, array('class' =>
            'form-control', 'placeholder' => 'Enter Client Last Name', 'required' => 'required')); }} 
            <span class='errorlogin'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('company_name', 'Company: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('company_name', $client->company_name, array('class' => 'form-control', 'required' => 'required')) }} 
            <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-4">{{ Form::text('phone', $client->phone, array('class' =>
            'form-control', 'placeholder' => 'Enter Client Phone Number', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask", 'required' => 'required')); }} 
            <span class='errorlogin'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', $client->phone_ext, array('class' =>
            'form-control', 'placeholder' => 'Enter Client Phone Extension')); }} 
            <span class='errorlogin'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Update', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'update-button') ); }}</div>

   </div>
{{ Form::close() }}
</div>
@stop

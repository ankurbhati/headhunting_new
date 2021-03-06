@extends('layouts.adminLayout')
@section('content')
<div class="content">
    <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Add Client</h3>
                </div><!-- /.box-header -->
{{ Form::open(array('route' => 'add-client','class' =>
'form-horizontal clientForm','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label required')) }}
        <div class="col-sm-8">{{Form::text('email', "", array('class' =>
            'form-control','id' => 'client-email', 'placeholder' => 'Enter Clients Email', 'required' => 'required'))}}
            <span class='errorlogin email-client-ajax'></span> 
            <span class='errorlogin email-login'>{{$errors->first('email')}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('first_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Clients First Name', 'required' => 'required')); }} 
            <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('last_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Clients Last Name', 'required' => 'required')); }} 
            <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
 
    <div class="form-group">
        {{ Form::label('company_name', 'Company: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('company_name', "", array('class' => 'form-control', 'required' => 'required')) }} 
            <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-4">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask", 'required' => 'required')); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', "", array('class' => 'form-control', 'placeholder' => 'ex. 121')); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Add Client', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'login-button') ); }}</div>

   </div>
{{ Form::close() }}
    </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
</div>
@stop

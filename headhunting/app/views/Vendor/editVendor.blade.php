@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        {{ Form::text('email', $vendor->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Email', 'required')); }}
        <span class='errorlogin'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('vendor_domain', 'Vendor Domain: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('vendor_domain', $vendor->vendor_domain, array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Domain')); }} 
            <span class='errorlogin email-login'>{{$errors->first('vendor_domain');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('phone', $vendor->phone, array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Phone Number', 'required', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
            <span class='errorlogin'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('partner', 'IS Partner: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-1">{{ Form::checkbox('partner', 1, $vendor->partner, ['class' => 'checkbox']) }} 
            <span class='errorlogin email-login'>{{$errors->first('partner');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Update', array('class' => 'btn
            btn-info pull-right', 'id' => 'update-button') ); }}</div>

   </div>
{{ Form::close() }}
</div>
@stop
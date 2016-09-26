@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('route' => 'add-third-party','class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('email', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Email')); }} 
            <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('poc', 'Point Of Contact: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('poc', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Point Of Contact')); }} 
            <span class='errorlogin email-login'>{{$errors->first('poc');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', "", array('class' => 'form-control', 'placeholder' => 'ext. 121')); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('nca_document', 'Upload Nca Document: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="nca_document" />
        @if(Session::has('nca_document_error'))
            <span class="errorlogin email-login">
                {{ Session::get('nca_document_error') }}
            </span>
        @endif
        </div>
    </div>
	<div class="form-group">
        {{ Form::label('msa_document', 'Upload Msa Document: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="msa_document" />
        @if(Session::has('msa_document_error'))
            <span class="errorlogin email-login">
                {{ Session::get('msa_document_error') }}
            </span>
        @endif
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Add Third Party', array('class' => 'btn
            btn-info pull-right', 'id' => 'login-button') ); }}</div>
   </div>
{{ Form::close() }}
</div>
@stop

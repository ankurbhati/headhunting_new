@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        {{ Form::text('email', $thirdparty->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Thirdparty Email', 'required')); }}
        <span class='errorlogin'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('poc', 'Point Of Contact: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('poc', $thirdparty->poc, array('class' =>
            'form-control', 'placeholder' => 'Enter Point Of Contact')); }} 
            <span class='errorlogin email-login'>{{$errors->first('poc');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('phone', $thirdparty->phone, array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Phone Number', 'required', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
            <span class='errorlogin'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', $thirdparty->phone_ext, array('class' =>
            'form-control', 'placeholder' => 'Enter phone ext', 'required')); }} 
            <span class='errorlogin'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('document_type', 'Document Type: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('document_type', array(0=>"No Document Required", 1=>"NCA", 2=>"MSA"), $thirdparty->document_type, array('class' => 'form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('document_type');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
	<div class="form-group">
        {{ Form::label('upload_document', 'Upload Document: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="upload_document" />
        @if(Session::has('resume_error'))
            <span class="errorlogin email-login">
                {{ Session::get('upload_document_error') }}
            </span>
        @endif
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Update', array('class' => 'btn
            btn-info pull-right', 'id' => 'update-button') ); }}</div>

   </div>
{{ Form::close() }}
</div>
@stop

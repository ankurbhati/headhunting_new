@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        {{ Form::text('email', $thirdparty->email, array('class' =>
            'form-control', 'placeholder' => 'Enter Thirdparty Email', 'required', 'readonly')); }}
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
        {{ Form::label('nca_document', 'Upload Nca Document: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="nca_document" />
            @if($thirdparty->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document)))
            <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
            @else
            No NCA Document Uploaded 
            @endif
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
            @if($thirdparty->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document)))
            <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
            @else
            No MSA Document Uploaded 
            @endif
        @if(Session::has('msa_document_error'))
            <span class="errorlogin email-login">
                {{ Session::get('msa_document_error') }}
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

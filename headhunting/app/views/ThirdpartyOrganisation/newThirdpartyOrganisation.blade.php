@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('route' => 'add-third-party-organisation','class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Organisation Name')); }} 
            <span class='errorlogin email-login'>{{$errors->first('name');}}@if(!empty($message)){{$message}}@endIf
            </span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('domain', 'Domain: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('domain', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Domain')); }} 
            <span class='errorlogin email-login'>{{$errors->first('domain');}}@if(!empty($message)){{$message}}@endIf
            @if(Session::has('domain_error'))
                {{ Session::get('domain_error') }}
            @endif
            </span>
        </div>
    </div>

    <div id="nca-group">
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
            {{ Form::label('nca_description', 'NCA Description: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('nca_description', "", array('class' =>
                'form-control', 'placeholder' => 'Enter Description For NCA')); }} 
                <span class='errorlogin email-login'>{{$errors->first('nca_description');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('nca_activation_date', 'NCA Activation Date: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('nca_activation_date', "", array('class' =>
                'form-control', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'dd/mm/yyyy'", 'data-mask')); }} 
                <span class='errorlogin'>{{$errors->first('nca_activation_date');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
    </div>
    <div id="msa-group">
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
        <div class="form-group">
            {{ Form::label('msa_description', 'MSA Description: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('msa_description', "", array('class' =>
                'form-control', 'placeholder' => 'Enter Company Name For MSA')); }} 
                <span class='errorlogin email-login'>{{$errors->first('msa_description');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('msa_activation_date', 'MSA Activation Date: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('msa_activation_date', "", array('class' =>
                'form-control', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'dd/mm/yyyy'", 'data-mask')); }} 
                <span class='errorlogin'>{{$errors->first('msa_activation_date');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Add Organisation', array('class' => 'btn
            btn-info pull-right', 'id' => 'login-button') ); }}</div>
   </div>
{{ Form::close() }}
</div>
@stop

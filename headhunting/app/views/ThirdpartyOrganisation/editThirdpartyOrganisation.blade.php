@extends('layouts.adminLayout')
@section('content')
<div class="content">
{{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        {{ Form::text('name', $org->name, array('class' =>
            'form-control', 'placeholder' => 'Enter Organisation Name', 'required')); }}
        <span class='errorlogin'>{{$errors->first('name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('domain', 'Domain: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">
        {{ Form::text('domain', $org->domain, array('class' =>
            'form-control', 'placeholder' => 'Enter Domain Name', 'required', 'readonly')); }}
        <span class='errorlogin'>{{$errors->first('domain');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    @if($category == 0 || $category == 1 || $category == 3)
    <div id="nca-group">
        <div class="form-group">
            {{ Form::label('nca_document', 'Upload Nca Document: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8"><input type="file" name="nca_document" />
                @if($org->nca_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->nca_document)))
                <a href="{{'/uploads/documents/'.$org->id.'/'.$org->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
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
            {{ Form::label('nca_description', 'NCA Company Name: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('nca_description', $org->nca_description, array('class' =>
                'form-control', 'placeholder' => 'Enter Description For NCA')); }} 
                <span class='errorlogin email-login'>{{$errors->first('nca_description');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('nca_activation_date', 'NCA Activation Date: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('nca_activation_date', ($org->nca_activation_date != "" && $org->nca_activation_date != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($org->nca_activation_date)):"", array('class' =>
                'form-control', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'yyyy/mm/dd'", 'data-mask')); }} 
                <span class='errorlogin'>{{$errors->first('nca_activation_date');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
    </div>
    @endif
    @if($category == 0 || $category == 2 || $category == 3)
    <div id="msa-group">
        <div class="form-group">
            {{ Form::label('msa_document', 'Upload Msa Document: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8"><input type="file" name="msa_document" />
                @if($org->msa_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->msa_document)))
                <a href="{{'/uploads/documents/'.$org->id.'/'.$org->msa_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
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
        <div class="form-group">
            {{ Form::label('msa_description', 'MSA Company Name: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('msa_description', $org->msa_description, array('class' =>
                'form-control', 'placeholder' => 'Enter Description For MSA')); }} 
                <span class='errorlogin email-login'>{{$errors->first('msa_description');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('msa_activation_date', 'MSA Activation Date: ', array('class' => 'col-sm-3
            control-label')); }}
            <div class="col-sm-8">{{ Form::text('msa_activation_date', ($org->msa_activation_date != "" && $org->msa_activation_date != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($org->msa_activation_date)):"", array('class' =>
                'form-control', 'placeholder' => 'ex. 10/08/2016', 'data-inputmask'=>"'alias': 'yyyy/mm/dd'", 'data-mask')); }} 
                <span class='errorlogin'>{{$errors->first('msa_activation_date');}}@if(!empty($message)){{$message}}@endIf</span>
            </div>
        </div>
    </div>
    @endif
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Update', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'update-button') ); }}</div> 
    </div>
{{ Form::close() }}
</div>
@stop

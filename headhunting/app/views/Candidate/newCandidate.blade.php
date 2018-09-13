@extends('layouts.adminLayout')
@section('content')
<div class="content">
    <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Add Candidate</h3>
                </div><!-- /.box-header -->

{{ Form::open(array('route' => 'add-candidate','class' =>
'form-horizontal','id' => 'candidate-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::email('email', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Candidate Email', 'required'=>"true")); }} 
            <span class='errorlogin email-login-ajax'></span>
            <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('first_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Candidates First Name', 'required'=>"true")); }} 
            <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('last_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Candidates Last Name', 'required'=>"true")); }} 
            <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask", 'required'=>"true")); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group" style="display: none;">
        {{ Form::label('country_id', 'Country: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('country_id', $country, null, array('class' => 'form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('country_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('state_id', 'State: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('state_id', [], null, array('class' => 'form-control', 'required'=>"true")) }} 
            <span class='errorlogin email-login'>{{$errors->first('state_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('city', 'City: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('city', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Candidates City', 'required'=>"true")); }} 
            <span class='errorlogin email-login'>{{$errors->first('city');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('designation', 'Job Title: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('designation', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Candidates Job Title', 'required'=>"true")); }} 
            <span class='errorlogin email-login'>{{$errors->first('designation');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('rate', 'Rate/hr: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::text('rate', "", array('class' =>
            'form-control', 'placeholder' => 'ex. 20', 'required'=>"true")); }}
            <span class='errorlogin'>{{$errors->first('rate');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('work_state_id', 'Work State: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('work_state_id',  array('' => 'Please select') + $work_state, '', array('class' => 'form-control', 'required'=>"true")) }} 
            <span class='errorlogin email-login'>{{$errors->first('work_state_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
     <div class="form-group" id="third_party_view" style="display:none;">
        {{ Form::label('third_party_id', 'Third Party: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::email('third_party_id', "", array('class' => 'form-control', 'placeholder' => 'Enter third party')) }} 
            <span class='errorlogin email-login'>{{$errors->first('third_party_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('visa_id', 'Visa: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8">{{ Form::select('visa_id',  array('' => 'Please select') + $visa, '', array('class' => 'form-control', 'required'=>"true")) }} 
            <span class='errorlogin email-login'>{{$errors->first('visa_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('resume', 'Upload Resume: ', array('class' => 'col-sm-3
        control-label required')); }}
        <div class="col-sm-8"><input type="file" name="resume" required="true">
        @if(Session::has('resume_error'))
            <span class="errorlogin email-login">
                {{ Session::get('resume_error') }}
            </span>
        @endif
        </div>
    </div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Add Candidate', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'login-button') ); }}</div>

   </div>
{{ Form::close() }}
                </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

</div>
@stop
@extends('layouts.adminLayout')
@section('content')
<div class="content">
	{{ Form::open(array('class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

    <div class="form-group">
        {{ Form::label('disclaimer', 'Mail Disclaimer: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('disclaimer', $setting->disclaimer, array('class' =>
            'form-control', 'placeholder' => 'ex. Disclaimer')); }}
            <span class='errorlogin'>{{$errors->first('disclaimer');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('guidence', 'Guidence: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::textarea('guidence', $setting->guidence, array('class' =>
            'form-control', 'placeholder' => 'ex. Guidence')); }}
            <span class='errorlogin'>{{$errors->first('guidence');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Save Settings', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>

    </div>

{{ Form::close() }}
</div>
@stop

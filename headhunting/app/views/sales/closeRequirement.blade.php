@extends('layouts.adminLayout')
@section('content')
<div class="content">
	{{ Form::open(array('class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
    @foreach($feedbacks as $key=>$value)
        <div class="form-group">
            <div class="col-sm-8" style="float:right;clear:both;">
                <input type="radio" name="feedback" required="true" value="{{$key}}" >
                <span><b>{{$value}}</b></span>
            </div>
        </div>
    @endforeach

    <div class="form-group row ">
        <div class="col-sm-8" style="float: right;" >{{ Form::submit('Close Requirement', array('class' => 'btn
            btn-info', 'id' => 'requirement-button') ); }}</div>
    </div>

{{ Form::close() }}
@stop

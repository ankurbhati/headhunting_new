@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view requirement-view">
    <div class="box col-sm-12">
        <div class="box-heading">Close Job Post</div>
        <div class="box-view">
        	{{ Form::open(array('class' =>
        	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
            <div class="form-group">
                @foreach($feedbacks as $key=>$value)
                    <div class="col-xs-12 col-sm-3">
                        <input type="radio" id="{{$key}}" name="feedback" required="true" value="{{$key}}">
                        <label for="{{$key}}">{{$value}}</label>
                    </div>
                @endforeach
            </div>

            <div class="form-group row ">
                <div class="col-sm-12">{{ Form::submit('Close Requirement', array('class' => 'btn
                    btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop

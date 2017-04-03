@extends('layouts.adminLayout')
@section('content')
<div class="content">
  <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Upload Report</h3>
                </div><!-- /.box-header -->
{{ Form::open(array('route' => 'upload-work-report','class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

	<div class="form-group">
        {{ Form::label('work_report', 'Upload Report: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="work_report" required/>
        <span class="errorlogin email-login">
        @if(Session::has('upload_report_error'))
                {{ Session::get('upload_report_error') }}
        @endif
        {{$errors->first('work_report');}}@if(!empty($message)){{$message}}@endIf
        </span>
        </div>
    </div>

    <div class="form-group row">
        {{ Form::label('for_date', 'Date:', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('for_date', "", array('placeholder' => 'Enter Date', 'class'=>'from_date form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('for_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
    	<!--<div class="col-sm-5">
    		<a href="{{'/downloads/client/client.csv'}}" class="btn btn-secondary btn-white pull-left">Download Clients Csv Template</a>
        </div>-->
        <div class="col-sm-12">
        	{{ Form::submit('Upload Report', array('class' => 'btn
            btn-primary btn-white center-input', 'id' => 'login-button') ); }}
        </div>
   </div>
   @if(Session::has('upload_result'))
   <hr>
   <div style="height: 500px; overflow: scroll;">
        {{ Session::get('upload_result') }}
   </div>
   @endif
{{ Form::close() }}
</div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
</div>
@stop

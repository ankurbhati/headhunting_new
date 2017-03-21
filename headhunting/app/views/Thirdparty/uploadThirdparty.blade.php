@extends('layouts.adminLayout')
@section('content')
<div class="content">
    <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Upload Third Party</h3>
                </div><!-- /.box-header -->
{{ Form::open(array('route' => 'upload-third-party-csv','class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

	<div class="form-group">
        {{ Form::label('client_csv', 'Upload Third Party CSV: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8"><input type="file" name="third_party_csv" required/>
        @if(Session::has('third_party_csv_error'))
            <span class="errorlogin email-login">
                {{ Session::get('third_party_csv_error') }}
            </span>
        @endif
        </div>
    </div>

    <div class="form-group row ">
    	<div class="col-sm-5">
    		<a href="{{'/downloads/third_party/third_party.csv'}}" class="btn btn-info pull-left">Download Third Party Csv Template</a>
        </div>
        <div class="col-sm-6">
        	{{ Form::submit('Upload Third Party', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'login-button') ); }}
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

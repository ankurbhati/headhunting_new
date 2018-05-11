@extends('layouts.adminLayout')
@section('content')
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
          <div class="box-header">
            <h3 class="box-title">Reports</h3>
          </div><!-- /.box-header -->
          {{ Form::open(array('class' => 'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}
            <div class="form-group">
              <div class="col-xs-3">
                  {{ Form::label('report_type', 'Type of Report: ', array('class' => '
                control-label')); }}
                  {{ Form::select('report_type',array(-1 => 'Please Select', 0=>"Candidates Added", 1=>"Clients Added", 2=> "Job post Added", 3=>"Submittels", 4 => 'Job Post Served', 5 => 'Interviews', 6 => 'PO'), -1, array('class' => 'form-control')) }}
              </div>
              <div class="col-xs-3">
                  {{ Form::label('user_id', 'Employees: ', array('class' => '
                control-label')); }}
                  {{ Form::select('user_id',$users, -1, array('class' => 'form-control')) }}
              </div>
              <div class="col-xs-3">
                  {{ Form::label('team_id', 'Teams: ', array('class' => '
                control-label')); }}
                  {{ Form::select('team_id',$teams,-1, array('class' => 'form-control')) }}
              </div>
              <div class="col-xs-3">
                <div class="row">
                  <div class="col-xs-12">
                    {{ Form::label('from_date', 'From Date - To Date:', array('class' => 'pull-left
                  control-label')); }}
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                      <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                  </div>
                  <div class="col-sm-6">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                      <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row margin-top-10">
              <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
              <div class="col-sm-2">
                  {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button') ); }}
              </div>
              <div class="col-sm-2">
                  {{ Form::button('Show Magic Numbers', array('class' => 'btn btn-primary btn-white', 'id' => 'download-button', 'style'=>'margin-left:-35px;') ); }}
              </div>
            </div>
          {{ Form::close() }}
      </div>
    </div>
  </div>
</section>
@stop

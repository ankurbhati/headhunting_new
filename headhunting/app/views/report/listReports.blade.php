@extends('layouts.adminLayout') 
@section('content')
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Reports</h3>
        </div>
        <!-- /.box-header -->
        {{ Form::open(array('class' => 'form-horizontal', 'id' => 'login-form', 'method' => 'GET')) }}
        <div class="form-group">
          <div class="col-xs-3">
            {{ Form::label('report_type', 'Type of Report: ', array('class' => ' control-label')); }} {{ Form::select('report_type',array(''
            => 'Please Select', 0=>"Candidates Added", 1=>"Clients", 2 => "Third Parties", 3 => "Job post"),
            $request['report_type'], array('class' => 'form-control')) }}
            <span class='errorlogin report_type'>{{$errors->first('report_type');}}@if(!empty($message)){{$message}}@endIf</span>
          </div>
          <div class="col-xs-3">
            {{ Form::label('team_id', 'Assigned Team: ', array('class' => ' control-label')); }} {{ Form::select('team_id',$teams, $request['team_id'],
            array('class' => 'form-control')) }}
          </div>
          <div class="col-xs-3">
            {{ Form::label('user_id', 'Added By: ', array('class' => ' control-label')); }} {{ Form::select('user_id',$users, $request['user_id'], array('class'
            => 'form-control')) }}
          </div>
          <div class="col-xs-3">
            <div class="row">
              <div class="col-xs-12">
                {{ Form::label('from_date', 'From Date - To Date:', array('class' => 'pull-left control-label')); }}
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">{{ Form::text('from_date', $request['from_date'], array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date
                form-control')) }}
                <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
              </div>
              <div class="col-sm-6">{{ Form::text('to_date', $request['to_date'], array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date
                form-control')) }}
                <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
              </div>
            </div>
          </div>
          <div class="col-xs-3 report-status   report-type-1  sr-only">
            {{ Form::label('report_status_clients', 'Clients Status: ', array('class' => ' control-label')); }} {{ Form::select('report_status_clients',array(-1
            => 'Please Select', 1=>"Active", 2=>"Blocked"), $request['report_status_clients'], array('class' => 'form-control')) }}
          </div>
          <div class="col-xs-3  report-status  report-type-2  sr-only">
            {{ Form::label('report_status_vendors', 'Third Parties Status: ', array('class' => ' control-label')); }} {{ Form::select('report_status_vendors',array(-1
            => 'Please Select', 1=>"Active", 2=>"Blacklisted"), $request['report_status_vendors'], array('class' => 'form-control')) }}
          </div>
          <div class="col-xs-3 report-status  report-type-3  sr-only">
            {{ Form::label('report_status_job_posts', 'Job Post Status: ', array('class' => ' control-label')); }} {{ Form::select('report_status_job_posts',array(-1
            => 'Please Select', 1=>"Pending", 2=>"Approved", 3=> "Closed"), $request['report_status_job_posts'], array('class' => 'form-control')) }}
          </div>
          <div class="col-xs-3 report-status   report-type-4  sr-only">
            {{ Form::label('report_status_submittels', 'Submittel Status: ', array('class' => ' control-label')); }} {{ Form::select('report_status_submittels',array(-1
            => 'Please Select', '0'=>'Pending', '1'=>'Open', '2'=>'Reject', '3'=>'Forwarded To Prime Vendor', '4'=>'Rejected
            By Prime Vendor', '5'=>'Submitted To End Client', '6'=>'Interview Scheduled', '7'=>'Purchase Order', '8'=>'Rejected
            By End Client', '9'=>'On Hold By End Client', '10' => 'Rejected By Recruiter Manager'), $request['report_status_submittels'], array('class' =>
            'form-control')) }}
          </div>
        </div>
        <div class="row margin-top-10">
          <div class="col-sm-2">
            {{ Form::button('Search Results', array('class' => 'btn btn-primary btn-white', 'id' => 'download-button') ); }}
          </div>
        </div>
        {{ Form::close() }}
        <div class="box-body">
          @if(count($data) > 0  && $data->getTotal() > 0)
          <div>
            <p class="result-total"><span class="text-bold">{{$data->getTotal()}} results :</span></p>
          </div>
          <?php echo $child ?>
          @endif
          @if (count($data) > 0  && $data->getTotal() > 0)
          <div>
            <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Pages</span> {{ $data->links() }}
          </div>
          @endif
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
  </div>
</section>

@stop
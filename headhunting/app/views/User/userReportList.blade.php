@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

                  <div class="form-group">
                    {{ Form::label('for_date', 'For Date:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-2">{{ Form::text('for_date', "", array('class' => 'form-control','placeholder' => 'Enter For Date', 'class'=>'from_date form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('for_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                  </div>

                  <div class="form-group row ">
                    <div class="col-sm-3">
                        {{ Form::button('Search', array('class' => 'btn btn-primary btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
                    </div>
                  </div>
              {{ Form::close() }}





                <!-- /.box-header -->
                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$user_reports->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Report</th>
                        <th>For Date</th>
                        <th>Added At</th>
                        <th>Updated At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($user_reports as $user_report)
                          <tr>
                            <td>{{$user_report->user->first_name." ".$user_report->user->last_name}}</td>
                            <td>{{$user_report->user->email}}</td>
                            <td>
                              @if( $user_report->filename && file_exists(public_path('/uploads/reports/'.$user_report->id.'/'.$user_report->filename)))
                              <a href="{{'/uploads/reports/'.$user_report->id.'/'.$user_report->filename}}" title="Download Resume" target="_blank" class="btn btn-secondary btn-white">Download Report</a>
                              @endif
                            </td>
                            <td>{{($user_report->for_date != "" && $user_report->for_date != "0000-00-00")?date("Y-m-d", strtotime($user_report->for_date)):"-"}}</td>
                            <td>{{($user_report->created_at != "" && $user_report->created_at != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($user_report->created_at)):"-"}}</td>
                            <td>{{($user_report->updated_at != "" && $user_report->updated_at != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($user_report->updated_at)):"-"}}</td>
                          </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Report</th>
                        <th>For Date</th>
                        <th>Added At</th>
                        <th>Updated At</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
@stop
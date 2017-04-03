@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

                  <div class="form-group">
                    {{ Form::label('from_date', 'For Date:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-2">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-2">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                  </div>

                  <div class="form-group row ">
                    <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
                    <div class="col-sm-3">
                        {{ Form::button('Search', array('class' => 'btn btn-info', 'id' => 'search-button', 'style'=>"float:right") ); }}
                    </div>
                    <div class="col-sm-8">
                        {{ Form::button('Download Csv', array('class' => 'btn btn-info', 'id' => 'download-button', 'style'=>"float:right") ); }}
                    </div>
                  </div>
              {{ Form::close() }}





                <!-- /.box-header -->
                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$reports->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>UserName</th>
                        <th>File</th>
                        <th>For Date</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($reports as $report)
                          <tr>
                            <td>{{$report->user->first_name.' '.$report->user->last_name}}</td>
                            <td>
                              @if($report->filename && file_exists(public_path('/uploads/reports/'.$report->id.'/'.$report->filename)))
                                <a href="{{'/uploads/reports/'.$report->id.'/'.$report->filename}}" title="Download Report" target="_blank"><i class="glyphicon glyphicon-download"></i></a>
                              @else
                                -
                              @endif
                            </td>
                            <td>{{($report->for_date != "" && $report->for_date != "0000-00-00")?date("Y-m-d", strtotime($report->for_date)):"-"}}</td>
                            <td>{{($report->created_at != "" && $report->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($report->created_at)):"-"}}</td>
                            <td>{{($report->updated_at != "" && $report->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($report->updated_at)):"-"}}</td>
                          </tr>
                      @empty
                        <p>No Report Found</p>
                      @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>UserName</th>
                        <th>File</th>
                        <th>For Date</th>
                        <th>Created At</th>
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
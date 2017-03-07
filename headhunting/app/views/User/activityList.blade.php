@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

                  <div class="form-group">
                      {{ Form::label('type', 'Type: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::select('type', array('' => 'Please select one Valid Type') + $types, '', array('class' => 'form-control')) }}
                          <span class='errorlogin email-login'>{{$errors->first('type');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  <div class="form-group">
                    {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date')) }}
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-4">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date')) }} 
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
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Records :  <span class="text-bold">{{$activities->getTotal()}}</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Added At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($activities as $activity)
                          <tr>
                            <td>{{$activity->description}}</td>
                            <td>{{$types[$activity->type]}}</td>
                            <td>{{($activity->created_at != "" && $activity->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($activity->created_at)):"-"}}</td>
                          </tr>
                      @empty
                        <p>No Activity Found</p>
                      @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Added At</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
@stop
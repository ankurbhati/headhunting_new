@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}

                  <div class="form-group">
                    {{ Form::label('for_date', 'For Date:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-2">{{ Form::text('for_date', "", array('class' => 'form-control','placeholder' => 'Enter For Date', 'class'=>'from_date form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('for_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                  </div>

                  <div class="form-group">
                    {{ Form::label('status', 'User Selection:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::select('status', array(''=>'Please Select', '1'=>'Uploaders', '2'=>'Defaulters'), '', array('placeholder' => 'User Selection', 'class'=>'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
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
                        <p class="result-total">
                        <span class="text-bold">
                        @if (isset($user_reports))
                          {{$user_reports->getTotal()}} results:
                        @else
                          {{$users->getTotal()}} results:
                        @endif
                        </span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>    
                        <th>Report</th>
                        <th>For Date</th>
                        @if (isset($user_reports))
                        <th>Added At</th>
                        <th>Updated At</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @if (isset($users))
                      @foreach($users as $user)
                        @if($user->hasRole(2) || $user->hasRole(3) || $user->hasRole(4) || $user->hasRole(5))
                          <tr>
                            <td>{{$user->first_name." ".$user->last_name}}</td>
                            <td>{{$user->email}}</td>
                            <td>-</td>
                            <td>{{$current_date}}</td>
                          </tr>
                        @endif
                      @endforeach
                      @endif

                      @if (isset($user_reports))
                      @foreach($user_reports as $user_report)
                        @if($user_reports->user->hasRole(2) || $user_reports->user->hasRole(3) || $user_reports->user->hasRole(4) || $user_reports->user->hasRole(5))
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
                          @endif
                      @endforeach
                      @endif
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Report</th>
                        <th>For Date</th>
                        @if (isset($user_reports))
                        <th>Added At</th>
                        <th>Updated At</th>
                        @endif
                      </tr>
                    </tfoot>
                  </table>
                  @if (isset($user_reports) && count($user_reports) > 0)
                    <div>  
                      {{ $user_reports->links() }}
                    </div>
                  @elseif (isset($users) && count($users) > 0)
                  <div>  
                      {{ $users->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
@stop
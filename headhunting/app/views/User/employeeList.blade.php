@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Employee List</h3>
                </div>
              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}

                  <div class="form-group">
                      {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('email', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Your Email')); }} 
                          <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>

                  <div class="form-group">
                      {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('first_name', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Employee First Name')); }} 
                          <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  <div class="form-group">
                      {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('last_name', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Employee Last Name')); }} 
                          <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>

                  <div class="form-group">
                      {{ Form::label('designation', 'Designation: ', array('class' => 'col-sm-3
                      control-label')); }}
                      <div class="col-sm-8">{{ Form::text('designation', "", array('class' =>
                          'form-control', 'placeholder' => 'Enter Employee Designation')); }}  
                          <span class='errorlogin email-login'>{{$errors->first('designation');}}@if(!empty($message)){{$message}}@endIf</span>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-4">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                  </div>

                  <div class="form-group row ">
                    <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
                    <div class="col-sm-3">
                        {{ Form::button('Search', array('class' => 'btn btn-primary btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
                    </div>
                    <div class="col-sm-8">
                        {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button', 'style'=>"float:right") ); }}
                    </div>
                  </div>
              {{ Form::close() }}





                <!-- /.box-header -->
                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$users->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Email<br>Full Name</th>
                        <th>Designation</th>
                        <th>Roles</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @foreach($users as $user)
		                      <tr>
		                        <td>
                              <strong>{{$user->email}}</strong><br>{{$user->first_name. " ".$user->last_name }}</td>
		                        <td>{{$user->designation}}</td>
		                        <td>{{$user->userRoles[0]->roles->role}}</td>
                            <td>{{($user->created_at != "" && $user->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($user->created_at)):"-"}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-member', array('id' => $user->id)) }}" title="View Profile" class="btn btn-primary btn-white">View Profile</a>
		                        	<a href="{{ URL::route('edit-member', array($user->id)) }}" title="Edit Profile" class="btn btn-primary btn-white" >Edit</a>
		                        	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('delete-member', array($user->id)) }}" title="Delete Profile" class="btn btn-secondary btn-white">Delete</a>
		                        	@endif
		                        	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('change-password', array('id' => $user->id)) }}" title="Change Password" class="btn btn-primary btn-white">Change Password</a>
		                        	@endif
		                        </td>
		                      </tr>
      						@endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Email<br>Full Name</th>
                        <th>Designation</th>
                        <th>Roles</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
@stop
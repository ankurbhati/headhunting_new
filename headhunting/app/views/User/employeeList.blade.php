@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div>



              {{ Form::open(array('class' =>
              'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

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
                  
                  <div class="form-group row ">
                      <div class="col-sm-11" style="text-align:center;">{{ Form::submit('Search', array('class' => 'btn
                          btn-info', 'id' => 'login-button') ); }}</div>

                 </div>
              {{ Form::close() }}





                <!-- /.box-header -->
                <div class="box-body">
                <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Employees :  <span class="text-bold">{{$users->getTotal()}}</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Roles</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($users as $user)
		                      <tr>
		                        <td>{{$user->first_name. " ".$user->last_name }}</td>
		                        <td>{{$user->email}}</td>
		                        <td>{{$user->designation}}</td>
		                        <td>{{$user->userRoles[0]->roles->role}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-member', array('id' => $user->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
		                        	<a href="{{ URL::route('edit-member', array($user->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
		                        	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('delete-member', array($user->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
		                        	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('change-password', array('id' => $user->id)) }}" title="Change Password"><i class="fa fa-fw fa-unlock-alt"></i></a>
		                        	@endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No users</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Roles</th>
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
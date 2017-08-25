@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
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
		                        	@if(Auth::user()->getRole() == 1 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('client-all-transfer', array('id' => $id, 'userId' => $user->id)) }}" title="Change Password" class="btn btn-primary btn-white">Tranfer To {{$user->first_name. " ".$user->last_name }}</a>
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
@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
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
		                        	@if(Auth::user()->getRole() == 1)
		                        		<a href="{{ URL::route('delete-member', array($user->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
		                        	@if(Auth::user()->getRole() == 1)
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
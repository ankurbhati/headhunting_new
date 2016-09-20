@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  @if($jobPostId > 0)
                    <h3 class="box-title">Assign Job Post</h3>
                  @endif
                  @if($jobPostId == 0)
                    <h3 class="box-title">My Team Area</h3>
                  @endif
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Roles</th>
                        @if($jobPostId > 0)
                          <th>Action</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($managerUsers as $managerUser)
                          <tr>
                            <td>{{$managerUser->first_name. " ".$managerUser->last_name }}</td>
                            <td>{{$managerUser->email}}</td>
                            <td>{{$managerUser->designation}}</td>
                            <td>{{$managerUser->userRoles[0]->roles->role}}</td>
                            @if($jobPostId > 0 && $jobPost->jobsAssignedToId($managerUser->id)->count() == 0)
                              <th><a href="{{ URL::route('assign-requirement', array('id' => $jobPostId, 'assignedTo' => $managerUser->id )) }}" title="Assign To {{$managerUser->first_name}}"><i class="fa fa-plus"></i>Assign Job Post</a></th>
                            @else
                              @if($jobPostId > 0)
                                <th>Already Assigned</th>
                              @endif
                            @endif
                          </tr> 
                      @endforeach
	                    @foreach($users as $user)
		                      <tr>
		                        <td>{{$user->peer->first_name. " ".$user->peer->last_name }}</td>
		                        <td>{{$user->peer->email}}</td>
		                        <td>{{$user->peer->designation}}</td>
		                        <td>{{$user->peer->userRoles[0]->roles->role}}</td>
                            @if($jobPostId > 0 && $jobPost->jobsAssignedToId($user->peer->id)->count() == 0)
                              <th><a href="{{ URL::route('assign-requirement', array('id' => $jobPostId, 'assignedTo' => $user->peer->id )) }}" title="Assign To {{$user->peer->first_name}}"><i class="fa fa-plus"></i>Assign Job Post</a></th>
                            @else
                              <th>Already Assigned</th>
                            @endif
		                      </tr> 
						          @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Designation</th>
                        <th>Roles</th>
                        @if($jobPostId > 0)
                          <th>Action</th>
                        @endif
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

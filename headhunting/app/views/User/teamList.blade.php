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
                    <h3 class="box-title">{{$title}}</h3>
                  @endif
                </div><!-- /.box-header -->
                <div class="box-body">
                <div>
                      @if(count($managerUsers)>0)
                        <p class="result-total"><span class="text-bold">{{(count($managerUsers)>0)?$managerUsers->getTotal():0}} results:</span></p>
                      @endif
                      @if(count($users)>0)
                        <p class="result-total"><span class="text-bold">{{(count($users)>0)?$users->getTotal():0}} results:</span></p>
                      @endif
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
                      @foreach($managerUsers as $managerUser)
                          <tr>
                            <td>{{$managerUser->first_name. " ".$managerUser->last_name }}</td>
                            <td>{{$managerUser->email}}</td>
                            <td>{{$managerUser->designation}}</td>
                            <td>{{$managerUser->userRoles[0]->roles->role}}</td>
                            <td>
                            @if($jobPostId > 0 && $jobPost->jobsAssignedToId($managerUser->id)->count() == 0)
                              <a href="{{ URL::route('assign-requirement', array('id' => $jobPostId, 'assignedTo' => $managerUser->id )) }}" title="Assign To {{$managerUser->first_name}}" class="btn btn-primary btn-white" ></i>Assign Job Post</a>
                            @else
                              @if($jobPostId > 0)
                                Already Assigned
                              @endif
                            @endif
                            <a href="{{ URL::route('my-activity', array('id' => $managerUser->id)) }}" class="btn btn-primary btn-white" title="View Activity">View Activity</a>
                            @if(Auth::user()->hasRole(1))
                            <a href="{{ URL::route('user-report', array('id' => $managerUser->id)) }}" class="btn btn-primary btn-white"  title="View Reports">View Report</a>
                            @endif
                            </td>
                          </tr> 
                      @endforeach
	                    @foreach($users as $user)
		                      <tr>
		                        <td>{{$user->user->first_name. " ".$user->user->last_name }}</td>
		                        <td>{{$user->user->email}}</td>
		                        <td>{{$user->user->designation}}</td>
		                        <td>{{$user->user->userRoles[0]->roles->role}}</td>
                            <td>
                            @if($jobPostId > 0 && $jobPost->jobsAssignedToId($user->user->id)->count() == 0)
                              <a href="{{ URL::route('assign-requirement', array('id' => $jobPostId, 'assignedTo' => $user->user->id )) }}" class="btn btn-primary btn-white" title="Assign To {{$user->user->first_name}}">Assign To {{$user->user->first_name}}</a>
                            @else
                              @if($jobPostId > 0)
                                Already Assigned
                              @endif
                            @endif
                            <a href="{{ URL::route('my-activity', array('id' => $user->user->id)) }}"  class="btn btn-primary btn-white" title="View Activity">View Activity</a>
                            </td>
		                      </tr> 
						          @endforeach
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
                  @if (count($managerUsers) > 0)
                    <div>
                      <p style="padding:1.9em 1.2em 0px 0px;">Total no of Employees :  <span class="text-bold">{{$managerUsers->getTotal()}}</span></p>
                    </div>
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Pages</span>
                      {{ $managerUsers->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

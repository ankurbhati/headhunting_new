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
                        <th>Phone</th>
                        <th>Added At(time, city, state)</th>
                        <th>Visa Id</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($candidates as $candidate)
		                      <tr>
                            <td>{{$candidate->first_name. " ".$candidate->last_name }}</td>
                            <td>{{$candidate->email}}</td>
		                        <td>{{$candidate->phone}}</td>
                            <td>
                              {{($candidate->created_at != "" && $candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->created_at)):"-"}}
                              @if($candidate->city){{$candidate->city->name}}@else{{"-"}}@endif
                              @if($candidate->state){{$candidate->state->state}}@else{{"-"}}@endif
                            </td>
		                        <td>{{$candidate->visa->title}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-candidate', array('id' => $candidate->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
                              @if(Auth::user()->getRole() <= 3)
		                        	  <a href="{{ URL::route('edit-candidate', array($candidate->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
                              @endif
		                        	@if(Auth::user()->hasRole(1) || Auth::user()->hasRole(3) || Auth::user()->hasRole(5) || Auth::user()->hasRole(7))
		                        		<a href="{{ URL::route('delete-candidate', array($candidate->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
                              @if(Auth::user()->getRole() <= 3 && $candidate->resume_path && file_exists(public_path('/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path)))
                              <a href="{{'/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path}}" title="Download Resume" target="_blank"><i class="glyphicon glyphicon-download"></i></a>
                              @endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Candidate</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Added At(time, city, state)</th>
                        <th>Visa Id</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($candidates) > 100)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $candidates->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

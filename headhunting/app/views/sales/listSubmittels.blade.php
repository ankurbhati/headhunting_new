@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Job Submittels</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Candidate Name</th>
                        <th>Candidate Email</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@forelse($candidateApplications as $candidateApplication)
		                      <tr>
		                        <td>{{$candidateApplication->requirement->title}}</td>
            								<td>{{($candidateApplication->requirement->type_of_employment == 1)?"Contractual": ($candidateApplication->requirement->type_of_employment == 2)?"Permanent": "Contract to hire";}}</td>
            								<td>{{$candidateApplication->candidate->first_name. " ".$candidateApplication->candidate->last_name}}</td>
            								<td>{{$candidateApplication->candidate->email}}</td>
            								<td>{{($candidateApplication->status == 1)?"Not Interviewed":"PO";}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-requirement', array('id' => $candidateApplication->requirement->id)) }}" title="View Job Post"><i class="fa fa-fw fa-eye"></i></a>
                              <a href="{{ URL::route('view-candidate', array('id' => $candidateApplication->candidate->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
                              <a href="{{ URL::route('add-comment-job-post-view', array($candidateApplication->requirement->id)) }}" title="Add Comments"><i class="fa fa-fw fa-edit"></i></a>
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Job Posts</p>
						@endforelse

                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Job Title</th>
                        <th>Type Of Employment</th>
                        <th>Candidate Name</th>
                        <th>Candidate Email</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($candidateApplications) > 100)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $candidateApplications->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

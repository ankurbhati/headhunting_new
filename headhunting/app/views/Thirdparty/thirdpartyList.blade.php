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
                        <th>Email</th>
                        <th>Point Of Contact</th>
                        <th>Phone</th>
                        <th>Document Type</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($thirdparties as $thirdparty)
		                    <tr>
								<td>{{$thirdparty->email}}</td>
									<td>{{$thirdparty->poc}}</td>
									<td>{{$thirdparty->phone}}</td>
								@if($thirdparty->document_type == 1)
									<td>NCA</td>
								@elseif($thirdparty->document_type == 2)
									<td>MSA</td>
								@else
									<td>-</td>
								@endif
									<td>
										<a href="{{ URL::route('view-third-party', array('id' => $thirdparty->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
								  @if(Auth::user()->getRole() <= 3)
										  <a href="{{ URL::route('edit-third-party', array($thirdparty->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
								  @endif
										@if(Auth::user()->getRole() <= 3)
											<a href="{{ URL::route('delete-third-party', array($thirdparty->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
										@endif
								@if($thirdparty->document_type != 0 && $thirdparty->document_url && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->document_url)))
									<a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->document_url}}" title="Download Document" target="_blank"><i class="glyphicon glyphicon-download"></i></a>
								@endif
								</td>
	                      </tr>
	                   	@empty
	                   		<p>No Third Party</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Email</th>
                        <th>Point Of Contact</th>
                        <th>Phone</th>
                        <th>Document Type</th>
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

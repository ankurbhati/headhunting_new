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
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($thirdparties as $thirdparty)
		                    <tr>
								<td>{{$thirdparty->email}}</td>
									<td>{{$thirdparty->poc}}</td>
									<td>{{$thirdparty->phone}}</td>
                @if($thirdparty->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                  </td>
                @else
                  <td>-</td>
                @endif
								@if($thirdparty->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                  </td>
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
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($thirdparties) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $thirdparties->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

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
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of vendors :  <span class="text-bold">{{$vendors->getTotal()}}</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Email</th>
                        <th>Vendor Domain</th>
                        <th>Phone</th>
                        <th>Is Partner</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($vendors as $vendor)
		                      <tr>
                            <td>{{$vendor->email}}</td>
		                        <td>{{$vendor->vendor_domain}}</td>
		                        <td>{{$vendor->phone}}</td>
                            @if($vendor->partner)
                            <td>Yes</td>
                            @else
                            <td>No</td>
                            @endif
		                        <td>
		                        	<a href="{{ URL::route('view-vendor', array('id' => $vendor->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
                              @if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8))
		                        	  <a href="{{ URL::route('edit-vendor', array($vendor->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
                              @endif
		                        	@if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8))
		                        		<a href="{{ URL::route('delete-vendor', array($vendor->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Vendor</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Email</th>
                        <th>Vendor Domain</th>
                        <th>Phone</th>
                        <th>Is Partner</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of vendors :  <span class="text-bold">{{$vendors->getTotal()}}</span></p>
                  </div>
                  @if (count($vendors) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $vendors->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop
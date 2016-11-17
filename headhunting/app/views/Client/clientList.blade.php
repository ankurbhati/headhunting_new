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
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Created By</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($clients as $client)
		                      <tr>
		                        <td>{{$client->first_name. " ".$client->last_name }}</td>
		                        <td>{{$client->email}}</td>
		                        <td>{{$client->company_name}}</td>
		                        <td>{{$client->phone}}</td>
                            <td>{{$client->createdby->first_name. " ".$client->createdby->last_name }}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-client', array('id' => $client->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
                              @if(Auth::user()->hasRole(1))
		                        	  <a href="{{ URL::route('edit-client', array($client->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
		                        		<a href="{{ URL::route('delete-client', array($client->id)) }}" title="Delete Profile"><i class="fa fa-fw fa-ban text-danger"></i></a>
		                        	@endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No client</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Created By</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($clients) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $clients->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

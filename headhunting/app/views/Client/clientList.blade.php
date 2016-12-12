@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Table With Full Features</h3>
                </div><!-- /.box-header -->


                {{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}

                <div class="form-group">
                    {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('email', "", array('class' =>
                        'form-control', 'placeholder' => 'Enter Clients Email')); }} 
                        <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('first_name', "", array('class' =>
                        'form-control', 'placeholder' => 'Enter Clients First Name')); }} 
                        <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('last_name', "", array('class' =>
                        'form-control', 'placeholder' => 'Enter Clients Last Name')); }} 
                        <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
             
                <div class="form-group">
                    {{ Form::label('company_name', 'Company: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('company_name', "", array('class' => 'form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-4">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
                        <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-4">{{ Form::text('phone_ext', "", array('class' => 'form-control', 'placeholder' => 'ex. 121')); }} 
                        <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group row ">
                    <div class="col-sm-11" style="text-align:center;">{{ Form::submit('Search', array('class' => 'btn
                        btn-info', 'id' => 'login-button') ); }}</div>

               </div>
            {{ Form::close() }}


                <div class="box-body">
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of clients :  <span class="text-bold">{{$clients->getTotal()}}</span></p>
                  </div>
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
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of clients :  <span class="text-bold">{{$clients->getTotal()}}</span></p>
                  </div>
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

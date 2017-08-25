@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Client List</h3>
                </div><!-- /.box-header -->

                {{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}
                <div class="form-group">
                    <div class="col-sm-4">
                        {{ Form::label('email', 'E-Mail: ', array('class' => '
                        control-label')); }}
                        {{ Form::text('email', "", array('class' =>
                            'form-control', 'placeholder' => 'Enter Clients Email')); }} 
                            <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>

                    <div class="col-sm-4">
                        {{ Form::label('first_name', 'First Name: ', array('class' => '
                        control-label')); }}
                        {{ Form::text('first_name', "", array('class' =>
                            'form-control', 'placeholder' => 'Enter Clients First Name')); }} 
                            <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-4">
                        {{ Form::label('last_name', 'Last Name: ', array('class' => '
                        control-label')); }}
                        {{ Form::text('last_name', "", array('class' =>
                            'form-control', 'placeholder' => 'Enter Clients Last Name')); }} 
                            <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
            <div class="form-group">
                <div class="col-sm-4">
                    {{ Form::label('company_name', 'Company: ', array('class' => '
                    control-label')); }}
                    {{ Form::text('company_name', "", array('class' => 'form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf</span>
                </div>

                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-xs-12">
                          {{ Form::label('phone', 'Phone: ', array('class' => '
                    control-label')); }}
                        </div>
                      </div>
                      <div class="row">
                    <div class="col-sm-8">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
                        <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-4">{{ Form::text('phone_ext', "", array('class' => 'form-control', 'placeholder' => 'ex. 121')); }} 
                        <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    </div>
                </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-xs-12">
                            {{ Form::label('from_date', 'Added At:', array('class' => '
                        control-label')); }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                                <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                            </div>
                            <div class="col-sm-6">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                                <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-group margin-top-10">
                    <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
                    <div class="col-sm-1">
                        {{ Form::submit('Search', array('class' => 'btn btn-primary pull-left btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button', 'style'=>"float:right") ); }}
                    </div>
                </div>
            {{ Form::close()}}


                <div class="box-body">
                  <div>
                        <p class="result-total"><span class="text-bold">{{$clients->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Created By</th>
                        <th>Added At</th>
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
                            <td>{{($client->created_at != "" && $client->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->created_at)):"-"}}</td>
		                        <td>
		                        	<a class="btn btn-primary btn-white" href="{{ URL::route('view-client', array('id' => $client->id)) }}" title="View">View</a>
                              @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
		                        	  <a class="btn btn-primary btn-white" href="{{ URL::route('edit-client', array($client->id)) }}" title="Edit">Edit</a>
		                        		<a class="btn btn-secondary btn-white" href="{{ URL::route('delete-client', array($client->id)) }}" title="Delete">Delete</a>
                                        <a class="btn btn-primary btn-white" href="{{ URL::route('transfer-client', array($client->id)) }}" title="Transfer">Transfer</a>
		                        	@endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No client</p>
						@endforelse
                    </tbody>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of clients :  <span class="text-bold">{{$clients->getTotal()}}</span></p>
                  </div>
                  @if (count($clients) > 0)
                    <div>
                       
                      {{ $clients->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

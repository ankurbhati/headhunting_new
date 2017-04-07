@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Mass Mail List</h3>
                </div><!-- /.box-header -->


                {{ Form::open(array('class' =>
  'form-horizontal','id' => 'login-form',  'method' => 'GET')) }}

                <div class="form-group">
                    {{ Form::label('subject', 'Subject: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('subject', '', array('class' =>
                        'form-control', 'placeholder' => 'subject')); }}
                        <span class='errorlogin'>{{$errors->first('subject');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('status', 'Status: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::select('status', array(0=>'Select Status',1 => "pending", 2 => 'In progress', 3=>'Completed', 5 => 'Canceled'), 0, array('class' =>
                        'form-control')); }}
                        <span class='errorlogin'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('description', 'Description: ', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-8">{{ Form::text('description_search', '', array('class' =>
                        'form-control', 'placeholder' => 'ex. Hello User,')); }}
                        <span class='errorlogin'>{{$errors->first('description');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
                    control-label')); }}
                    <div class="col-sm-2">{{ Form::text('from_date', "", array('placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-2">{{ Form::text('to_date', "", array('placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                        <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                  </div>
                <div class="form-group row ">
                    <div class="col-sm-11">
                    {{ Form::submit('Search', array('class' => 'btn
                        btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>

                </div>

            {{ Form::close() }}




                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$mass_mails->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sent By</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Mail Group</th>
                        <th>limit-lower/limit-upper</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($mass_mails as $mass_mail)
		                      <tr>
		                        <td>{{$mass_mail->sendby->first_name." ".$mass_mail->sendby->last_name}}</td>
		                        <td>{{$mass_mail->subject}}</td>
		                        <td>{{(strlen($mass_mail->description)>100)?substr($mass_mail->description, 0, 97)."...":$mass_mail->description}}</td>
                            <td>{{$mass_mail->created_at}}</td>
                            <td>@if($mass_mail->mail_group_id == 1){{"Clients"}}@elseif($mass_mail->mail_group_id == 3){{"Third Party"}}@else {{"Candidates"}}@endif</td>
		                        <td>{{$mass_mail->limit_lower."/".$mass_mail->limit_upper}}</td>
                            <td>{{($mass_mail->status == 1)?"Pending":($mass_mail->status == 2?"In Progress":($mass_mail->status == 5?"Canceled":"Completed"))}}</td>
                            <td>{{($mass_mail->created_at != "" && $mass_mail->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($mass_mail->created_at)):"-"}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-mass-mail', array('id' => $mass_mail->id)) }}" title="View Mass Mail" class="btn btn-white btn-primary">View</a>

                              @if($mass_mail->status == 1 && (Auth::user()->hasRole(1) || Auth::user()->hasRole(8)))
                                <a href="{{ URL::route('cancel-mass-mail', array('id' => $mass_mail->id)) }}" title="Cancel Mass Mail" class="btn btn-secondary btn-white">Cancel Mass Mail</a>
                              @endif
		                        </td>
		                      </tr>
	                   	@empty
	                   		<p>No Mass Mail</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <th>Sent By</th>
                      <th>Subject</th>
                      <th>Description</th>
                      <th>Created At</th>
                      <th>Mail Group</th>
                      <th>limit-lower/limit-upper</th>
                      <th>Status</th>
                      <th>Sent At</th>
                      <th>Action</th>
                    </tfoot>
                  </table>
                   <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Mass Mails :  <span class="text-bold">{{$mass_mails->getTotal()}}</span></p>
                  </div>
                  @if (count($mass_mails) > 0)
                    <div>
                       
                      {{ $mass_mails->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop
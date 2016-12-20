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
                    <div class="col-sm-8">{{ Form::select('status', array(1 => "pending", 2 => 'In progress', 3=>'Completed', 5 => 'Canceled'), 1, array('class' =>
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

                <div class="form-group row ">
                    <div class="col-sm-11" style="text-align: center;">{{ Form::submit('Search', array('class' => 'btn
                        btn-info', 'id' => 'requirement-button') ); }}</div>

                </div>

            {{ Form::close() }}




                <div class="box-body">
                <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Mass Mails :  <span class="text-bold">{{$mass_mails->getTotal()}}</span></p>
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
		                        <td>
		                        	<a href="{{ URL::route('view-mass-mail', array('id' => $mass_mail->id)) }}" title="View Mass Mail"><i class="fa fa-fw fa-eye"></i></a>

                              @if($mass_mail->status == 1 && (Auth::user()->hasRole(1) || Auth::user()->hasRole(8)))
                                <a href="{{ URL::route('cancel-mass-mail', array('id' => $mass_mail->id)) }}" title="Cancel Mass Mail"><i class="fa fa-close"></i></a>
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
                      <th>Action</th>
                    </tfoot>
                  </table>
                   <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Mass Mails :  <span class="text-bold">{{$mass_mails->getTotal()}}</span></p>
                  </div>
                  @if (count($mass_mails) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $mass_mails->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop
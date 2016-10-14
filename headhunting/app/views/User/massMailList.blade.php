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
                        <th>Sent By</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Mail Group</th>
                        <th>limit-lower/limit-upper</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($mass_mails as $mass_mail)
		                      <tr>
		                        <td>{{$mass_mail->sendby->first_name." ".$mass_mail->sendby->last_name}}</td>
		                        <td>{{$mass_mail->subject}}</td>
		                        <td>{{$mass_mail->description}}</td>
                            <td>{{$mass_mail->created_at}}</td>
                            <td>{{($mass_mail->mail_group_id == 1)?"Clients":"Third Party"}}</td>
		                        <td>{{$mass_mail->limit_lower."/".$mass_mail->limit_upper}}</td>
		                        <td>
		                        	<a href="{{ URL::route('view-mass-mail', array('id' => $mass_mail->id)) }}" title="View Mass Mail"><i class="fa fa-fw fa-eye"></i></a>
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
                      <th>Action</th>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop
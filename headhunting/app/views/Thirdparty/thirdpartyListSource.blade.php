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
'form-horizontal','id' => 'login-form',  'method' => 'GET', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('email', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Vendor Email')); }} 
            <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf
                @if(Session::has('email_error'))
                    {{ Session::get('email_error') }}
                @endif
            </span>
        </div>
    </div>
    
    
    <div class="form-group">
        {{ Form::label('poc', 'Point Of Contact: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('poc', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Point Of Contact')); }} 
            <span class='errorlogin email-login'>{{$errors->first('poc');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('company_name', 'Company Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('company_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Company Name')); }} 
            <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('phone', 'Phone: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('phone', "", array('class' => 'form-control', 'placeholder' => 'ex. (704) 888-9999', "data-inputmask"=>'"mask": "(999) 999-9999"', "data-mask")); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('phone_ext', "", array('class' => 'form-control', 'placeholder' => 'ext. 121')); }} 
            <span class='errorlogin email-login'>{{$errors->first('phone_ext');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-4">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date')) }} 
            <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date')) }} 
            <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group row ">
      <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
      <div class="col-sm-3">
          {{ Form::button('Search', array('class' => 'btn btn-info', 'id' => 'search-button', 'style'=>"float:right") ); }}
      </div>
      <div class="col-sm-8">
          {{ Form::button('Download Csv', array('class' => 'btn btn-info', 'id' => 'download-button', 'style'=>"float:right") ); }}
      </div>
    </div>
{{ Form::close() }}



                <div class="box-body">
                <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Third Parties Sources :  <span class="text-bold">{{$thirdparties->getTotal()}}</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Email</th>
                        @if($id != 1)
                          <th>NCA Document</th>
                          <th>NCA Company Name</th>
                          <th>NCA Activation Date</th>
                        @else
                          <th>MSA Document</th>
                          <th>MSA Company Name</th>
                          <th>MSA Activation Date</th>
                        @endif
                        <th>Added At</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($thirdparties as $thirdparty)
		                    <tr>
								<td>{{$thirdparty->email}}</td>
                @if($id != 1)
                  @if($thirdparty->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document)))
                    <td>
                      <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                    </td>
                  @else
                    <td>-</td>
                  @endif
                  <td>{{$thirdparty->nca_company_name}}</td>
                  <td>{{($thirdparty->nca_activation_date != "" && $thirdparty->nca_activation_date != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($thirdparty->nca_activation_date)):""}}</td>
                @else
                  @if($thirdparty->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document)))
                    <td>
                      <a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                    </td>
                  @else
                    <td>-</td>
                  @endif
                  <td>{{$thirdparty->msa_company_name}}</td>
                  <td>{{($thirdparty->msa_activation_date != "" && $thirdparty->msa_activation_date != "0000-00-00 00:00:00")?date("d/m/Y", strtotime($thirdparty->msa_activation_date)):""}}</td>
                @endif
                <td>{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty->created_at)):"-"}}</td>
                <td>
                  @if($thirdparty->status == 1)
                  Blacklisted
                  @else
                  Active
                  @endif
                </td>
									<td>
										<a href="{{ URL::route('view-third-party', array('id' => $thirdparty->id)) }}" title="View Profile"><i class="fa fa-fw fa-eye"></i></a>
								  @if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
										  <a href="{{ URL::route('edit-third-party', array($thirdparty->id)) }}" title="Edit Profile"><i class="fa fa-fw fa-edit"></i></a>
								  @endif
                  @if($thirdparty->status == 1)
                    <a href="{{ URL::route('unblock-third-party', array($thirdparty->id)) }}" title="Unblock Third Party">
                    <i class="fa fa-fw fa-check text-success"></i>
                    </a>
                  @else
                    <a href="{{ URL::route('block-third-party', array($thirdparty->id)) }}" title="Blacklist Third Party"><i class="fa fa-fw fa-exclamation-triangle text-danger"></i></a>
                  @endif
										@if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
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
                        @if($id != 1)
                          <th>NCA Document</th>
                          <th>NCA Company Name</th>
                          <th>NCA Activation Date</th>
                        @else
                          <th>MSA Document</th>
                          <th>MSA Company Name</th>
                          <th>MSA Activation Date</th>
                        @endif
                        <th>Added At</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Third Parties Sources :  <span class="text-bold">{{$thirdparties->getTotal()}}</span></p>
                  </div>
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

@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                      @if($id != 1)
                        Third Party List NCA
                      @else
                        Third Party List MSA
                      @endif
                  </h3>
                </div><!-- /.box-header -->

                {{ Form::open(array('class' =>
'form-horizontal','id' => 'login-form',  'method' => 'GET', 'enctype' => 'multipart/form-data')) }}

    <div class="form-group">
        {{ Form::label('email', 'E-Mail/Domain: ', array('class' => 'col-sm-3
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
        {{ Form::label('company_name', 'Company Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('company_name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Company Name')); }} 
            <span class='errorlogin email-login'>{{$errors->first('company_name');}}@if(!empty($message)){{$message}}@endIf
                @if(Session::has('company_name_error'))
                    {{ Session::get('company_name_error') }}
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
    <!--
    <div class="form-group">
        {{ Form::label('status', 'Status: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('status', array('' => 'Please select status', '0'=>'Active', '1'=>'Blacklisted', '2'=>'MSA/NCA Incomplete'), '', array('class' => 'form-control')) }}
            <span class='errorlogin email-login'>{{$errors->first('status');}}@if(!empty($message)){{$message}}@endIf</span>
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
        <div class="col-sm-2">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-4">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    -->
    <div class="form-group row ">
      <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
      <div class="col-sm-3">
          {{ Form::button('Search', array('class' => 'btn btn-primary btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
      </div>
      <div class="col-sm-8">
          {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button', 'style'=>"float:right") ); }}
      </div>
    </div>
{{ Form::close() }}



                <div class="box-body">
                <div>
                        <p class="result-total"><span class="text-bold">{{$thirdparties->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Email<br>Company Name</th>
                        @if($id != 1)
                          <th>NCA Document</th>
                          <th>NCA Activation Date</th>
                        @else
                          <th>MSA Document</th>
                          <th>MSA Activation Date</th>
                        @endif
                        <th>Status</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @foreach($thirdparties as $thirdparty)
		                    <tr>
								<td>{{$thirdparty->email}}
                  <br>
                  <b>{{$thirdparty->organisation->name}}</b>
                </td>
                @if($id != 1)
                   @if($thirdparty->organisation->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                  </td>
                  @else
                    <td>-</td>
                  @endif
                  <td>{{($thirdparty->organisation->nca_activation_date != "" && $thirdparty->organisation->nca_activation_date != "0000-00-00 00:00:00" && $thirdparty->organisation->nca_activation_date != "1970-01-01 00:00:00")?date("d/m/Y", strtotime($thirdparty->organisation->nca_activation_date)):date("Y-m-d", strtotime($thirdparty->created_at))}}</td>
                @else
                  @if($thirdparty->organisation->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                  </td>
                  @else
                    <td>-</td>
                  @endif
                  <td>{{($thirdparty->organisation->msa_activation_date != "" && $thirdparty->organisation->msa_activation_date != "0000-00-00 00:00:00" && $thirdparty->organisation->msa_activation_date != "1970-01-01 00:00:00")?date("d/m/Y", strtotime($thirdparty->organisation->msa_activation_date)):date("Y-m-d", strtotime($thirdparty->created_at))}}</td>
                @endif
                <td>
                  @if($thirdparty->status == 1)
                      Blacklisted
                    @elseif($thirdparty->status == 2)
                      MSA/NCA Incomplete
                    @else
                      Active
                    @endif
                </td>
                <td>{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty->created_at)):"-"}}</td>
									<td>
										<a href="{{ URL::route('view-third-party', array('id' => $thirdparty->ID)) }}" title="View Profile" class="btn btn-primary btn-white">View</a>
								  @if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
										  <a href="{{ URL::route('edit-third-party', array($thirdparty->ID)) }}" title="Edit Profile"  class="btn btn-primary btn-white">Edit</a>
								  @endif
                  @if($thirdparty->status == 1)
                    <a href="{{ URL::route('unblock-third-party', array($thirdparty->ID)) }}" title="Unblock Third Party"  class="btn btn-primary btn-white">
                      Unblock
                    </a>
                  @else
                    <a href="{{ URL::route('block-third-party', array($thirdparty->ID)) }}" title="Blacklist Third Party"  class="btn btn-primary btn-white">Blacklist</a>
                  @endif
										@if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8))
											<a href="{{ URL::route('delete-third-party', array($thirdparty->ID)) }}" title="Delete Profile"  class="btn btn-secondary btn-white">Delete</a>
										@endif
								</td>
	              </tr>
						  @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Email<br>Company Name</th>
                        @if($id != 1)
                          <th>NCA Document</th>
                          <th>NCA Activation Date</th>
                        @else
                          <th>MSA Document</th>
                          <th>MSA Activation Date</th>
                        @endif
                        <th>Status</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  @if (count($thirdparties) > 0)
                    <div>
                      {{ $thirdparties->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Third Party List</h3>
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
                        <th>Email/<br>Point Of Contact/<br>Phone</th>
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>Status</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($thirdparties as $thirdparty)
		                    <tr>
  								<td>{{$thirdparty->email}} / <br/ >{{$thirdparty->poc}} / <br/>

                    {{$thirdparty->phone}}
                  </td>
                  <td>
                    @if(!empty($thirdparty->organisation) && $thirdparty->organisation->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document)))
                        <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                    @else
                      -
                    @endif
                  </td>
                  <td>
  								  @if(!empty($thirdparty->organisation) && $thirdparty->organisation->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document)))
                        <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                    @else
                      -
                    @endif
                  </td>
                  <td>
                    @if($thirdparty->status == 1)
                    Blacklisted
                    @elseif($thirdparty->status == 2)
                    MSA/NCA Incomplete
                    @else
                    Active
                    @endif
                  </td>
                  <td>{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty-> created_at)):"-"}}</td>
  									<td>
  										<a href="{{ URL::route('view-third-party', array('id' => $thirdparty->id)) }}" title="View Profile" class="btn btn-primary btn-white">View</a>
  								  @if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) || Auth::user()->hasRole(4) )
  										  <a href="{{ URL::route('edit-third-party', array($thirdparty->id)) }}" class="btn btn-primary btn-white" title="Edit Profile">Edit</a>
  								  @endif
                    @if($thirdparty->status == 1)
                      <a href="{{ URL::route('unblock-third-party', array($thirdparty->id)) }}" title="Unblock Third Party" class="btn btn-primary btn-white">
                        Unblock
                      </a>
                    @else
                      <a href="{{ URL::route('block-third-party', array($thirdparty->id)) }}" title="Blacklist Third Party" class="btn btn-secondary btn-white">Blacklist</a>
                    @endif
  									@if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
  										<a href="{{ URL::route('delete-third-party', array($thirdparty->id)) }}" title="Delete Profile" class="btn btn-secondary btn-white">Delete</a>
  									@endif
  								</td>
	              </tr>
	                   	@empty
	                   		<p>No Third Party</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Email/<br>Point Of Contact/<br>Phone</th>
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>Status</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  <div>
                  </div>
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

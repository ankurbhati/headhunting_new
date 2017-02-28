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
        {{ Form::label('name', 'Name: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('name', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Name')); }} 
            <span class='errorlogin email-login'>{{$errors->first('name');}}@if(!empty($message)){{$message}}@endIf
                @if(Session::has('name_error'))
                    {{ Session::get('name_error') }}
                @endif
            </span>
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('domain', 'Domain: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('domain', "", array('class' =>
            'form-control', 'placeholder' => 'Enter Domain')); }} 
            <span class='errorlogin email-login'>{{$errors->first('domain');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('nca_description', 'NCA Description: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('nca_description', "", array('class' =>
            'form-control', 'placeholder' => 'Enter NCA Description')); }} 
            <span class='errorlogin email-login'>{{$errors->first('nca_description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('msa_description', 'MSA Description: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('msa_description', "", array('class' =>
            'form-control', 'placeholder' => 'Enter MSA Description')); }} 
            <span class='errorlogin email-login'>{{$errors->first('msa_description');}}@if(!empty($message)){{$message}}@endIf</span>
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
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Third Parties Sources :  <span class="text-bold">{{$orgs->getTotal()}}</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>NCA Activation Date</th>
                        <th>MSA Activation Date</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($orgs as $org)
		                    <tr>
								<td>{{$org->name}}</td>
								<td>{{$org->domain}}</td>
                @if($org->nca_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->nca_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$org->id.'/'.$org->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                  </td>
                @else
                  <td>-</td>
                @endif
								@if($org->msa_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->msa_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$org->id.'/'.$org->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                  </td>
                @else
                  <td>-</td>
                @endif
                <td>{{($org->nca_activation_date != "" && $org->nca_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->nca_activation_date)):"-"}}</td>
                <td>{{($org->msa_activation_date != "" && $org->msa_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->msa_activation_date)):"-"}}</td>
                <td>{{($org->created_at != "" && $org->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->created_at)):"-"}}</td>

									<td>
										<a href="{{ URL::route('view-third-party-organisation', array('id' => $org->id)) }}" title="View Organisation"><i class="fa fa-fw fa-eye"></i></a>
								  @if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
										  <a href="{{ URL::route('edit-third-party-organisation', array($org->id)) }}" title="Edit Organisation"><i class="fa fa-fw fa-edit"></i></a>
								  @endif
									@if(Auth::user()->getRole() <= 3 || Auth::user()->hasRole(8) )
										<a href="{{ URL::route('delete-third-party-organisation', array($org->id)) }}" title="Delete Organisation"><i class="fa fa-fw fa-ban text-danger"></i></a>
									@endif
								</td>
	              </tr>
	                   	@empty
	                   		<p>No Organisation</p>
						@endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>NCA Document</th>
                        <th>MSA Document</th>
                        <th>NCA Activation Date</th>
                        <th>MSA Activation Date</th>
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Third Parties Sources :  <span class="text-bold">{{$orgs->getTotal()}}</span></p>
                  </div>
                  @if (count($orgs) > 0)
                    <div>
                      <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Backend Load</span>
                      {{ $orgs->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop
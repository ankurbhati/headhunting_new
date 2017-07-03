@extends('layouts.adminLayout')
@section('content')
<section class="content">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                    @if($id == 0)
                      NCA/MSA List
                    @elseif($id == 1)
                      NCA List
                    @elseif($id == 2)
                      MSA List
                    @elseif($id == 3)
                      No NCA/MSA List
                    @endif

                  </h3>
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
    @if($id == 0 || $id == 1)
    <div class="form-group">
        {{ Form::label('nca_description', 'NCA Description: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('nca_description', "", array('class' =>
            'form-control', 'placeholder' => 'Enter NCA Description')); }} 
            <span class='errorlogin email-login'>{{$errors->first('nca_description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    @endif
    @if($id == 0 || $id == 2)
    <div class="form-group">
        {{ Form::label('msa_description', 'MSA Description: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::text('msa_description', "", array('class' =>
            'form-control', 'placeholder' => 'Enter MSA Description')); }} 
            <span class='errorlogin email-login'>{{$errors->first('msa_description');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    @endif

    <div class="form-group">
        {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-2">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
        <div class="col-sm-2">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
            <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>

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
                        <p class="result-total"><span class="text-bold">{{$orgs->getTotal()}} results:</span></p>
                  </div>
                  <table id="employeeList" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Domain</th>
                        @if($id==0||$id==1)<th>NCA Document</th>@endif
                        @if($id==0||$id==2)<th>MSA Document</th>@endif
                        @if($id==0||$id==1)<th>NCA Activation Date</th>@endif
                        @if($id==0||$id==2)<th>MSA Activation Date</th>@endif
                        <th>Added At</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
	                    @forelse($orgs as $org)
		                    <tr>
								<td>{{$org->name}}</td>
								<td>{{$org->domain}}</td>
                @if($id==0||$id==1)
                @if($org->nca_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->nca_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$org->id.'/'.$org->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>
                  </td>
                @else
                  <td>-</td>
                @endif
                @endif
                @if($id==0||$id==2)
								@if($org->msa_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->msa_document)))
                  <td>
                    <a href="{{'/uploads/documents/'.$org->id.'/'.$org->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
                  </td>
                @else
                  <td>-</td>
                @endif
                @endif
                @if($id==0||$id==1)
                <td>{{($org->nca_activation_date != "" && $org->nca_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->nca_activation_date)):"-"}}</td>
                @endif
                @if($id==0||$id==2)
                <td>{{($org->msa_activation_date != "" && $org->msa_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->msa_activation_date)):"-"}}</td>
                @endif
                <td>{{($org->created_at != "" && $org->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->created_at)):"-"}}</td>

									<td>
										<a href="{{ URL::route('view-third-party-organisation', array('id' => $org->id, 'category'=> $id)) }}" class="btn btn-primary btn-white" title="View Organisation">View</a>
                    @if($id ==3)
                      <a href="{{ URL::route('edit-third-party-organisation', array('id' => $org->id, 'category'=> 1))  }}"  class="btn btn-primary btn-white" title="Edit Organisation">Add NCA</a>
                      <a href="{{ URL::route('edit-third-party-organisation', array('id' => $org->id, 'category'=> 2))  }}"  class="btn btn-primary btn-white" title="Edit Organisation">Add MSA</a>
                    @else
                      <a href="{{ URL::route('edit-third-party-organisation', array('id' => $org->id, 'category'=> $id))  }}"  class="btn btn-primary btn-white" title="Edit Organisation">Edit</a>
                    @endif
									@if(Auth::user()->getRole() <= 1 || Auth::user()->hasRole(8) )
										<a href="{{ URL::route('delete-third-party-organisation', array('id' => $org->id, 'category'=> $id)) }}" class="btn btn-secondary btn-white"  title="Delete Organisation">Delete</a>
									@endif
								</td>
	              </tr>
						    @endforeach
                    </tbody>
                  </table>
                  <div>
                        <p style="padding:1.9em 1.2em 0px 0px;">Total no of Third Parties Sources :  <span class="text-bold">{{$orgs->getTotal()}}</span></p>
                  </div>
                  @if (count($orgs) > 0)
                    <div>
                       
                      {{ $orgs->links() }}
                    </div>
                  @endif
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

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
'form-horizontal','id' => 'login-form',  'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    <div class="form-group">
        {{ Form::label('user_id', 'Third Party Users: ', array('class' => 'col-sm-3
        control-label')); }}
        <div class="col-sm-8">{{ Form::select('user_id', array('' => 'Please select one Valid User') + $thirdparty_owners, '', array('class' => 'form-control', 'required'=>'true')) }}
            <span class='errorlogin email-login'>{{$errors->first('user_id');}}@if(!empty($message)){{$message}}@endIf</span>
        </div>
    </div>
    <div class="form-group row ">
        @if(Session::has('error'))
            <span class="errorlogin email-login">
                {{ Session::get('error') }}
            </span>
        @endif
      <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
      <div class="col-sm-3">
          {{ Form::button('Delete', array('class' => 'btn btn-primary btn-white', 'id' => 'search-button', 'style'=>"float:right") ); }}
      </div>
    </div>
{{ Form::close() }}



              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

@stop

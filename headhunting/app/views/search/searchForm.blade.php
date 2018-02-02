@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view user-view">
  <div class="box col-sm-12">
    <div class="box-heading">Search Candidate</div>
    <div class="box-view">
        <form id="searchForm" class="form-horizontal search-box"  method="get" action="{{ URL::route('search-result', array($jobId)) }}">
        <div>
            <div class="form-group col-sm-6">
              <div class="col-xs-12">
                  <label for="region" control-label">Location</label>
                  <div >
                    {{ Form::select('region[]', [], null, array('class' => 'form-control', 'id'=>'region', "multiple" => "multiple")) }}
                    <!--<input type="text" class="form-control" name="region" />-->
                  </div>
                </div>
            </div>
            <div class="form-group col-sm-6">
              <div class="col-xs-12">
              <label for="visa"   control-label">Visa</label>
              <div >
                {{ Form::select('visa[]', $visa, null, array('class' => 'form-control', 'id' => 'visa', "multiple" => "multiple")) }}
                <!--<input type="text" class="form-control" name="visa" />-->
              </div>
              </div>
            </div>
          </div>
          <div>
            <div class="col-sm-6" style="padding-right:25px;">
              <div class="form-group">
                <div class="col-xs-6">
                  <label for="jobTitle" control-label">Job Title</label>
                  <div>
                    <input type="text" class="form-control" placeholder="Job Title" id="jobTitle" name="designation"/>
                  </div>
                </div>
                <div class="col-xs-6">
                  <label for="email" control-label">Candidate Email</label>
                  <div>
                    <input placeholder="email" type="email" class="form-control" id="email" name="email"/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-6">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from-date-without-default form-control')) }} 
                      <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                  </div>
                  <div class="col-sm-6">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to-date-without-default form-control')) }} 
                      <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                  </div>
              </div>
            </div>
            <div class="col-sm-6" style="padding-left:0;margin-left:-7px;">
              <div class="form-group">
                <div class="col-xs-12">
                  <label for="inputQuery" control-label">Query</label>
                  <div >
                    <textarea class="form-control" style="resize: none;" rows="3" id="inputQuery" name="query"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{ Form::hidden('searchQuery', '', array('id'=>'searchQuery')) }}
          {{ Form::hidden('searchType', '', array('id'=>'searchType')) }}
          <div class="form-group" style="margin-top:20px; margin-left:10px;">
              <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
              <button id="submitSearch" type="submit" class="btn btn-primary btn-white">Search</button>
          </div>
        </form>
    </div>
  </div>
</div>
@stop

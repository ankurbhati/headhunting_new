@extends('layouts.adminLayout')
@section('content')
<section class="content search-form">
          <div class="row">
            <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Search Candidate</h3>
                </div><!-- /.box-header -->
        <form class="form-horizontal search-box"  method="get" action="{{ URL::route('search-result', array($jobId)) }}">
        <div class="row searchForm">
          <div class="form-group col-sm-4">
            <label for="jobTitle" control-label">Job Title</label>
            <div >
              <input type="text" class="form-control" id="jobTitle" name="designation" />
            </div>
          </div>
          <div class="form-group col-sm-4 ">
            <label for="visa"   control-label">Visa</label>
            <div >
              {{ Form::select('visa', $visa, "", array('class' => 'form-control', 'id' => 'visa')) }}
              <!--<input type="text" class="form-control" name="visa" />-->
            </div>
          </div>
        </div>
        <div class="row searchForm">
          <div class="form-group no-padd col-sm-4">
            <div class="form-group mrg-btm-15 col-sm-12">
              <label for="region" control-label">Location</label>
              <div >
                {{ Form::select('region', [], null, array('class' => 'form-control', 'id'=>'region')) }}
                <!--<input type="text" class="form-control" name="region" />-->
              </div>
            </div>
            <div class="form-group searchDate row">
                <div class="col-sm-6">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date form-control')) }} 
                    <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                </div>
                <div class="col-sm-6">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date form-control')) }} 
                    <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                </div>
            </div>
          </div>
          <div class="form-group col-sm-4">
            <label for="inputQuery" control-label">Query</label>
            <div >
              <textarea class="form-control" style="resize: none;" rows="4" id="inputQuery" name="query"></textarea>
            </div>
          </div>
        </div>


          {{ Form::hidden('searchQuery', '', array('id'=>'searchQuery')) }}
          {{ Form::hidden('searchType', '', array('id'=>'searchType')) }}
          <div class="form-group">
            
          </div>
          <div class="form-group col-sm-12">
              <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
              <button id="submitSearch" type="submit" class="btn btn-primary btn-white">Search</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@stop

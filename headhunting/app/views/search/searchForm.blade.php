@extends('layouts.adminLayout')
@section('content')
<form class="form-horizontal" id="searchForm" method="post">
  <div class="form-group">
    <label for="jobTitle" class="col-sm-2 control-label">Job Title</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="jobTitle" name="designation" />
    </div>
  </div>
  <div class="form-group">
    <label for="visa" class="col-sm-2 control-label">Visa</label>
    <div class="col-sm-7">
      {{ Form::select('visa', $visa, "", array('class' => 'form-control', 'id' => 'visa')) }}
      <!--<input type="text" class="form-control" name="visa" />-->
    </div>
  </div>

  <div class="form-group">
    <label for="region" class="col-sm-2 control-label">Location</label>
    <div class="col-sm-7">
      {{ Form::select('region', [], null, array('class' => 'form-control', 'id'=>'region')) }}
      <!--<input type="text" class="form-control" name="region" />-->
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Query</label>
    <div class="col-sm-7">
      <textarea class="form-control" style="resize: none;" rows="2" id="inputQuery" name="query"></textarea>
    </div>
  </div>

  <div class="form-group">
      <label for="inputQuery" class="col-sm-2 control-label">Added At</label>
      <div class="col-sm-4">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from_date')) }} 
          <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
      </div>
      <div class="col-sm-3">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to_date')) }} 
          <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
      </div>
  </div>


  {{ Form::hidden('searchQuery', '', array('id'=>'searchQuery')) }}
  {{ Form::hidden('searchType', '', array('id'=>'searchType')) }}
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button id="submitSearch" type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
@stop

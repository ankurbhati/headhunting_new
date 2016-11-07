@extends('layouts.adminLayout')
@section('content')
<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Job Title</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="designation" />
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Visa</label>
    <div class="col-sm-7">
      {{ Form::select('visa', $visa, null, array('class' => 'form-control')) }}
      <!--<input type="text" class="form-control" name="visa" />-->
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Location</label>
    <div class="col-sm-7">
      {{ Form::select('region', [], null, array('class' => 'form-control', 'id'=>'region')) }}
      <!--<input type="text" class="form-control" name="region" />-->
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Query</label>
    <div class="col-sm-7">
      <textarea class="form-control" style="resize: none;" rows="2" name="query"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
@stop

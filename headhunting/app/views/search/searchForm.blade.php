@extends('layouts.adminLayout')
@section('content')
<form class="form-horizontal" method="post">
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Key Skills</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="key_skills" />
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Designation</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="designation" />
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Visa</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="visa" />
    </div>
  </div>
  <div class="form-group">
    <label for="inputQuery" class="col-sm-2 control-label">Region</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="region" />
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
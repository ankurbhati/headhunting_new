@extends('layouts.adminLayout')
@section('content')
  <div class="clock row">
      <div class="col-md-3">
        <div class="time-bg sans_francisco">
            <div class="date" id="Date_sans_francisco"></div>
              <ul>
                <li id="hours_sans_francisco"></li>
                <li id="point_sans_francisco">:</li>
                <li id="min_sans_francisco"></li>
                <li id="point_sans_francisco">:</li>
                <li id="sec_sans_francisco"></li>
              </ul>
              <p class="country">San Francisco</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="time-bg denver">
            <div class="date" id="Date_denver"></div>
                <ul>
                  <li id="hours_denver"></li>
                  <li id="point_denver">:</li>
                  <li id="min_denver"></li>
                  <li id="point_denver">:</li>
                  <li id="sec_denver"></li>
                </ul>
                <p class="country">Denver</p>
        </div >
      </div>
      <div class="col-md-3">
        <div  class="time-bg new_york ">
            <div class="date" id="Date_new_york"></div>
                <ul>
                  <li id="hours_new_york"></li>
                  <li id="point_new_york">:</li>
                  <li id="min_new_york"></li>
                  <li id="point_new_york">:</li>
                  <li id="sec_new_york"></li>
                </ul>
                <p class="country">New York</p>
        </div>
      </div>
      <div class="col-md-3">
      <div class="time-bg chicago ">
          <div class="date" id="Date_chicago"></div>
              <ul>
                <li id="hours_chicago"></li>
                <li id="point_chicago">:</li>
                <li id="min_chicago"></li>
                <li id="point_chicago">:</li>
                <li id="sec_chicago"></li>
              </ul>
              <p class="country">Chicago</p>
          </div>
      </div>
  </div>
  <div id="myModal">
    <div class="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Default Modal</h4>
          </div>
          <div class="modal-body">
            <p>One fine body…</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-white pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-white">Save changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
        <!-- /.modal -->
  </div>
@stop

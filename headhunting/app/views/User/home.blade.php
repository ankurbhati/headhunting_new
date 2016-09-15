@extends('layouts.adminLayout')
@section('content')
<div class="box box-primary">
                <div class="box-header ui-sortable-handle" style="cursor: move;">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Today Requirements</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul class="todo-list">
                  	@forelse($jobPosts as $jobPost)
	                    <li>
	                      <span class="text">{{$jobPost->title}}</span>
	                      <!-- General tools such as edit or delete-->
	                      <div class="tools">
	                      		<a href="{{ URL::route('edit-requirement', array($jobPost->id)) }}" class="text-info" title="Edit Job Post"><i class="fa fa-edit"></i> &nbsp; Edit Requirement</a> &nbsp;&nbsp; &nbsp; &nbsp;
	                        	@if($jobPost->jobsAssignedToMe()->count() == 0)
	                        		<a href="{{ URL::route('assign-requirement', array($jobPost->id)) }}" class="text-success" title="Assign To me"><i class="fa fa-plus"></i>&nbsp; Assign To Me</a>&nbsp;&nbsp; &nbsp; &nbsp;
	                        	@endif
	                        	@if(Auth::user()->getRole() <= 2)
	                        		<a href="{{ URL::route('delete-requirement', array($jobPost->id)) }}" class="text-danger" title="Delete Job Post"><i class="fa fa-trash-o text-danger"></i>&nbsp; Delete Requirement</a>
	                        	@endif
	                      </div>
	                    </li>
                    @empty
	                	<p>No Job Post Today</p>
					@endforelse
                  </ul>
                </div><!-- /.box-body -->
              </div>

              <div class="clock">
                <div style="border-radius: 10px; margin: 10px; background: #aed6f1 none repeat scroll 0% 0%;">
                    <div class="date" id="Date_india"></div>
                      <ul>
                        <li id="hours_india"></li>
                        <li id="point_india">:</li>
                        <li id="min_india"></li>
                        <li id="point_india">:</li>
                        <li id="sec_india"></li>
                      </ul>
                      <span class="country">INDIA</span>
                    </div>
                <div style="border-radius: 10px; margin: 10px; background: #33fff9 none repeat scroll 0% 0%;">
                    <div class="date" id="Date_gmt"></div>
                        <ul>
                          <li id="hours_gmt"></li>
                          <li id="point_gmt">:</li>
                          <li id="min_gmt"></li>
                          <li id="point_gmt">:</li>
                          <li id="sec_gmt"></li>
                        </ul>
                        <span class="country">GMT</span>
                    </div>
                <div style="border-radius: 10px; margin: 10px; background: #d7dbdd none repeat scroll 0% 0%;">
                    <div class="date" id="Date_usa"></div>
                        <ul>
                          <li id="hours_usa"></li>
                          <li id="point_usa">:</li>
                          <li id="min_usa"></li>
                          <li id="point_usa">:</li>
                          <li id="sec_usa"></li>
                        </ul>
                        <span class="country">United States</span>
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
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
      </div>
@stop

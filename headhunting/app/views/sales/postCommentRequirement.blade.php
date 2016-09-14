@extends('layouts.adminLayout')
@section('content')
<div class="content">
	{{ Form::open(array('route' => array('add-comment-job-post', $jobPost->id), 'class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
		<div class="form-group">
			<div class="col-sm-3 control-label"><strong>Job Title</strong></div>
			<div class="col-sm-8">{{$jobPost->title}}</div>
		</div>
		<hr>
		<div class="form-group">
				{{ Form::label('title', 'Comment: ', array('class' => 'col-sm-3
				control-label')); }}
				<div class="col-sm-8">
					{{ Form::textarea('comment', '', array('class' =>	'form-control', 'placeholder' => 'Enter Comment', 'required')); }}
					{{ Form::hidden('job_post_id', $jobPost->id); }}
					<span class='errorlogin email-login'>{{$errors->first('comment');}}@if(!empty($message)){{$message}}@endIf</span>
				</div>
		</div>
    <div class="form-group row ">
        <div class="col-sm-11">{{ Form::submit('Add Comment', array('class' => 'btn
            btn-info pull-right', 'id' => 'requirement-button') ); }}</div>
   </div>
	 {{ Form::close() }}
</div>
<div class="box-body">
	<div class="col-md-12">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Job Post Comments</h3>
                </div>
									@foreach($jobPost->comments as $comment)
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left">{{$comment->user->first_name." ".$comment->user->last_name}}</span>
                        <span class="direct-chat-timestamp pull-right">{{date('Y M, d(D) H:i', strtotime($comment->created_at))}}</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="../dist/img/avatar5.png" alt="message user image"><!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        {{$comment->comment}}
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
										<hr>
									@endforeach
                </div>
            </div>
</div>
@stop

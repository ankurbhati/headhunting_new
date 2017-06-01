@extends('layouts.adminLayout')
@section('content')
<div class="content">
  <div class="row detail-view user-view">
  <div class="box col-sm-12">
    <div class="box-heading">Job Post Comments - {{$jobPost->title}}</div>
    <div class="box-view">
                  @foreach($jobPost->comments as $comment)
                    <!-- Message. Default to the left -->
                    <div class="row direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left view-label">{{$comment->user->first_name." ".$comment->user->last_name}}</span>
                        <span class="direct-chat-timestamp view-label pull-right"> Posted At : {{date('Y M, d(D) H:i', strtotime($comment->created_at))}}</span>
                      </div>
                      <div class="direct-chat-text view-value">
                        {{$comment->comment}}
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                  @endforeach
                </div>
            </div>
</div>
	{{ Form::open(array('route' => array('add-comment-job-post', $jobPost->id), 'class' =>
	'form-horizontal','id' => 'login-form',  'method' => 'POST')) }}
		<div class="form-group">
				{{ Form::label('title', 'Add More Comment: ', array('class' => 'col-sm-12')); }}
				<div class="col-sm-12">
					{{ Form::textarea('comment', '', array('class' =>	'form-control', 'id' => 'job_post_comment', 'placeholder' => 'Enter Comment')); }}
					{{ Form::hidden('job_post_id', $jobPost->id); }}
					<span class='errorlogin email-login'>{{$errors->first('comment');}}@if(!empty($message)){{$message}}@endIf</span>
				</div>
		</div>
    <div class="form-group row ">
        <div class="col-sm-12">{{ Form::submit('Add Comment', array('class' => 'btn
            btn-primary btn-white pull-right', 'id' => 'requirement-button') ); }}</div>
   </div>
	 {{ Form::close() }}
</div>
@stop

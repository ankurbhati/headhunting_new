@extends('layouts.adminLayout')
@section('content')
<div class="row user-view">
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Email:
	        </div><div class="col-sm-8">
	        	{{$thirdparty->email}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Point Of Contact:
	        </div><div class="col-sm-8">
	        	{{$thirdparty->poc}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Phone:
	        </div><div class="col-sm-8">
	        	{{$thirdparty->phone}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Document Type:
	        </div><div class="col-sm-8">
	        	@if($thirdparty->document_type == 1)
					<td>NCA</td>
				@elseif($thirdparty->document_type == 2)
					<td>MSA</td>
				@else
					<td>-</td>
				@endif
	        </div>
	    </div>
	    @if($thirdparty->document_type != 0 && $thirdparty->document_url && file_exists(public_path('/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->document_url)))
	    <div class="row"><div class="col-sm-4">
	        Document:
	        </div><div class="col-sm-8">
	        	<a href="{{'/uploads/documents/'.$thirdparty->id.'/'.$thirdparty->document_url}}" title="Download Document" target="_blank"><i class="glyphicon glyphicon-download"></i>Download Document</a>
	        </div>
	    </div>
	    @endif
		<div class="row"><div class="col-sm-4">
	        Created By:
	        </div><div class="col-sm-8">
	        	{{$thirdparty->createdby->first_name. " ".$thirdparty->createdby->last_name }}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Updated At:
	        </div><div class="col-sm-8">
	        	{{($thirdparty->updated_at != "" && $thirdparty->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty->updated_at)):"-"}}
	        </div>
	    </div>
	</div>
</div>
@stop

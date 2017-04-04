@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view user-view">
	<div class="box col-sm-6">
		<div class="box-heading">Vendor Details</div>
		<div class="box-view">
	    <div class="row"><div class="col-sm-4 view-label">
	        Email:
	        </div><div class="col-sm-8 view-value">
	        	{{$thirdparty->email}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4 view-label">
	        Point Of Contact:
	        </div><div class="col-sm-8 view-value">
	        	{{$thirdparty->poc}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Phone:
	        </div><div class="col-sm-8 view-value">
	        	{{$thirdparty->phone}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
	        Phone Extension:
	        </div><div class="col-sm-8 view-value">
	        	{{$thirdparty->phone_ext}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4 view-label">
		        Created By:
		        </div>
		        <div class="col-sm-8 view-value">
		        	{{$thirdparty->createdby->first_name. " ".$thirdparty->createdby->last_name }}
		        </div>
		    </div>
	    </div>
	    </div>
		<div class="box col-sm-6">
			<div class="box-heading">Vendor Documents</div>
			<div class="box-view">
				<div class="row"><div class="col-sm-4 view-label">
			        Status:
			        </div><div class="col-sm-8 view-value">
			        	@if($thirdparty->status == 1)
		              		Blacklisted
		              	@elseif($thirdparty->status == 2)
		              		MSA/NCA Incomplete
		              	@else
		              		Active
		              	@endif
			        </div>
			    </div>
		    	<div class="row"><div class="col-sm-4 view-label">
		        NCA Document:
		        </div><div class="col-sm-8 view-value">
		        	@if($thirdparty->organisation->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document)))
						<a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>	
					@else
						{{"-"}}
					@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        MSA Document:
		        </div>
		        <div class="col-sm-8 view-value">
		        	@if($thirdparty->organisation->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document)))
						<a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
					@else
						{{"-"}}
					@endif
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Created At:
		        </div><div class="col-sm-8 view-value">
		        	{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("d M, Y H:i", strtotime($thirdparty->created_at)):"-"}}
		        </div>
		    </div>
		    <div class="row"><div class="col-sm-4 view-label">
		        Last Updated At:
		        </div><div class="col-sm-8 view-value">
		        	{{($thirdparty->updated_at != "" && $thirdparty->updated_at != "0000-00-00 00:00:00")?date("d M, Y H:i", strtotime($thirdparty->updated_at)):"-"}}
		        </div>
		    </div>
		    </div>
		    </div>
	</div>
@stop

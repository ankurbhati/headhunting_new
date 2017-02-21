@extends('layouts.adminLayout')
@section('content')
<div class="row user-view">
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Name:
	        </div><div class="col-sm-8">
	        	{{$org->name}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Domain:
	        </div><div class="col-sm-8">
	        	{{$org->domain}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        NCA Document:
	        </div><div class="col-sm-8">
	        	@if($org->nca_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->nca_document)))
					<a href="{{'/uploads/documents/'.$org->id.'/'.$org->nca_document}}" title="Download NCA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a>	
				@else
					{{"-"}}
				@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        NCA Description:
	        </div><div class="col-sm-8">
	        	{{$org->nca_description}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        NCA Activation Date:
	        </div><div class="col-sm-8">
	        	{{($org->nca_activation_date != "" && $org->nca_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->nca_activation_date)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        MSA Document:
	        </div>
	        <div class="col-sm-8">
	        	@if($org->msa_document && file_exists(public_path('/uploads/documents/'.$org->id.'/'.$org->msa_document)))
					<a href="{{'/uploads/documents/'.$org->id.'/'.$org->msa_document}}" title="Download MSA Document" target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a>
				@else
					{{"-"}}
				@endif
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        MSA Description:
	        </div><div class="col-sm-8">
	        	{{$org->msa_description}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        MSA Activation Date:
	        </div><div class="col-sm-8">
	        	{{($org->msa_activation_date != "" && $org->msa_activation_date != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->msa_activation_date)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($org->created_at != "" && $org->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Last Updated At:
	        </div><div class="col-sm-8">
	        	{{($org->updated_at != "" && $org->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($org->updated_at)):"-"}}
	        </div>
	    </div>
	</div>
</div>
@stop

@extends('layouts.adminLayout')
@section('content')
<div class="row detail-view user-view">
	<div class="box col-xs-12">
		<div class="box-heading">Mass Mail</div>
		<div class="box-view">
		    <div class="row"><div class="col-sm-4 view-label">
	        Sent By:
	        </div><div class="col-sm-8 view-value">
	        	{{$mass_mail->sendby->first_name." ".$mass_mail->sendby->last_name}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4  view-label">
	        Subject:
	        </div><div class="col-sm-8 view-value">
	        	{{$mass_mail->subject}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4  view-label">
	        Limit Lower/Limit Upper:
	        </div><div class="col-sm-8  view-value">
	        	{{$mass_mail->limit_lower."/".$mass_mail->limit_upper}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4  view-label">
	        Mail Group:
	        </div><div class="col-sm-8  view-value">
	        	@if($mass_mail->mail_group_id == 1)
	        		{{"Clients"}}
	        	@elseif($mass_mail->mail_group_id == 3)
	        		{{"Third Party"}}
	        	@else
	        		{{"Candidates"}}
	        	@endif
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4  view-label">
	        Created At:
	        </div><div class="col-sm-8 view-value">
	        	{{($mass_mail->created_at != "" && $mass_mail->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($mass_mail->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4  view-label">
	        Updated At:
	        </div><div class="col-sm-8 view-value">
	        	{{($mass_mail->updated_at != "" && $mass_mail->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($mass_mail->updated_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-2  view-label">
	        Description:
	        </div>
	        <div class="col-sm-12  view-value">
	        	{{$mass_mail->description}}
	        </div>
	    </div>
	    </div>
	</div>
</div>
@stop
@extends('layouts.adminLayout')
@section('content')
<div class="user-view">
	<div class="col-sm-12 right-view">
	    <div class="row"><div class="col-sm-4">
	        Sent By:
	        </div><div class="col-sm-8">
	        	{{$mass_mail->sendby->first_name." ".$mass_mail->sendby->last_name}}
	        </div>
	    </div>

	    <div class="row"><div class="col-sm-4">
	        Subject:
	        </div><div class="col-sm-8">
	        	{{$mass_mail->subject}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Description:
	        </div><div class="col-sm-8">
	        	{{$mass_mail->description}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Limit Lower/Limit Upper:
	        </div><div class="col-sm-8">
	        	{{$mass_mail->limit_lower."/".$mass_mail->limit_upper}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Mail Group:
	        </div><div class="col-sm-8">
	        	@if($mass_mail->mail_group_id == 1)
	        		{{"Clients"}}
	        	@elseif($mass_mail->mail_group_id == 3)
	        		{{"Third Party"}}
	        	@else
	        		{{"Candidates"}}
	        	@endif
	        </div>
	    </div>
		<div class="row"><div class="col-sm-4">
	        Created At:
	        </div><div class="col-sm-8">
	        	{{($mass_mail->created_at != "" && $mass_mail->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($mass_mail->created_at)):"-"}}
	        </div>
	    </div>
	    <div class="row"><div class="col-sm-4">
	        Updated At:
	        </div><div class="col-sm-8">
	        	{{($mass_mail->updated_at != "" && $mass_mail->updated_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($mass_mail->updated_at)):"-"}}
	        </div>
	    </div>
	</div>
</div>
@stop
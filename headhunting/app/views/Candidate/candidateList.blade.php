@extends('layouts.adminLayout') 
@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Candidate List</h3>
                </div>
                <!-- /.box-header -->



                {{ Form::open(array('class' => 'form-horizontal','id' => 'login-form', 'method' => 'GET', 'enctype' => 'multipart/form-data'))
                }}

                <div class="form-group">
                    {{ Form::label('email', 'E-Mail: ', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-8">{{ Form::email('email', "", array('class' => 'form-control', 'placeholder' => 'Enter Candidate Email'));
                        }}
                        <span class='errorlogin email-login'>{{$errors->first('email');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('first_name', 'First Name: ', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-8">{{ Form::text('first_name', "", array('class' => 'form-control', 'placeholder' => 'Enter Candidates First
                        Name')); }}
                        <span class='errorlogin email-login'>{{$errors->first('first_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('last_name', 'Last Name: ', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-8">{{ Form::text('last_name', "", array('class' => 'form-control', 'placeholder' => 'Enter Candidates Last
                        Name')); }}
                        <span class='errorlogin email-login'>{{$errors->first('last_name');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('visa_id', 'Visa: ', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-8">{{ Form::select('visa_id', array('' => 'Please select one Valid Visa Type') + $visa, '', array('class'
                        => 'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('visa_id');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('added_by', 'Added By:', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-8">{{ Form::select('added_by', array('' => 'Please select one Creator') + $addedBy, "", array('class' =>
                        'form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('added_by');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('from_date', 'Added At:', array('class' => 'col-sm-3 control-label')); }}
                    <div class="col-sm-2">{{ Form::text('from_date', "", array('class' => 'form-control','placeholder' => 'Enter From Date', 'class'=>'from-date-without-default
                        form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('from_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                    <div class="col-sm-2">{{ Form::text('to_date', "", array('class' => 'form-control','placeholder' => 'Enter To Date', 'class'=>'to-date-without-default
                        form-control')) }}
                        <span class='errorlogin email-login'>{{$errors->first('to_date');}}@if(!empty($message)){{$message}}@endIf</span>
                    </div>
                </div>

                <div class="form-group row ">
                    <input type="hidden" value="" id="csv_download_input" name="csv_download_input">
                    <div class="col-sm-3">
                        {{ Form::button('Search', array('class' => 'btn btn-primary btn-white pull-right', 'id' => 'search-button') ); }}
                    </div>
                    <div class="col-sm-8">
                        {{ Form::button('Download Csv', array('class' => 'btn btn-secondary btn-white', 'id' => 'download-button', 'style'=>"float:right")
                        ); }}
                    </div>
                </div>
                {{ Form::close() }}

                <div class="box-body">
                    <div>
                        <p class="result-total"><span class="text-bold">{{$candidates->getTotal()}} results :</span></p>
                    </div>
                    <table id="employeeList" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Email<br/>Full Name</br>Phone</th>
                                <th>Work State<br>Visa Id</th>
                                <th>Added By</th>
                                <th>Added At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidates as $candidate)
                            <tr>
                                <td>
                                    <b>{{$candidate->email}}</b><br/> {{$candidate->first_name. " ".$candidate->last_name
                                    }}<br/> {{$candidate->phone}}
                                </td>
                                <td>
                                    {{($candidate->workstate->id == 3)?$candidate->workstate->title:$candidate->workstate->title }}{{($candidate->source_id !=
                                    "" && $candidate->workstate->id == 3)?"(<a href='".URL::route('
                                        view-third-party ', array('id ' => $candidate->thirdparty->id))."'>".$candidate->thirdparty->email."</a>)":""}}<br>                                    {{$candidate->visa->title}}
                                </td>
                                <td>{{$candidate->added_by_name}}</td>
                                <td>
                                    {{($candidate->created_at != "" && $candidate->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($candidate->created_at)):"-"}}
                                </td>
                                <td>
                                    <a href="{{ URL::route('view-candidate', array('id' => $candidate->id)) }}" title="View Profile" class="btn btn-primary btn-white">View</a>                                    @if(Auth::user()->getRole()
                                    <=5 || Auth::user()->hasRole(8))
                                        <a href="{{ URL::route('edit-candidate', array($candidate->id)) }}" title="Edit Profile" class="btn btn-primary btn-white">Edit</a>                                        @endif @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                                        <a href="{{ URL::route('delete-candidate', array($candidate->id)) }}" title="Delete Profile" class="btn btn-primary btn-white">Delete</a>                                        @endif @if((Auth::user()->getRole()
                                        <=3 || Auth::user()->hasRole(8)) && $candidate->resume_path && file_exists(public_path('/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path)))
                                            <a href="{{'/uploads/resumes/'.$candidate->id.'/'.$candidate->resume_path}}" title="Download Resume" target="_blank" class="btn btn-secondary btn-white">Download Resume</a>                                            @endif
                                            <a target="_blank" href="{{ URL::route('candidate-application-list', array('id' => $candidate->id)) }}" title="View Profile"
                                                class="btn btn-primary btn-white">View Submission History</a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Email<br/>Full Name</br>Phone</th>
                                <th>Work State<br>Visa Id</th>
                                <th>Added By</th>
                                <th>Added At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    @if (count($candidates) > 0)
                    <div>
                        <span style="float:left; padding:1.9em 1.2em 0px 0px;font-weight: 700;">Pages</span> {{ $candidates->links()
                        }}
                    </div>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

@stop
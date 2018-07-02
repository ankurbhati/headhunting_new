<table id="employeeList" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Email/<br>Point Of Contact/<br>Phone</th>
            <th>NCA Document</th>
            <th>MSA Document</th>
            <th>Status</th>
            <th>Added At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $thirdparty)
        <tr>
            <td>{{$thirdparty->email}} / <br/>{{$thirdparty->poc}} / <br/> {{$thirdparty->phone}}
            </td>
            <td>
                @if(!empty($thirdparty->organisation) && $thirdparty->organisation->nca_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document)))
                <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->nca_document}}" title="Download NCA Document"
                    target="_blank"><i class="glyphicon glyphicon-download"></i>NCA Document</a> @else - @endif
            </td>
            <td>
                @if(!empty($thirdparty->organisation) && $thirdparty->organisation->msa_document && file_exists(public_path('/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document)))
                <a href="{{'/uploads/documents/'.$thirdparty->organisation->id.'/'.$thirdparty->organisation->msa_document}}" title="Download MSA Document"
                    target="_blank"><i class="glyphicon glyphicon-download"></i>MSA Document</a> @else - @endif
            </td>
            <td>
                @if($thirdparty->status == 1) Blacklisted @elseif($thirdparty->status == 2) MSA/NCA Incomplete @else Active @endif
            </td>
            <td>{{($thirdparty->created_at != "" && $thirdparty->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($thirdparty->
                created_at)):"-"}}
            </td>
            <td>
                <a target="_blank" href="{{ URL::route('view-third-party', array('id' => $thirdparty->id)) }}" title="View Profile" class="btn btn-primary btn-white">View</a>                @if(Auth::user()->getRole()
                <=3 || Auth::user()->hasRole(8) || Auth::user()->hasRole(4) )
                    <a target="_blank" href="{{ URL::route('edit-third-party', array($thirdparty->id)) }}" class="btn btn-primary btn-white"
                        title="Edit Profile">Edit</a> @endif @if($thirdparty->status == 1)
                    <a target="_blank" href="{{ URL::route('unblock-third-party', array($thirdparty->id)) }}" title="Unblock Third Party" class="btn btn-primary btn-white">
            Unblock
          </a> @else
                    <a target="_blank" href="{{ URL::route('block-third-party', array($thirdparty->id)) }}" title="Blacklist Third Party" class="btn btn-secondary btn-white">Blacklist</a>                    @endif 
            </td>
        </tr>
        @empty
            <p>No Third Party</p>
        @endforelse
    </tbody>
</table>
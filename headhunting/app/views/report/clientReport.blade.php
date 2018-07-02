<table id="employeeList" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Phone</th>
            <th>Created By</th>
            <th>Added At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @forelse($data as $client)
                  <tr>
                    <td>{{$client->first_name. " ".$client->last_name }}</td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->company_name}}</td>
                    <td>{{$client->phone}}</td>
                <td>{{$client->createdby->first_name. " ".$client->createdby->last_name }}</td>
                <td>{{($client->created_at != "" && $client->created_at != "0000-00-00 00:00:00")?date("Y-m-d", strtotime($client->created_at)):"-"}}</td>
                    <td>
                        <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('view-client', array('id' => $client->id)) }}" title="View">View</a>
                  @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                            <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('edit-client', array($client->id)) }}" title="Edit">Edit</a>
                            <a target="_blank" class="btn btn-secondary btn-white" href="{{ URL::route('delete-client', array($client->id)) }}" title="Delete">Delete</a>
                            <a target="_blank" class="btn btn-primary btn-white" href="{{ URL::route('transfer-client', array($client->id)) }}" title="Transfer">Transfer</a>
                            @if($client->status == 1)
                                <a target="_blank" class="btn btn-primary btn-white btn-red " href="{{ URL::route('block-client', array($client->id)) }}" title="Block">Block</a>
                            @endif
                            @if($client->status == 2)
                                <a target="_blank" class="btn btn-secondary btn-white" href="{{ URL::route('unblock-client', array($client->id)) }}" title="UnBlock">UnBlock</a>
                            @endif
                        @endif
                    </td>
                  </tr>
               @empty
                   <p>No client</p>
            @endforelse
        </tbody>
      </table>
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">

            </div>
            <div class="pull-left info">
              <p>{{Auth::user()->first_name." ".Auth::user()->last_name}}</p>
              <p>{{Auth::user()->designation}}</p>
            </div>
          </div>
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
              <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Employees</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('employee-list') }}"><i class="fa fa-user"></i>Employee List</a></li>
                <li><a href="{{ URL::route('add-employee') }}"><i class="fa fa-user-plus"></i>Add Employee</a></li>
              </ul>
            </li>
            @endif
            
            @if(Auth::user()->hasRole(1)|| Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(8))
              <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Clients</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('client-list') }}"><i class="fa fa-user"></i>Client List</a></li>
                @if(Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1))
                  <li><a href="{{ URL::route('add-client') }}"><i class="fa fa-user-plus"></i>Add Client</a></li>
                  <!-- <li><a href="{{ URL::route('client-upload') }}"><i class="fa fa-user"></i>Upload Clients</a></li> -->
                @endif
              </ul>
            </li>
            @endif
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-bookmark-o"></i> <span>Requirements</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                @if(Auth::user()->getRole() != 4)
                <li><a href="{{ URL::route('list-requirement') }}"><i class="fa fa-level-down"></i>Posted Requirements</a></li>
                @endif
                @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3))
                  <li><a href="{{ URL::route('post-requirement') }}"><i class="fa fa-plus"></i>Post Requirement</a></li>
                @endif
                <li><a href="{{ URL::route('assigned-requirement', array(Auth::user()->id)) }}"><i class="fa fa-upload"></i>Assigned Requirement</a></li>
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Candidates</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('candidate-list') }}"><i class="fa fa-user"></i>Candidate List</a></li>
                <li><a href="{{ URL::route('add-candidate') }}"><i class="fa fa-user-plus"></i>Add Candidate</a></li>
              </ul>
            </li>
	          @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8))
              <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Third Party</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('third-party-list') }}"><i class="fa fa-user"></i>Third Party List</a></li>
                <li><a href="{{ URL::route('third-party-list-with-document', array('id'=>1))}}"><i class="fa fa-user"></i>Third Party List MSA</a></li>
                <li><a href="{{ URL::route('third-party-list-with-document', array('id'=>2))}}"><i class="fa fa-user"></i>Third Party List NCA</a></li>
                <li><a href="{{ URL::route('blacklist-third-party-list')}}"><i class="fa fa-user"></i>Blacklist Third Party List</a></li>
                <li><a href="{{ URL::route('add-third-party') }}"><i class="fa fa-user-plus"></i>Add Third Party</a></li>
                <li><a href="{{ URL::route('vendor-third-party') }}"><i class="fa fa-user"></i>Upload Third Party</a></li>
                @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                  <li><a href="{{ URL::route('delete-user-third-party') }}"><i class="fa fa-user"></i>Delete Third Party</a></li>
                @endif
              </ul>
            </li>
            @endif
            @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8))
              <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>Organisations</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('third-party-organisation-list') }}"><i class="fa fa-user"></i>Organisations List</a></li>
                <li><a href="{{ URL::route('add-third-party-organisation') }}"><i class="fa fa-user-plus"></i>Add Organisation</a></li>
              </ul>
            </li>
            @endif
            <li>
              <a href="{{ URL::route('advance-search') }}">
                <i class="fa fa-search"></i> <span>Search</span>
              </a>
            </li>


            @if(Auth::user()->hasRole(1))
            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i> <span>My Team</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('salesteam') }}"><i class="fa fa-users"></i> <span>Sales Team</span></a></li>
                <li><a href="{{ URL::route('recruitmentteam') }}"><i class="fa fa-users"></i> <span>Recruitment Team</span></a></li>
              </ul>
            </li>
            @else
            <li>
              <a href="{{ URL::route('peers') }}">
                <i class="fa fa-users"></i> <span>My Team</span>
              </a>
            </li>
            @endif

            @if(Auth::user()->getRole() <= 6 || Auth::user()->hasRole(8))
            <li>
              <a href="{{ URL::route('mass-mail') }}">
                <i class="fa fa-users"></i> <span>Mass Mail</span>
              </a>
            </li>
            @endif
            @if(Auth::user()->getRole() <= 6 || Auth::user()->hasRole(8))
            <li>
              <a href="{{ URL::route('mass-mail-list') }}">
                <i class="fa fa-users"></i> <span>Mail List</span>
              </a>
            </li>
            @endif
            <li>
              <a href="{{ URL::route('list-submittel') }}">
                <i class="fa fa-eye"></i> <span>Job Submittals</span>
              </a>
            </li>
            @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
            <li>
              <a href="{{ URL::route('settings') }}">
                <i class="fa fa-eye"></i> <span>Settings</span>
              </a>
            </li>
            @endif
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

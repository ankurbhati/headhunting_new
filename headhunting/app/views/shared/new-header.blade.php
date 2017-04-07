<header class="loggedin">
        <div class="top-header-wrapper">
          <div class="container">
            <a class="logo" href="{{ URL::route('dashboard-view') }}">
              <img src="/assets/images/logo.png" alt="logo"/>
            </a>
            <ul class="header-items">
              @if(!Auth::check())
                <li class="login-container">
                  <a class="icon-member_signout member-login" href="">
                    <span class="hidden-moblet">MEMBER LOGIN</span>
                  </a>
                </li>
              @endif
              @if(Auth::check())
                <li class="logout-container dropdown theme-caret">
                  <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img src="/assets/images/header_default.png" alt="user"/>
                    <span class="hidden-moblet">{{Auth::user()->first_name." ".Auth::user()->last_name}}</span>
                  </a>
                   <ul class="dropdown-menu hidden-moblet">
                    <li><a href="{{ URL::route('view-member', array('id' => Auth::user()->id)) }}">View Profile</a></li>
                    <li><a href="{{ URL::route('edit-member', array('id' => Auth::user()->id)) }}">Edit Profile</a></li>
                    <li><a href="{{ URL::route('change-password', array('id' => Auth::user()->id)) }}">Change Password</a></li>
                    <li><a href="{{ URL::route('logout-member') }}">Logout</a></li>
                  </ul>
                </li>
                <li class="navbar-hamburg">
                  <span class="hamburg"></span>
                  <span class="hamburg"></span>
                  <span class="hamburg"></span>
                  <span class="close icon-sm_navClose"></span>
                </li>
              @endif
            </ul>
          </div>
        </div>
        <nav class="navbar hidden-moblet">
          <ul class="navbar-nav">
            @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
              <li class="dropdown open">
                <a href="#">Employee</a>
                <ul class="dropdown-menu">
                  <li class="dropdown padd-left-15">
                    <a href="{{ URL::route('employee-list') }}">Employee List</a>
                  </li>
                  <li class="dropdown">
                    <a href="{{ URL::route('add-employee') }}">Add Employee</a>
                  </li>
                </ul>
              </li>
            @endif
            <li class="dropdown">
                <a href="#">Sales</a>
                <ul class="dropdown-menu">
                @if(Auth::user()->getRole() != 4)
                  <li class="dropdown">
                    <a href="{{ URL::route('list-requirement') }}">
                      Posted Requirements
                    </a>
                  </li>
                @endif
                @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8) || Auth::user()->hasRole(2) || Auth::user()->hasRole(3))
                  <li class="dropdown">
                    <a href="{{ URL::route('post-requirement') }}">Post Requirement</a></li>
                @endif
                @if(Auth::user()->getRole() != 4)
                  <li class="dropdown">
                    <a href="{{ URL::route('pending-requirement') }}">
                      Pending Requirements
                    </a>
                  </li>
                @endif
                  <li class="dropdown">
                    <a href="{{ URL::route('assigned-requirement', array(Auth::user()->id)) }}">
                      Assigned Requirement</a>
                  </li>
                  <li class="dropdown">
                    <a href="{{ URL::route('client-list') }}">Client List</a>
                  </li>
                  @if(Auth::user()->hasRole(2) || Auth::user()->hasRole(3) || Auth::user()->hasRole(1))
                    <li class="dropdown"><a href="{{ URL::route('add-client') }}">
                      Add Client</a>
                    </li>
                    <li class="dropdown">
                      <a href="{{ URL::route('client-upload') }}">
                      Upload Clients</a>
                    </li>
                  @endif
                </ul>
              </li>
              <li class="dropdown">
                <a href="#">Recruitment</a>
                <ul class="dropdown-menu">
                  <li class="dropdown dropdown-child">
                    <a href="javascript:void(0);">
                      <span>Job Submittals</span>
                    </a>
                    <div class="dropdown-menu-child">
                      <ul class="inline-items">
                        <li class="visible-moblet close-me">
                          <a href="#"><i class="fa fa-caret-left"></i><span class="sr-only">Back</span></a>
                        </li>
                        <li class="dropdown-item">
                          <a href="{{ URL::route('list-submittel') }}">
                            <span>All Job Submittals</span>
                          </a>
                        </li>
                        <li class="dropdown-item">
                          <a href="{{ URL::route('interview-scheduled-submittel') }}">
                            <span>Interview Scheduled Submittals</span>
                          </a>
                        </li>
                        <li class="dropdown-item">
                          <a href="{{ URL::route('selected-submittel') }}">
                            <span>End Client Selected Submittals</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li class="dropdown">
                    <a href="{{ URL::route('candidate-list') }}">Candidate List</a>
                  </li>
                  <li class="dropdown">
                    <a href="{{ URL::route('add-candidate') }}">Add Candidate</a>
                  </li>
                  @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8))
                    <li class="dropdown">
                      <a href="{{ URL::route('third-party-organisation-list') }}">
                      Organisations List</a>
                    </li>
                    <li class="dropdown">
                      <a href="{{ URL::route('add-third-party-organisation') }}">
                      Add Organisation</a>
                    </li>
                  @endif
                </ul>
              </li>
              @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(4) || Auth::user()->hasRole(5) || Auth::user()->hasRole(8))
                <li class="dropdown">
                  <a href="#">Third Party</a>
                  <ul class="dropdown-menu">
                      <li class="dropdown">
                        <a href="{{ URL::route('third-party-list') }}">Third Party List</a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('third-party-list-with-document', array('id'=>1))}}">Third Party List MSA</a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('third-party-list-with-document', array('id'=>2))}}">Third Party List NCA</a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('blacklist-third-party-list')}}">
                          Blacklist Third Party List</a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('add-third-party') }}">Add Third Party</a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('vendor-third-party') }}">Upload Third Party</a>
                      </li>
                  </ul>
                </li>
              @endif
              <li class="dropdown">
                <a href="#">Search & Mail</a>
                <ul class="dropdown-menu">
                  @if(Auth::user()->getRole() <= 6 || Auth::user()->hasRole(8))
                    <li class="dropdown">
                      <a href="{{ URL::route('mass-mail') }}">
                        <span>Mass Mail</span>
                      </a>
                    </li>
                    <li class="dropdown">
                      <a href="{{ URL::route('mass-mail-list') }}">
                          <span>Mail List</span>
                      </a>
                    </li> 
                  @endif
                  <li class="dropdown">
                    <a href="{{ URL::route('advance-search') }}">
                      <span>Search</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#">Admin</a>
                <ul class="dropdown-menu">
                  @if(Auth::user()->hasRole(1))
                      <li class="dropdown padd-left-15">
                        <a href="{{ URL::route('salesteam') }}">
                          <span>Sales Team</span>
                        </a>
                      </li>
                      <li class="dropdown">
                        <a href="{{ URL::route('recruitmentteam') }}">
                          <span>Recruitment Team</span>
                        </a>
                      </li>
                  @else
                    <li class="dropdown padd-left-15">
                      <a href="{{ URL::route('peers') }}">
                        <span>My Team</span>
                      </a>
                    </li>
                  @endif
                  @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                    <li class="dropdown padd-left-15">
                      <a href="{{ URL::route('settings') }}">
                        <span>Settings</span>
                      </a>
                    </li>
                  @endif
                    <li class="dropdown padd-left-15">
                      <a href="{{ URL::route('work-report') }}">
                        <span>Upload Report</span>
                      </a>
                    </li>
                    @if(Auth::user()->hasRole(1) || Auth::user()->hasRole(8))
                      <li class="dropdown">
                        <a href="{{ URL::route('delete-user-third-party') }}">Delete Third Party</a>
                      </li>
                    @endif
                </ul>
              </li>
          </ul>
          <div class="contact-tab visible-moblet">
            <a href="{{ URL::route('logout-member') }}">
              <i class="fa fa-envelope-o" aria-hidden="true"></i>
              <span>Logout</span>
            </a>
          </div>
  </nav>
      </header>
      <header class="main-header">
        <!-- Logo -->
        <a href="{{ URL::route('dashboard-view') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>HRE</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Headhunting</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
		@if(Auth::check())
        <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
	          <div class="navbar-custom-menu">
	            <ul class="nav navbar-nav">
	              <li class="dropdown user user-menu">
	                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                  <span class="hidden-xs">{{Auth::user()->first_name." ".Auth::user()->last_name}}<i class="fa margin-left-15 fa-caret-down"></i></span>
	                </a>
	                <ul class="dropdown-menu">
	                  <!-- User image -->
	                  <li class="user-header">
	                    <p>
	                      {{Auth::user()->first_name." ".Auth::user()->last_name}}
	                      <small>{{Auth::user()->designation}}</small>
	                    </p>
	                  </li>
	                  <!-- Menu Body -->
	                  <li class="user-body">
	                    <div class="col-xs-6 text-center right-border">
	                      <a href="{{ URL::route('change-password', array('id' => Auth::user()->id)) }}">Change Password</a>
	                    </div>
	                    <div class="col-xs-6 text-center" style="margin:0;">
	                      <a href="{{ URL::route('view-member', array('id' => Auth::user()->id)) }}">View Profile</a>
	                    </div>
	                  </li>
	                  <!-- Menu Footer-->
	                  <li class="user-footer">
	                    <div class="pull-left">
	                      <a href="{{ URL::route('edit-member', array('id' => Auth::user()->id)) }}" class="btn btn-default btn-flat">Edit Profile</a>
	                    </div>
	                    <div class="pull-right">
	                      <a href="{{ URL::route('logout-member') }}" class="btn btn-default btn-flat">Sign out</a>
	                    </div>
	                  </li>
	                </ul>
	              </li>
	            </ul>
	          </div>
	      @endif
        </nav>
      </header>

@php
$user = auth()->guard('admin')->user();
@endphp
<div class="col-md-3 left_col"> <!-- menu_fixed class for fixed sidebar -->
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="{{route('admin-dashboard')}}" class="site_title"><img class="sidebar-logo" src="{{ URL::asset('frontend/assets/images/logoOneLine.png') }}" alt=""></a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile clearfix">
        <div class="profile_pic">
          <img src="{{URL::asset('uploads/admin/profile_picture/thumb/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" class="img-circle profile_img">
        </div>
        <div class="profile_info">
          <span>Welcome,</span>
          <h2>{{auth()->guard('admin')->user()->first_name}} {{auth()->guard('admin')->user()->last_name}}</h2>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <h3>General</h3>
          <ul class="nav side-menu">
            <li>
                <a href="{{route('admin-dashboard')}}"><i class="fa fa-home"></i> Dashboard </span></a>
            </li>

            <li>
                <a href="javascript:void(0);"><i class="fa fa-user"></i> Admins <span class="fa fa-chevron-down"></span></a>

                <ul class="nav child_menu">
                    <li><a href="{{ route('roles.index') }}">Roles</a></li>
                    <li><a href="{{ route('permissions.index') }}">Permissions</a></li>
                    <li><a href="{{ route('admins.index') }}">Admin</a></li>
                </ul>

            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-user"></i> Workers <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('workers.create') }}">Add Worker</a></li>
                    <li><a href="{{ route('workers.index') }}">Workers</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-hospital-o"></i> Facilities <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="#">Add Facility</a></li>
                    <li><a href="#">Facilities</a></li>
                    <li><a href="#">Departments</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-search"></i> Recruiters <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('recruiters.create')}}">Add Recruiter</a></li>
                    <li><a href="{{route('recruiters.index')}}">Recruiters</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-suitcase"></i> Jobs <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('jobs.create')}}">Add Job</a></li>
                    <li><a href="{{route('jobs.index')}}">Jobs</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-list"></i> Keywords <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('keywords.create')}}">Add Keyword</a></li>
                    <li><a href="{{route('keywords.index')}}">Keywords</a></li>
                </ul>
            </li>

            <li>
                <a href="{{route('tickets.index')}}"><i class="fa fa-support"></i> Help & Support</a>
            </li>
          </ul>
        </div>
        <div class="menu_section">
          <h3>Settings</h3>
          <ul class="nav side-menu">
            <li><a><i class="fa fa-gear"></i> Settings <span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                    <li>
                        <a>Email Templates<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="sub_menu">
                                <a href="{{route('email-templates.create')}}">Add Email Template</a>
                            </li>
                            <li>
                                <a href="{{route('email-templates.index')}}">Email Templates</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>States<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="sub_menu">
                                <a href="{{route('states.create')}}">Add State</a>
                            </li>
                            <li>
                                <a href="{{route('states.index')}}">States</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Mange Profession <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-profession')}}">Add Profession</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.profession')}}">Professions</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Manage Speciality <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-speciality')}}">Add Speciality</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.speciality')}}">Specialities</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Mange MSP <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-msp')}}">Add MSP</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.msp')}}">MSPs</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Manage VMS <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-vms')}}">Add VMS</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.vms')}}">VMS</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Mange Shift <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-shift')}}">Add Shift</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.shift')}}">Shifts</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>Manage Clinical Setting <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a href="{{route('add-clinical')}}">Add Clinical Setting</a>
                            </li>
                            <li class="sub_menu">
                                <a href="{{route('key.clinical')}}">Clinical Settings</a>
                            </li>
                        </ul>
                    </li>
                  {{-- <li><a href="#level1_2">Level One</a></li> --}}
              </ul>
            </li>
            {{-- <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li> --}}
          </ul>
        </div>

      </div>
      <!-- /sidebar menu -->

      <!-- /menu footer buttons -->
      <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Profile Settings" href="{{route('admin-profile')}}">
          <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
          <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Lock">
          <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{route('admin-logout')}}">
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      </div>
      <!-- /menu footer buttons -->
    </div>
</div>

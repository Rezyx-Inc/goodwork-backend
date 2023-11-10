@php
$user = auth()->guard('admin')->user();
@endphp
<div class="top_nav ss-top-nav-sec">
    <div class="nav_menu">
        <!-- <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div> -->
        <nav class="nav navbar-nav ss-dash-nav-mn-sec">
          <div class="col-lg-6">
          <div class="ss-dsh-adm-hed-txt-lft">
            <h4>Hello, Admin</h4>
            <p>Welcome Back!</p>
          </div>
        </div>
<div class="col-lg-6">
        <ul class=" navbar-right">
          <li class="nav-item dropdown open" style="padding-left: 15px;">
            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
              <img src="{{URL::asset('public/uploads/admin/profile_picture/thumb/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" alt="Avatar">{{auth()->guard('admin')->user()->first_name.' '.auth()->guard('admin')->user()->last_name}}
            </a>
            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item"  href="{{route('admin-profile')}}"> Profile</a>
                {{-- <a class="dropdown-item"  href="javascript:;">
                  <span class="badge bg-red pull-right">50%</span>
                  <span>Settings</span>
                </a>
                <a class="dropdown-item"  href="javascript:;">Help</a> --}}
              <a class="dropdown-item"  href="{{route('admin-logout')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
            </div>
          </li>

          <li role="presentation" class="nav-item dropdown open ss-noti-li-sec">
            <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
              <img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/notification-icon.png" />
              <span class="badge bg-green">6</span>
            </a>
            <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown1">
              <li class="nav-item">
                <a class="dropdown-item">
                  <span class="image"><img src="{{URL::asset('public/backend/assets/images/img.jpg')}}" alt="Profile Image" /></span>
                  <span>
                    <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                  </span>
                  <span class="message">
                    Film festivals used to be do-or-die moments for movie makers. They were where...
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="dropdown-item">
                  <span class="image"><img src="{{URL::asset('public/backend/assets/images/img.jpg')}}" alt="Profile Image" /></span>
                  <span>
                    <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                  </span>
                  <span class="message">
                    Film festivals used to be do-or-die moments for movie makers. They were where...
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="dropdown-item">
                  <span class="image"><img src="{{URL::asset('public/backend/assets/images/img.jpg')}}" alt="Profile Image" /></span>
                  <span>
                    <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                  </span>
                  <span class="message">
                    Film festivals used to be do-or-die moments for movie makers. They were where...
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="dropdown-item">
                  <span class="image"><img src="{{URL::asset('public/backend/assets/images/img.jpg')}}" alt="Profile Image" /></span>
                  <span>
                    <span>John Smith</span>
                    <span class="time">3 mins ago</span>
                  </span>
                  <span class="message">
                    Film festivals used to be do-or-die moments for movie makers. They were where...
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <div class="text-center">
                  <a class="dropdown-item">
                    <strong>See All Alerts</strong>
                    <i class="fa fa-angle-right"></i>
                  </a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      </nav>
    </div>
</div>

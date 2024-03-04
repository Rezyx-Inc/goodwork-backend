@php
$user = auth()->guard('frontend')->user();
@endphp
<!-- Navbar -->
<nav id="main-navbar" class="ss-hed-top-fixd navbar navbar-expand-lg navbar-light fixed-top">
  <!-- Container wrapper -->
  <div class="container-fluid ss-top-hed-cont-pd">

    <!-- Brand -->

    <div class="col-lg-4">
      <div class="ss-dash-wel-div">
        <h5>Hi, {{ $user->first_name }}!</h5>
        <p>Welcome Back!</p>
      </div>
    </div>

    <div class="col-lg-4 col-12">
      <div class="ss-dash-serch-bx">
        <div class="input-group">
          <div class="form-outline">
            <input type="search" id="form1" class="form-control" placeholder="Search anything...">
          </div>
          <button type="button" class="ssbtn">
            <i class="fas fa-search" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="ss-dash-hed-noti-divv">
        <!-- Right links -->
        <ul class="ssnavbar-nav">
          <!-- Search form -->

          <!-- Notification dropdown -->

          <li class="nav-item dropdown ss-bell-sec-li">
            <a class="ss-bell-sec nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell"></i>
              <span class="badge rounded-pill badge-notification bg-danger"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="#">Some news</a></li>
              <li><a class="dropdown-item" href="#">Another news</a></li>
              <li>
                <a class="dropdown-item" href="#">Something else here</a>
              </li>
            </ul>
          </li>


          <!-- Icon dropdown -->

          <!-- Avatar -->
          <li class="nav-item dropdown">
            <a class="ss-hed-user-log-sec nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <img src="{{URL::asset('frontend/img/profile-icon-img.png')}}" class="rounded-circle" height="40" alt="" loading="lazy" /> <span class="color-dark">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item" href="{{route('profile')}}">My profile</a></li>
              <li><a class="dropdown-item" href="{{route('profile-setting')}}">Settings</a></li>
              <li><a class="dropdown-item" href="{{route('worker.logout')}}">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>


    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <!-- Container wrapper -->
</nav>

@php
$user = auth()->guard('employer')->user();
@endphp
<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">

    <div class="ss-side-user-ul">

        <ul>
            <li><img src="{{URL::asset('frontend/img/logo.png')}}" /></li>
        </ul>
    </div>
    <div class="position-sticky ss-das-side-menu-sec">
    <div class="list-group list-group-flush">
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-dashboard') ? 'active' : '' }}" aria-current="true">
        <img src="{{URL::asset('recruiter/assets/images/r-home-menu-icon.png')}}" /><span>Home</span>
        </a>

        <a href="{{ route('explore-employees') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-application') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-appliction-icon.png')}}" /><span>Explore Employees</span>
        </a>


        <a href="{{ route('employer-opportunities-manager') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-opportunities-manager') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-opp-icon.png')}}" /><span>Opportunities Manager</span></a>

        <a href="{{ route('employer-messages') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-messages') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-message-icon.png')}}" /><span>Messages</span></a>

        <a href="{{ route('employer-profile') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-profile') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-profile-icon.png')}}" /><span>My Profile</span>
        </a>
    </div>
    </div>
    <div class="ss-dash-logout-sec">
        <a href="{{route('recruiter-logout')}}"> Logout <img src="{{URL::asset('frontend/img/logout.png')}}" /></a>
    </div>
</nav>

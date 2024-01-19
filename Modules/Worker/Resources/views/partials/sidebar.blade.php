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
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-dashboard') ? 'active' : '' }}" aria-current="true">
        <img src="{{URL::asset('employer/assets/images/r-home-menu-icon.png')}}" /><span>Home</span>
        </a>

        <a href="{{ route('explore-employees') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-application') ? 'active' : '' }}">
        <img src="{{URL::asset('employer/assets/images/r-appliction-icon.png')}}" /><span>Explore Employees</span>
        </a>


        <a href="{{ route('employer-opportunities-manager') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-opportunities-manager') ? 'active' : '' }}">
        <img src="{{URL::asset('employer/assets/images/r-opp-icon.png')}}" /><span>Opportunities Manager</span></a>

        <a href="{{ route('employer-messages') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-messages') ? 'active' : '' }}">
        <img src="{{URL::asset('employer/assets/images/r-message-icon.png')}}" /><span>Messages</span></a>

        <a href="{{ route('employer-profile') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-profile') ? 'active' : '' }}">
        <img src="{{URL::asset('employer/assets/images/r-profile-icon.png')}}" /><span>My Profile</span>
        </a>

        <a href="{{ route('employer-keys') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('employer/employer-profile') ? 'active' : '' }}">
        <img src="{{URL::asset('employer/assets/images/plus-icon-white.png')}}" /><span>Check you API keys</span>
        </a>

    </div>
    </div>
    <div class="ss-dash-logout-sec">
        <a href="{{route('employer-logout')}}"> Logout <img src="{{URL::asset('frontend/img/logout.png')}}" /></a>
    </div>
</nav>

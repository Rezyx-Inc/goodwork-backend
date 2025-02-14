@php
$user = auth()->guard('recruiter')->user();
@endphp
<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">

    <div class="ss-side-user-ul">
        <ul>
            <a href="{{ route('/') }}">
                <img src="{{ URL::asset('frontend/img/logo.png') }}" />
            </a>
        </ul>
    </div>
    <div class="position-sticky ss-das-side-menu-sec">
    <div class="list-group list-group-flush">
        <a href="{{ route('recruiter-dashboard') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-dashboard') ? 'active' : '' }}" aria-current="true">
        <img src="{{URL::asset('recruiter/assets/images/r-home-menu-icon.png')}}" /><span>Home</span>
        </a>
        <a href="{{ route('recruiter-application', ['view' => 'Apply'])  }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-application') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-appliction-icon.png')}}" /><span>Application Journey</span>
        </a>


        <a href="{{ route('recruiter-opportunities-manager') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-opportunities-manager') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-opp-icon.png')}}" /><span>Opportunities Manager</span></a>

        <a href="{{ route('recruiter-messages') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-messages') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-message-icon.png')}}" /><span>Messages</span></a>

        <a  href="{{route('recruiter-profile', ['type' => 'profile']) }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('recruiter/recruiter-profile/profile') ? 'active' : '' }}">
        <img src="{{URL::asset('recruiter/assets/images/r-profile-icon.png')}}" /><span>My Profile</span>
        </a>
    </div>
    </div>
    <div class="ss-dash-logout-sec">
        <a href="{{route('recruiter-logout')}}"> Logout <img src="{{URL::asset('frontend/img/logout.png')}}" /></a>
    </div>
</nav>

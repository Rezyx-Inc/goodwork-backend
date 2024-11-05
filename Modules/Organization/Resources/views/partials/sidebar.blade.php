@php
$user = auth()->guard('organization')->user();
@endphp
<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">

    <div class="ss-side-user-ul">
        <ul>

            <li><img src="{{URL::asset('frontend/img/logo.png')}}" /></li>
        </ul>
    </div>
    <div class="position-sticky ss-das-side-menu-sec">
    <div class="list-group list-group-flush">
        <a href="{{ route('organization-dashboard') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/organization-dashboard') ? 'active' : '' }}" aria-current="true">
        <img src="{{URL::asset('organization/assets/images/r-home-menu-icon.png')}}" /><span>Home</span>
        </a>

        <a href="{{ route('organization-application') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/organization-application') ? 'active' : '' }}">
        <img src="{{URL::asset('organization/assets/images/r-appliction-icon.png')}}" /><span>Application Journey</span>
        </a>


        <a href="{{ route('organization-opportunities-manager') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/organization-opportunities-manager') ? 'active' : '' }}">
        <img src="{{URL::asset('organization/assets/images/r-opp-icon.png')}}" /><span>Opportunities Manager</span></a>

        {{-- <a href="{{ route('organization-messages') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/organization-messages') ? 'active' : '' }}">
        <img src="{{URL::asset('organization/assets/images/r-message-icon.png')}}" /><span>Messages</span></a> --}}

        <a  href="{{route('organization-profile', ['type' => 'profile']) }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/organization-profile/profile') ? 'active' : '' }}">
        <img src="{{URL::asset('organization/assets/images/r-profile-icon.png')}}" /><span>My Profile</span>
        </a>

        <a href="{{ route('organization-keys') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/keys') ? 'active' : '' }}">
        <img src="{{URL::asset('organization/assets/images/manage_api_keys.svg')}}" /><span>Check your API keys</span>
        </a>

        <a href="{{ route('organization-recruiters-management') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/recruiters-management') ? 'active' : '' }}">
            <img src="{{URL::asset('organization/assets/images/manage_recruiters.svg')}}" /><span>Manage your recruiters</span>
            </a>

            <a href="{{ route('get_preferences') }}" class="list-group-item list-group-item-action py-2 ripple shadow-none {{ request()->is('organization/get-preferences') ? 'active' : '' }}">
                <img src="{{URL::asset('organization/assets/images/manage_rules.svg')}}" /><span>Manage your rules</span>
                </a>
    </div>
    </div>
    <div class="ss-dash-logout-sec">
        <a href="{{route('organization-logout')}}"> Logout <img src="{{URL::asset('frontend/img/logout.png')}}" /></a>
    </div>
</nav>

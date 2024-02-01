@php
$user = auth()->guard('frontend')->user();
@endphp
<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse">

    <div class="ss-side-user-ul">
        <ul>
            <li>
                <a href="{{route('/')}}">
                    <img src="{{URL::asset('frontend/img/logo.png')}}" />
                </a>
            </li>
        </ul>
    </div>
    <div class="position-sticky ss-das-side-menu-sec">
    <div class="list-group list-group-flush">
        <a href="{{route('worker.dashboard')}}" class="list-group-item list-group-item-action py-2 ripple {{ (request()->route()->getName() == 'worker.dashboard') ? 'active':''}}" aria-current="true">
            <img src="{{URL::asset('frontend/img/home-icon.png')}}" /><span>Home</span>
        </a>

        <a href="{{route('worker.explore')}}" class="list-group-item list-group-item-action py-2 ripple {{ (request()->route()->getName() == 'worker.explore') ? 'active':''}}">
            <img src="{{URL::asset('frontend/img/my-profile-icon.png')}}" /><span>Explore Jobs</span>
        </a>


        <a href="{{route('my-work-journey')}}" class="list-group-item list-group-item-action py-2 ripple {{ (request()->route()->getName() == 'my-work-journey') ? 'active':''}}">
            <img src="{{URL::asset('frontend/img/my-profile-icon.png')}}" /><span>My Work Journey</span>
        </a>

        <a href="{{ route('worker.messages') }}" class="list-group-item list-group-item-action py-2 ripple {{ (request()->route()->getName() == 'worker.messages') ? 'active':''}}">
            <img src="{{URL::asset('frontend/img/message-icon.png')}}" /><span>Messages</span>
        </a>

        <a href="{{route('profile')}}" class="list-group-item list-group-item-action py-2 ripple {{ (request()->route()->getName() == 'profile') ? 'active':''}}">
            <img src="{{URL::asset('frontend/img/my-profile-icon.png')}}" /><span>My Profile</span>
        </a>


    </div>


    </div>

    <div class="ss-dash-logout-sec">
        <a href="{{route('worker.logout')}}"> Logout <img src="{{URL::asset('frontend/img/logout.png')}}" /></a>
    </div>


</nav>

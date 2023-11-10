<!-------- Mobile view menu section -------->
@php
$user = auth()->guard('frontend')->user();
@endphp
<div class="mobile-view">
    <div class="logo-sec">
        <div class="clearfix d-flex">
            <div class="col-xs-6 col-4">
                <a href="{{route('/')}}"><img src="{{asset('public/frontend/dashboard/images/logo.png')}}" alt="" class="img-responsive"></a>
            </div>
            <div class="col-xs-6 col-8 text-right">
                <div class="mobtop">

                    <div class="top-right-btn">
                        <ul class="list-inline header-top pull-right">
                            {{-- <il class="share_btn">
                                <a href="#" data-toggle="modal" data-target="#exampleModalLong"><i class="fa fa-share" aria-hidden="true"></i>Share</a>
                            </il> --}}
                            {{-- <li>
                                <a href="#" class="icon-info" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell-o" aria-hidden="true"></i>
                                    <span class="label label-primary"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right notificationdrop nw-drp">
                                    <li>
                                        <div class="notifi_media">
                                            <div class="media" wire:key="notification_20">
                                                <i class="icofont-notification"></i>
                                                <div class="media-body">
                                                    <h5 class="heading unread ">
                                                        <a href="javascript:void(0);" class="font-weight-bold">
                                                            Please leave a public review for jackusername  so others will benefit
                                                        </a>
                                                    </h5>
                                                    <span class="date font-weight-bold">7:29 PM, Mar 31, 2023</span>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="notifi_media">
                                            <div class="media">
                                                <i class="icofont-notification"></i>
                                                <div class="media-body">
                                                    <h5 class="heading unread ">
                                                        <a href="javascript:void(0);" class="font-weight-bold">
                                                            Please leave a public review for jackusername  so others will benefit
                                                        </a>
                                                    </h5>
                                                    <span class="date font-weight-bold">7:29 PM, Mar 31, 2023</span>
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li> --}}
                            <li class="">
                                <div class="dropdown dash-drop">
                                    <span data-toggle="dropdown" aria-expanded="false">
                                        <img class="img-responsive rounded-circle headr-prof-pic"
                                             src="{{ asset('public/uploads/frontend/profile_picture/thumb/'.$user->profile_picture)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" alt="profile-picture">
                                        <h1>{{$user->first_name}}<i class="icofont-caret-down"></i></h1>
                                    </span>
                                    <ul class="dropdown-menu dropdown-menu-right nw-drp alignarea">
                                        <li><a href="{{route('account-setting')}}" data-original-title="" title=""><i
                                                    class="icofont-gear"></i>&nbsp;Account Settings</a>
                                        </li>
                                        <li>
                                            <a href="{{route('subscription')}}" data-original-title="" title="">
                                                <i class="icofont-package"></i>&nbsp;Subscription
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('transactions')}}" data-original-title="" title="">
                                                <i class="fa fa-history"></i>&nbsp;Transaction History
                                            </a>
                                        </li>
                                        <li><a href="{{route('logout')}}" data-original-title="" title=""><i
                                                    class="fa fa-power-off" aria-hidden="true"></i>&nbsp;Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <a href="javascript:void(0);" id="MobilesidebarToggle" class="bgr-mnu">
                        <span class="navbar-toggler-icon"></span>
                        <div class="clearfix"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="mobile-menu-link" style="display: none;">
        <ul>
            <li class="{{ ( in_array(Request::route()->getName(),['add-field']) ) ?'active':''}}"><a href="{{route('add-field')}}"><i class="icofont-gear"></i> Settings</a></li>
            <li class="{{Route::is('dashboard')?'active':''}}"><a href="{{route('dashboard')}}"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
            @if($user->type_id == '2')
            <li class="{{Route::is('verifed-users')?'active':''}}"><a href="{{route('verifed-users')}}"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Team</a></li>
            @endif
            <li class="{{ ( in_array(Request::route()->getName(),['contacts','add-contact']) ) ?'active':''}}"><a href="{{route('contacts')}}"><i class="icofont-ui-call"></i> Contacts</a></li>
            <li class="{{ ( in_array(Request::route()->getName(),['calls']) ) ?'active':''}}"><a href="{{route('calls')}}"><i class="fa fa-check"></i> Calls completed</a></li>
            <li class="{{Route::is('call-page')?'active':''}}"><a href="{{route('call-page')}}"><i class="fa fa-clock-o"></i> Calls pending</a></li>
            {{-- <li class="{{Route::is('account-setting')?'active':''}}"><a href="{{route('account-setting')}}"><i class="fa fa-user" aria-hidden="true"></i> Account Settings</a></li>
            <li class="{{Route::is('subscription')?'active':''}}"><a href="{{route('subscription')}}"><i class="icofont-package"></i> Subscription</a></li>
            <li class="{{Route::is('transactions')?'active':''}}"><a href="{{route('transactions')}}"><i class="fa fa-history" aria-hidden="true"></i> Transaction History</a></li> --}}
            <li class="{{Route::is('help-center')?'active':''}}"><a href="{{route('help-center')}}"><i class="fa fa-question-circle" aria-hidden="true"></i> Help Center </a></li>
            <li><a href="{{route('/')}}"><i class="fa fa-power-off" aria-hidden="true"></i>Logout</a></li>
        </ul>
    </div>
</div>
<!-------- End Mobile view menu section -------->



<script>
          $("#MobilesidebarToggle").click(function () {
                if($(".navbar-toggler-icon").hasClass("menuopen"))
                {
                    $(".navbar-toggler-icon").removeClass("menuopen");
                    $(".background_show").removeClass("modal-backdrop fade show");
                }else{
                    $(".navbar-toggler-icon").addClass("menuopen");
                    $(".background_show").addClass("modal-backdrop fade show");
                }
            });
</script>

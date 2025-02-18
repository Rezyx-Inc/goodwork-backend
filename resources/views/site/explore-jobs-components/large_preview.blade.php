<div class="ss-dash-profile-4-bx-dv" id="job-item-container">
    @forelse($jobs as $j)
        <div class="ss-job-prfle-sec job-item" data-id="{{ $j->id }}">
            {{-- row 1 --}}
            <div class="row d-flex align-items-center">
            <div class="col-12 d-flex justify-content-start col-10">
            </div>
    
                <div class="row mt-2 mt-md-0 d-flex align-items-center">
                    <div class="col-7">
                        <ul>
                            @if (isset($j->profession) && isset($j->preferred_specialty))
                                <li>
                                        <svg style="vertical-align: text-top;"
                                            xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor"
                                            class="bi bi-briefcase" viewBox="0 0 16 16">
                                            <path
                                                d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                        {{ $j->profession }}, {{ $j->preferred_specialty }}
                                </li>
                            @elseif( isset($j->profession) )
                                <li><svg style="vertical-align: text-top;"
                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor"
                                    class="bi bi-briefcase" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                                        {{ $j->profession }}</li>
                            @elseif(isset($j->preferred_specialty))
                                <li><svg style="vertical-align: text-top;"
                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor"
                                    class="bi bi-briefcase" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                </svg>{{ $j->preferred_specialty }}</li>
                            @else
                                <li><svg style="vertical-align: text-top;"
                                    xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor"
                                    class="bi bi-briefcase" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5m1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0M1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5" />
                                </svg> No profession or specialty specified</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-5 d-flex justify-content-end">
                        <p class="text-center">
                            <span>+{{ $j->getOfferCount() }} Applied</span>
                        </p>
                    </div>
                </div>
                
            </div>
            {{-- row 2 --}}
            <div class="row mt-2 mt-md-0 d-flex align-items-center">
                <div class="col-7">
                    <ul>
                        @if (isset($j->job_city) && isset($j->job_state))
                            <li><a href="#"><img class="icon_cards"
                                        src="{{ URL::asset('frontend/img/location.png') }}">
                                    {{ $j->job_city }}, {{ $j->job_state }}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-5 d-flex justify-content-end">
                    <ul>
                        @if (isset($j->preferred_assignment_duration) && isset($j->terms) && $j->terms == 'Contract')
                            <li><a href="#"><img class="icon_cards"
                                        src="{{ URL::asset('frontend/img/calendar.png') }}">
                                    {{ $j->preferred_assignment_duration }} wks / assignment
                                </a></li>
                        @endif
                        @if (isset($j->actual_hourly_rate))
                            <li>
                                $ {{ number_format($j->actual_hourly_rate) }}/hr
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            {{-- row 3 --}}

            <div class="row d-flex align-items-center">
                @if (isset($j->preferred_shift_duration))
                    <div class="col-6 d-flex justify-content-between justify-content-md-start ">
                            <ul>
                                @if ($j->preferred_shift_duration == '5x8 Days' || $j->preferred_shift_duration == '4x10 Days')
                                 <li>
                                    <svg style="vertical-align: bottom;"
                                        xmlns="http://www.w3.org/2000/svg" width="25"
                                        height="25" fill="currentColor"
                                        class="bi bi-brightness-alt-high-fill"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 3a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 3m8 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5m-13.5.5a.5.5 0 0 0 0-1h-2a.5.5 0 0 0 0 1zm11.157-6.157a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m-9.9 2.121a.5.5 0 0 0 .707-.707L3.05 5.343a.5.5 0 1 0-.707.707zM8 7a4 4 0 0 0-4 4 .5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5 4 4 0 0 0-4-4" />
                                    </svg>     
                                    {{ $j->preferred_shift_duration }}
                                </li>
                                @elseif ($j->preferred_shift_duration == '3x12 Nights or Days')
                                <li>
                                    <svg style="vertical-align: text-bottom;"
                                        xmlns="http://www.w3.org/2000/svg" width="20"
                                        height="16" fill="currentColor"
                                        class="bi bi-moon-stars" viewBox="0 0 16 16">
                                        <path
                                            d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278M4.858 1.311A7.27 7.27 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.32 7.32 0 0 0 5.205-2.162q-.506.063-1.029.063c-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286" />
                                        <path
                                            d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.73 1.73 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.73 1.73 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.73 1.73 0 0 0 1.097-1.097zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z" />
                                    </svg>
                                    {{ $j->preferred_shift_duration }}
                                </li>
                                @endif
                            </ul>
                            
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <ul>
                            @if (isset($j->weekly_pay))
                            <li>
                                $ {{ number_format($j->weekly_pay) }}/wk
                            </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="col-6 d-flex justify-content-start">
                        <ul>
                            @if (isset($j->hours_per_week))
                                <li><a href="#"><img class="icon_cards"
                                            src="{{ URL::asset('frontend/img/calendar.png') }}">
                                        {{ $j->hours_per_week }} hrs/wk</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-6 mt-3 mt-md-0 d-flex justify-content-between justify-content-md-end">
                        <ul>
                            @if (isset($j->weekly_pay))
                                <li style="font-weight: 600;">
                                    $ {{ number_format($j->weekly_pay * 4 * 12) }}/yr
                                </li>
                            @endif
                        </ul>
                    </div>
                @else
                    <div class="col-6 d-flex justify-content-start">
                            <ul>
                                @if (isset($j->hours_per_week))
                                    <li><a href="#"><img class="icon_cards"
                                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                                            {{ $j->hours_per_week }} hrs/wk</a></li>
                                @endif
                            </ul>
                    </div>
                    <div class="col-6 mt-3 mt-md-0 d-flex justify-content-end">
                        <ul>
                            @if (isset($j->weekly_pay))
                                <li style="font-weight: 600;">
                                    $ {{ number_format($j->weekly_pay) }}/wk
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-12 mt-3 mt-md-0 d-flex justify-content-end">
                        <ul>
                            @if (isset($j->weekly_pay))
                                <li style="font-weight: 600;">
                                    $ {{ number_format($j->weekly_pay * 4 * 12) }}/yr
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                @endif
            </div>


            {{-- row 4 --}}
            <div class="row w-100">

                <div class="col-6 d-flex justify-content-start">
                    @if ($j->as_soon_as == true)
                        <p class="col-12" style="padding-bottom: 0px; padding-top: 8px;">
                            As soon as possible</p>
                    @endif
                </div>
                <div class="col-6 d-flex justify-content-end">
                    @if ($j->urgency == 'Auto Offer' || $j->as_soon_as == true)
                        <p class="col-2 text-center"
                            style="padding-bottom: 0px; padding-top: 8px;">Urgent</p>
                    @endif
                </div>
            </div>

        </div>
    @empty
        <div class="ss-job-prfle-sec">
            <h4>No Data found</h4>
        </div>
    @endforelse

</div>
<div id="loadTrigger"></div>
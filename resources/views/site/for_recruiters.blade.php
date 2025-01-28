@extends('layouts.main')
@section('mytitle', ' For Organizations | Saas')
@section('content')


    <!-------------banner-section-------->

    <section class="ss-hm-banner-sec ss-for-recrutr-pg-ban-sec">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ss-hm-banner-txt-bx">
                        <h2>Goodwork values applicant quality over quantity</h2>
                        <p>We give recruiters the power to stop the flood of unqualified applicants. Sometimes more is not better. We believe better is better.</p>
                        <p>Partner with Goodwork by signing up as an organization or ask us about our Independent Recruiter program. Because we don't charge fees until the Worker works, there is no risk to sign up.</p>
                        <ul>
                            {{-- <li><a href="{{ route('recruiter-signup') }}">Join Now</a></li> --}}

                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ss-hm-baner-img-dv">
                        <ul class="ss-ban-top-map-txt">
                            <li><img src="{{ URL::asset('landing/img/banner-map-icon.png') }}" /></li>
                            <li>
                                <h5>Los Angeles, CA</h5>
                            </li>
                        </ul>
                        <img src="{{ URL::asset('landing/img/For-Recruiters-banner.png') }}" />

                        <ul class="ss-ban-top-map-txt2">
                            <li><img src="{{ URL::asset('landing/img/banner-map-icon.png') }}" /></li>
                            <li>
                                <h5>Fredericksburg, VA</h5>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>



    <!------------Benefits for Organizations-------------->


    <section class="ss-benefit-emply-mn-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ss-benefit-hed-sed">
                        <h2 style="cursor: pointer;" onclick="window.location.href='https://calendly.com/goodworkdanielh/demo'">Schedule a <span class="ss-clr-pink">demo</span> here</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>








@stop

@extends('layouts.main')
@section('mytitle', ' For Organizations | Saas')
@section('content')


    <!-------------banner-section-------->

    <section class="ss-hm-banner-sec ss-for-emply-pg-ban-sec">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ss-hm-banner-txt-bx">
                        <h2>Goodwork only gets paid if you get paid</h2>
                        <p>We believe incentives should align, so we don't charge a fee until after your Worker has billable hours. No more uncertain ROI. Why should you pay for a flood of unqualified applicants? Only pay for what you want with Goodwork.</p>
                        <ul>
                            <li><a target="_blank" href="https://calendly.com/goodworkdanielh/demo">Schedule a demo here</a></li>

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
                        <img src="{{ URL::asset('landing/img/organizations-banner-img.png') }}" />

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
                        <h2>
                            <a style="cursor: pointer;" onclick="window.location.href='https://calendly.com/goodworkdanielh/demo'">
                              Sign up for a <span class="ss-clr-pink">demo</span>
                            </a>
                            or <br>
                            <span style="cursor: pointer;" onclick="window.location.href='{{ route('organization.signup') }}'">
                              create an <span class="ss-clr-pink">account</span>
                            </span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

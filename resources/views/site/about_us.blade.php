@extends('layouts.main')
@section('mytitle', ' About us page | Saas')
@section('content')
    <!-- <section class="page_banner">
    <img class="page_banner_grid" src="{{ asset('frontend/assets/images/images/page_point.png') }}" alt="">
    </section> -->

    <!------------about us sec 1-------->


    <section class="ss-about-count-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ss-about-fst-hed-ec">
                        <h4>We’re on a mission to make technology affordable & accessible to millions of business across the
                            globe</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="ss-count-div">
                        <h2>2023</h2>
                        <p>Launch</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ss-count-div">
                        <h2>40+</h2>
                        <p>App Installed</p>
                    </div>

                </div>

                <div class="col-lg-3">
                    <div class="ss-count-div">
                        <h2>200+</h2>
                        <p>Nurses</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ss-count-div">
                        <h2>120+</h2>
                        <p>Job opportunities</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!------------about us sec 2-------->


    <section class="ss-about-story-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="ss-abut-stry-hed">
                        <h6>Story</h6>
                        <p>We were told we could be anything when we grew up, but no one told us how. We found that finding
                            good work as clinicians, in particular, is tough.</p>

                        <p>That’s why we created Goodwork, an app designed to simplify the process of connecting clinicians
                            and organizations. </p>

                        <p>Our goal is to provide a user-friendly platform that eliminates the hassle of job searching,
                            allowing clinicians to focus on delivering exceptional care while working with supportive
                            organizations. Easy peasy. That’s our promise.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ss-abut-stry-img-dv">
                        <img src="{{ URL::asset('landing/img/about-img1.png') }}" />
                    </div>
                </div>
            </div>

            <div class="ss-abt-miss-txt">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-abut-stry-img-dv">
                            <img src="{{ URL::asset('landing/img/about-img2.png') }}" />
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="ss-abut-stry-hed">
                            <h6>Mission</h6>
                            <p>Goodwork is all about connecting travel nurses and healthcare workers effortlessly.</p>

                            <p>Our app simplifies the recruitment process, allowing you to find your ideal match quickly.
                            </p>

                            <p>We believe in building lasting relationships, and we’re committed to creating a platform
                                that’s transparent, supportive, and easy to use.</p>

                            <p>Our mission is to empower travel nurses to find rewarding work, and to help organizations
                                find the best talent for their needs.</p>

                            <p>Say goodbye to the headaches of job hunting and say hello to Goodwork.</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-8">
                    <div class="ss-abut-stry-hed">
                        <h6>Perks </h6>
                        <p>Goodwork offers more than just an efficient solution. We even reward you for using the app.</p>

                        <p>That’s why, for clinicians’ first year using Goodwork, we’ll add an extra 5% to the weekly
                            advertised pay. It’s our way of saying thanks.</p>

                        <p>And recruiters? We want you to have peace of mind when hiring workers. That’s why you’ll only pay
                            us when the job is done. No hassles, no worries.</p>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ss-abut-stry-img-dv">
                        <img src="{{ URL::asset('landing/img/about-img1.png') }}" />
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!------------about us sec 3-------->

    <section class="ss-our-tem-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Meet our team</h2>
                </div>

                <div class="col-lg-3">
                    <div class="ss-team-div-sec">
                        <img src="{{ URL::asset('landing/img/team-img-1.png') }}" />
                        <h5>John Smith</h5>
                        <p>CEO</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ss-team-div-sec">
                        <img src="{{ URL::asset('landing/img/team-img-2.png') }}" />
                        <h5>Simon Adams</h5>
                        <p>CTO</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ss-team-div-sec">
                        <img src="{{ URL::asset('landing/img/team-img-1.png') }}" />
                        <h5>Paul Jones</h5>
                        <p>Design Lead</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="ss-team-div-sec">
                        <img src="{{ URL::asset('landing/img/team-img-1.png') }}" />
                        <h5>Sara Hardin</h5>
                        <p>Project Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!------------about us sec31-------->

    <section class="ss-foot-btm-sign-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>sign up to explore your career options today!</h2>
                    <a href="#">Sign Up Now</a>
                </div>
            </div>
        </div>
    </section>


@stop

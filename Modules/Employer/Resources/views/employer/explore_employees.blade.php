@extends('employer::layouts.main')

@section('content')
@php
       $faker = app('Faker\Generator');
@endphp
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

        <h2>Explore your <span class="ss-color-pink">employees status!</span></h2>
        <div class="row mt-4 applicants-header text-center">
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Apply')" id="Apply">
                    <p>New</p>
                    <span>15 Applicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Screening')" id="Screening">
                    <p>Screening</p>
                    <span>17Applicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Submitted')" id="Submitted">
                    <p>Submitted</p>
                    <span>17Applicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Offered')" id="Offered">
                    <p>Offered</p>
                    <span>17plicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Onboarding')" id="Onboarding">
                    <p>Onboarding</p>
                    <span>17 Applicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Working')" id="Working">
                    <p>Working</p>
                    <span>17plicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Done')" id="Done">
                    <p>Done</p>
                    <span>17plicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Rejected')" id="Rejected">
                    <p>Rejected</p>
                    <span>17plicants</span>
                </div>
            </div>
            <div class="list_employees">
                <div class="ss-job-prfle-sec" onclick="applicationType('Blocked')" id="Blocked">
                    <p>Blocked</p>
                    <span>17plicants</span>
                </div>
            </div>
        </div>


        <div class="ss-acount-profile">
            <div class="row">
                <div class="col-lg-5" id="other_type">
                    <div class="ss-account-form-lft-1 d-none" id="ss-account">
                        <h5 class="mb-4" id="listingname">New application</h5>
                        <div id="application-list">
                            <!-- first card -->


                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="ss-mesg-sml-div" style="padding:0px;">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                            <img class="proffession_application_profil"
                                                src="{{ URL::asset('employer/assets/images/recomand-img-1.png') }}"
                                                alt="">
                                        </div>
                                        <div class="col-9 col-sm-9">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 ">
                                                    <p class="app_info_home1">
                                                        CRNA</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home2">
                                                        {{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home3">
                                                        Anesthesia</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- information of application -->
                                        <div class="name_card_locations">
                                            <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                            </div>
                                            <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                        </div>
                                        <!-- information of application -->

                                    </div>
                                </div>
                            </div>

                            <!-- first card end -->

                        </div>
                    </div>
                    <!-- done application-list model start -->

                    <div class="ss-account-form-lft-1" id="ss-account-apply">
                        <div class="row">

                            <div class="col-lg-6 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-start"
                                style="padding-right:0px;  margin-top: 10px; ">
                                <h5 id="listingname-apply">New application</h5>
                            </div>
                            <div class="col-lg-6 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-end"
                                style="padding-right:0px;  margin-top: 10px; ">
                                <button type="text" style="  padding:0px; height:37px;  width: 100px; "><svg
                                        xmlns="http://www.w3.org/2000/svg" width="31" height="30" viewBox="0 0 25 24"
                                        fill="none">
                                        <path
                                            d="M12.1623 2.28906C6.84149 2.28906 2.51257 6.61798 2.51257 11.9388C2.51257 17.2596 6.84149 21.5885 12.1623 21.5885C17.4831 21.5885 21.812 17.2596 21.812 11.9388C21.812 6.61798 17.4831 2.28906 12.1623 2.28906ZM13.6469 16.3925H10.6777C10.4808 16.3925 10.292 16.3143 10.1528 16.1751C10.0136 16.0359 9.93543 15.8471 9.93543 15.6502C9.93543 15.4533 10.0136 15.2645 10.1528 15.1253C10.292 14.9861 10.4808 14.9079 10.6777 14.9079H13.6469C13.8437 14.9079 14.0325 14.9861 14.1717 15.1253C14.3109 15.2645 14.3891 15.4533 14.3891 15.6502C14.3891 15.8471 14.3109 16.0359 14.1717 16.1751C14.0325 16.3143 13.8437 16.3925 13.6469 16.3925ZM15.8737 13.4233H8.45086C8.25399 13.4233 8.06519 13.3451 7.92598 13.2059C7.78678 13.0667 7.70857 12.8779 7.70857 12.6811C7.70857 12.4842 7.78678 12.2954 7.92598 12.1562C8.06519 12.017 8.25399 11.9388 8.45086 11.9388H15.8737C16.0706 11.9388 16.2594 12.017 16.3986 12.1562C16.5378 12.2954 16.616 12.4842 16.616 12.6811C16.616 12.8779 16.5378 13.0667 16.3986 13.2059C16.2594 13.3451 16.0706 13.4233 15.8737 13.4233ZM17.3583 10.4542H6.96629C6.76942 10.4542 6.58062 10.376 6.44141 10.2368C6.30221 10.0976 6.224 9.90878 6.224 9.71192C6.224 9.51505 6.30221 9.32625 6.44141 9.18704C6.58062 9.04784 6.76942 8.96963 6.96629 8.96963H17.3583C17.5551 8.96963 17.744 9.04784 17.8832 9.18704C18.0224 9.32625 18.1006 9.51505 18.1006 9.71192C18.1006 9.90878 18.0224 10.0976 17.8832 10.2368C17.744 10.376 17.5551 10.4542 17.3583 10.4542Z"
                                            fill="white" />
                                    </svg><span class="filters_titles">Filters</span></button>
                            </div>
                        </div>


                        <div id="application-list">
                            <!-- first card -->


                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="ss-mesg-sml-div" style="padding:0px;">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                            <img class="proffession_application_profil"
                                                src="{{ URL::asset('employer/assets/images/recomand-img-1.png') }}"
                                                alt="">
                                        </div>
                                        <div class="col-9 col-sm-9">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 ">
                                                    <p class="app_info_home1">
                                                        CRNA</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home2">
                                                         {{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home3">
                                                        Anesthesia</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- information of application -->
                                        <div class="name_card_locations">
                                            <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                            </div>
                                            <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                        </div>
                                        <!-- information of application -->

                                    </div>
                                </div>
                            </div>

                            <!-- first card end -->



                            <!-- second card -->


                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="ss-mesg-sml-div" style="padding:0px;">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                            <img class="proffession_application_profil"
                                                src="{{ URL::asset('employer/assets/images/recomand-img-1.png') }}"
                                                alt="">
                                        </div>
                                        <div class="col-9 col-sm-9">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 ">
                                                    <p class="app_info_home1">
                                                        CRNA</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home2">
                                                         {{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home3">
                                                        Anesthesia</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- information of application -->
                                        <div class="name_card_locations">
                                            <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                            </div>
                                            <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                        </div>
                                        <!-- information of application -->

                                    </div>
                                </div>
                            </div>

                            <!-- second card end -->


                            <!-- third card start -->
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="ss-mesg-sml-div" style="padding:0px;">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-md-4 " style="width: 20%;">
                                            <img class="proffession_application_profil"
                                                src="{{ URL::asset('employer/assets/images/recomand-img-1.png') }}"
                                                alt="">
                                        </div>
                                        <div class="col-9 col-sm-9">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 ">
                                                    <p class="app_info_home1">
                                                        CRNA</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home2">
                                                         {{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</p>
                                                </div>
                                                <div class="col-12 col-sm-12">
                                                    <p class="app_info_home3">
                                                        Anesthesia</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- information of application -->
                                        <div class="name_card_locations">
                                            <div class="col-3"> <a href="#" class="application-a"> Los Angeles, CA</a>
                                            </div>
                                            <div class="col-3"><a href="#" class="application-a"> Permanent</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> Day Shift</a></div>
                                            <div class="col-3"><a href="#" class="application-a"> $2500/wk</a></div>
                                        </div>
                                        <!-- information of application -->

                                    </div>
                                </div>
                            </div>
                            <!-- third card end -->

                        </div>




                        <!-- done application-list model end -->

                    </div>
                </div>






                <div class="col-lg-7">
                    <div class="ss-journy-svd-jbdtl-dv">
                        <div class="ss-job-dtls-view-bx" style="border:2px solid #111011; padding-bottom:10px;">
                            <div class="row d-none" id="application-details">


                                <div class="col-lg-3 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-start"
                                    style="padding-right:0px;  margin-top: 10px; ">
                                    <button type="text" style="    width: auto; font-size: 15px;">Chat Now</button>
                                </div>

                                <div class="ss-jb-dtl-abt-txt">
                                    <h6> About Nurse:</h6>

                                    <ul style="gap: 33px;">

                                        <li>
                                            <h5>Preferred shift:</h5>
                                            <p>Associate Degree in Nursing</p>
                                        </li>
                                        <li>
                                            <h5> Years of Experience: </h5>
                                            <p>3+ Years</p>
                                        </li>
                                        <li>
                                            <h5>Relevant Certifications:</h5>
                                            <p>BLS, CCRN</p>
                                        </li>
                                        <li>
                                            <h5>Salary Expectation:</h5>
                                            <p>$2500/wk</p>
                                        </li>
                                    </ul>
                                </div>


                                <div class="ss-meditch-text-bx">
                                    <div class="row">
                                        <h6 class="col-6"> Resume:</h6>
                                        <h6 class="col-6 text-end"><a href="#" class="emloyes_a">Download</a></h6>
                                    </div>


                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Employer</h6>
                                    <p>AGC Hospital</a></p>
                                </div>
                                <div class="ss-jb-dtl-disc-sec">
                                    <h5>Start Date:</h5>
                                    <p>28th Feb, 2023</p>
                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Jobs Applied(2)</h6>
                                    <div class="row" style="width: 100%; flex-wrap: nowrap; gap:10px;">
                                        <div class="col-6 ss-job-prfle-sec">
                                            <p>Travel <span>+50 Applied</span></p>
                                            <h4>Manager CRNA - Anesthesia</h4>
                                            <h6>Medical Solutions Recruiter</h6>
                                            <ul>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}">
                                                        Los
                                                        Angeles, CA</a></li>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}">
                                                        10
                                                        wks</a></li>
                                                <li><a href="#"><img
                                                            src="{{URL::asset('frontend/img/dollarcircle.png')}}">
                                                        2500/wk</a></li>
                                            </ul>

                                        </div>
                                        <div class="col-6 ss-job-prfle-sec">
                                            <p>Travel <span>+50 Applied</span></p>
                                            <h4>Manager CRNA - Anesthesia</h4>
                                            <h6>Medical Solutions Recruiter</h6>
                                            <ul>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}">
                                                        Los
                                                        Angeles, CA</a></li>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}">
                                                        10
                                                        wks</a></li>
                                                <li><a href="#"><img
                                                            src="{{URL::asset('frontend/img/dollarcircle.png')}}">
                                                        2500/wk</a></li>
                                            </ul>

                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-center"
                                            style="padding-right:0px;  margin-top: 10px; margin-bottom: 10px;">
                                            <button type="text" id="change-button-type" data-target="file"
                                                data-hidden_name="diploma_cer" data-hidden_value="Yes" data-href=""
                                                data-title="Did you really graduate?" data-name="diploma"
                                                onclick="open_modal(this)">submit hidden</button>
                                        </div>




                                    </div>
                                </div>



                            </div>


                            <!-- details for apply type -->

                            <div class="row" id="application-details-apply">




                                <h6> Change Application Status :</h6>
                                <div class="col-lg-12 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-start"
                                    style="padding-right:0px;  margin-top: 10px; ">


                                    <select class="select_style" name="cars" id="cars">
                                        <option class="option_style" value="">Select Status</option>

                                    </select>

                                </div>

                                <div class="ss-jb-dtl-abt-txt">
                                    <h6> About Nurse:</h6>

                                    <ul style="gap: 33px;">

                                        <li>
                                            <h5>Preferred shift:</h5>
                                            <p>Associate Degree in Nursing</p>
                                        </li>
                                        <li>
                                            <h5> Years of Experience: </h5>
                                            <p>3+ Years</p>
                                        </li>
                                        <li>
                                            <h5>Relevant Certifications:</h5>
                                            <p>BLS, CCRN</p>
                                        </li>
                                        <li>
                                            <h5>Salary Expectation:</h5>
                                            <p>$2500/wk</p>
                                        </li>
                                    </ul>
                                </div>


                                <div class="ss-meditch-text-bx">
                                    <div class="row">
                                        <h6 class="col-6"> Resume:</h6>
                                        <h6 class="col-6 text-end"><a href="#" class="emloyes_a">Download</a></h6>
                                    </div>


                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Employer</h6>
                                    <p>AGC Hospital</a></p>
                                </div>
                                <div class="ss-jb-dtl-disc-sec">
                                    <h5>Start Date:</h5>
                                    <p>28th Feb, 2023</p>
                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Jobs Applied(2)</h6>
                                    <div class="row" style="width: 100%; flex-wrap: nowrap; gap:10px;">
                                        <div class="col-6 ss-job-prfle-sec">
                                            <p>Travel <span>+50 Applied</span></p>
                                            <h4>Manager CRNA - Anesthesia</h4>
                                            <h6>Medical Solutions Recruiter</h6>
                                            <ul>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}">
                                                        Los
                                                        Angeles, CA</a></li>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}">
                                                        10
                                                        wks</a></li>
                                                <li><a href="#"><img
                                                            src="{{URL::asset('frontend/img/dollarcircle.png')}}">
                                                        2500/wk</a></li>
                                            </ul>

                                        </div>
                                        <div class="col-6 ss-job-prfle-sec">
                                            <p>Travel <span>+50 Applied</span></p>
                                            <h4>Manager CRNA - Anesthesia</h4>
                                            <h6>Medical Solutions Recruiter</h6>
                                            <ul>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}">
                                                        Los
                                                        Angeles, CA</a></li>
                                                <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}">
                                                        10
                                                        wks</a></li>
                                                <li><a href="#"><img
                                                            src="{{URL::asset('frontend/img/dollarcircle.png')}}">
                                                        2500/wk</a></li>
                                            </ul>

                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 ss-jb-dtl-apply-btn d-flex align-items-center justify-content-center"
                                            style="padding-right:0px;  margin-top: 10px; margin-bottom: 10px;">
                                            <button type="text" id="change-button-type" data-target="file"
                                                data-hidden_name="diploma_cer" data-hidden_value="Yes" data-href=""
                                                data-title="Did you really graduate?" data-name="diploma"
                                                onclick="open_modal(this)">submit hidden</button>
                                        </div>




                                    </div>
                                </div>



                            </div>

                            <!-- end details for apply types -->


                            <div class="modal fade ss-jb-dtl-pops-mn-dv" id="file_modal" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content" class="modal_content">
                                        <div class="ss-pop-cls-vbtn">
                                            <div> <button type="button" class="btn-close black"
                                                    data-target="#file_modal" onclick="close_modal(this)"
                                                    aria-label="Close"></button>
                                                <h4 class="modal_title" id="title_model">Move To On Boarding</h4>
                                            </div>

                                        </div>
                                        <div class="modal-body">
                                            <div class="ss-job-dtl-pop-form">
                                                <form method="post" action="{{route('my-profile.store')}}"
                                                    id="dropdown_modal_form" class="modal-form">

                                                    <div class="ss-form-group">
                                                        <div style="margin-bottom: 24px;">
                                                            <label for="date_model">Employer</label>
                                                            <select name="">
                                                                <option value="none" selected disabled hidden>AGC
                                                                    Hospital</option>
                                                            </select>
                                                        </div>
                                                        <label for="date_model">On Board Date</label>
                                                        <input name="date_model" type="date">
                                                    </div>
                                                    {{-- <div class="ss-jb-dtl-pop-check"><input type="checkbox"
                                                            id="vehicle1" name="vehicle1" value="Bike">
                                                        <label for="vehicle1"> This is a compact license</label>
                                                    </div> --}}
                                                    <button class="ss-job-dtl-pop-sv-btn">Save</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>
<script>
    function applicationType(type, id = "", formtype, jobid = "") {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

        if (formtype == "joballdetails" || formtype == "createdraft") {
            event.preventDefault();
            var $form = $('#send-job-offer');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('CSRF token not found.');
                return;
            }
            var formData = $form.serialize();


        }

        var applyElement = document.getElementById('Apply');
        var screeningElement = document.getElementById('Screening');
        var submittedElement = document.getElementById('Submitted');
        var offeredElement = document.getElementById('Offered');
        var onboardingElement = document.getElementById('Onboarding');
        var workingElement = document.getElementById('Working');
        var doneElement = document.getElementById('Done');
        var blockedElement = document.getElementById('Blocked');
        var rejectedElement = document.getElementById('Rejected');


        if (applyElement.classList.contains("active")) {
            applyElement.classList.remove("active");
        }
        if (screeningElement.classList.contains("active")) {
            screeningElement.classList.remove("active");
        }
        if (submittedElement.classList.contains("active")) {
            submittedElement.classList.remove("active");
        }
        if (offeredElement.classList.contains("active")) {
            offeredElement.classList.remove("active");
        }
        if (onboardingElement.classList.contains("active")) {
            onboardingElement.classList.remove("active");
        }
        if (workingElement.classList.contains("active")) {
            workingElement.classList.remove("active");
        }
        if (doneElement.classList.contains("active")) {
            doneElement.classList.remove("active");
        }
        if (blockedElement.classList.contains("active")) {
            blockedElement.classList.remove("active");
        }
        if (rejectedElement.classList.contains("active")) {
            rejectedElement.classList.remove("active");
        }

        document.getElementById(type).classList.add("active")

        document.getElementById('listingname').innerHTML = type + ' Employees';
        document.getElementById('listingname-apply').innerHTML = type + ' Employees';
        document.getElementById('change-button-type').innerHTML = 'Move to ' + type;
        document.getElementById('title_model').innerHTML = 'Move to ' + type;
        if (type == 'Apply') {

            document.getElementById("ss-account-apply").classList.remove("d-none");
            document.getElementById("application-details-apply").classList.remove("d-none");
            document.getElementById("application-details").classList.add("d-none");
            document.getElementById("ss-account").classList.add("d-none");
        } else {
            document.getElementById("ss-account-apply").classList.add("d-none");
            document.getElementById("application-details-apply").classList.add("d-none");
            document.getElementById("application-details").classList.remove("d-none");
            document.getElementById("ss-account").classList.remove("d-none");
        }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

    }

    function showDetails(id) {
        console.log(id);
    }

    function sendOffer(type, userid, jobid) {
        document.getElementById("offer-form").classList.remove("d-none");
        document.getElementById("application-details").classList.add("d-none");
    }
    $(document).ready(function () {
        applicationType('Apply')
    });



    function applicationStatus(applicationstatus, type, id, jobid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('employer/employer-update-application-status') }}",
                data: {
                    'token': csrfToken,
                    'type': type,
                    'id': id,
                    'formtype': applicationstatus,
                    'jobid': jobid,
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {

                    $("#application-list").html(result.applicationlisting);
                    $("#application-details").html(result.applicationdetails);
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }

    // function offerSend(id, jobid, type) {
    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //     if (csrfToken) {
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': csrfToken
    //             },
    //             url: "{{ url('employer/send-job-offer-employer') }}",
    //             data: {
    //                 'token': csrfToken,
    //                 'id': id,
    //                 'jobid': jobid,
    //                 'type': type,
    //             },
    //             type: 'POST',
    //             dataType: 'json',
    //             success: function (result) {
    //                 notie.alert({
    //                     type: 'success',
    //                     text: '<i class="fa fa-check"></i> ' + result.message,
    //                     time: 5
    //                 });
    //             },
    //             error: function (error) {
    //                 // Handle errors
    //             }
    //         });
    //     } else {
    //         console.error('CSRF token not found.');
    //     }
    // }

</script>
<script>
    var speciality = {};



    function add_speciality(obj) {
        if (!$('#preferred_specialty').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the speciality please.',
                time: 3
            });
        } else if (!$('#preferred_experience').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Enter total year of experience.',
                time: 3
            });
        } else {
            if (!speciality.hasOwnProperty($('#preferred_specialty').val())) {
                speciality[$('#preferred_specialty').val()] = $('#preferred_experience').val();
                $('#preferred_experience').val('');
                $('#preferred_specialty').val('');
                list_specialities();
            }
        }
    }

    function remove_speciality(obj, key) {
        if (speciality.hasOwnProperty($(obj).data('key'))) {
            var element = document.getElementById("remove-speciality");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'specialty': key,
                    }
                    let removetype = 'specialty';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('employer/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {

                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
                delete speciality[$(obj).data('key')];
                delete window.allspecialty[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_specialities();
        }
    }

    function list_specialities() {
        var str = '';
        if (window.allspecialty) {
            speciality = Object.assign({}, speciality, window.allspecialty);
        }
        for (const key in speciality) {
            let specialityname = "";

            var select = document.getElementById("preferred_specialty");
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (speciality.hasOwnProperty(key)) {
                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        specialityname = item.title;
                    }
                });
                const value = speciality[key];
                str += '<ul>';
                str += '<li>' + specialityname + '</li>';
                str += '<li>' + value + ' Years</li>';
                str += '<li><button type="button"  id="remove-speciality" data-key="' + key + '" onclick="remove_speciality(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.speciality-content').html(str);
    }
</script>
<script>
    var vaccinations = {};

    function addvacc() {



        if (!$('#vaccinations').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                time: 3
            });
        } else {
            if (!vaccinations.hasOwnProperty($('#vaccinations').val())) {
                console.log($('#vaccinations').val());

                var select = document.getElementById("vaccinations");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                vaccinations[$('#vaccinations').val()] = optionText;
                $('#vaccinations').val('');
                list_vaccinations();
            }
        }
    }

    function list_vaccinations() {
        var str = '';
        if (window.allvaccinations) {
            vaccinations = Object.assign({}, vaccinations, window.allvaccinations);
        }
        for (const key in vaccinations) {
            let vaccinationsname = "";
            var select = document.getElementById("vaccinations");
            console.log(select);
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (vaccinations.hasOwnProperty(key)) {

                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        vaccinationsname = item.title;
                    }
                });
                const value = vaccinations[key];
                str += '<ul>';
                str += '<li class="w-50">' + vaccinationsname + '</li>';
                str += '<li class="w-50 text-end"><button type="button"  id="remove-vaccinations" data-key="' + key + '" onclick="remove_vaccinations(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }


</script>
<script>
    var certificate = {};
    function addcertifications() {
        if (!$('#certificate').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the certificate please.',
                time: 3
            });
        } else {
            if (!certificate.hasOwnProperty($('#certificate').val())) {
                console.log($('#certificate').val());

                var select = document.getElementById("certificate");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                certificate[$('#certificate').val()] = optionText;
                $('#certificate').val('');
                list_certifications();
            }
        }
    }

    function list_certifications() {
        var str = '';
        if (window.allcertificate) {
            certificate = Object.assign({}, certificate, window.allcertificate);
        }
        for (const key in certificate) {
            let certificatename = "";
            var select = document.getElementById("certificate");
            console.log(select);
            var allspcldata = [];
            for (var i = 0; i < select.options.length; i++) {
                var obj = {
                    'id': select.options[i].value,
                    'title': select.options[i].textContent,
                };
                allspcldata.push(obj);
            }

            if (certificate.hasOwnProperty(key)) {
                allspcldata.forEach(function (item) {
                    if (key == item.id) {
                        certificatename = item.title;
                    }
                });
                const value = certificate[key];
                str += '<ul>';
                str += '<li class="w-50">' + certificatename + '</li>';
                str += '<li class="w-50 text-end"><button type="button"  id="remove-certificate" data-key="' + key + '" onclick="remove_certificate(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }


</script>
<script>

</script>
@section('js')
<script>



    function open_modal(obj) {
        let name, title, modal, form, target;

        name = $(obj).data('name');
        title = $(obj).data('title');
        target = $(obj).data('target');

        modal = '#' + target + '_modal';
        form = modal + '_form';
        $(form).find('h4').html(title);
        switch (target) {
            case 'file':
                $(form).find('input[type="file"]').attr('name', name);
                $(form).find('input[type="hidden"]').attr('name', $(obj).data('hidden_name'));
                $(form).find('input[type="hidden"]').val($(obj).data('hidden_value'));
                $(form).attr('action', $(obj).data('href'));
                break;
            case 'input':
                $(form).find('input[type="text"]').attr('name', name);
                $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                break;
            case 'binary':
                $(form).find('input[type="radio"]').attr('name', name);
                break;
            case 'dropdown':
                $(form).find('select').attr('name', name);
                get_dropdown(obj);
                break;
            default:
                break;
        }

        $(modal).modal('show');
    }

    function close_modal(obj) {
        let target = $(obj).data('target');
        $(target).modal('hide');
    }
</script>
@stop

@endsection

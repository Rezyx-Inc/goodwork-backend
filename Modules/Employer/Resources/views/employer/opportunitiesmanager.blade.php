@extends('employer::layouts.main')

@section('content')
<main class="ss-main-body-sec">
    <div class="container">
        <div class="ss-opport-mngr-mn-div-sc">
            <div class="ss-opport-mngr-hedr">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>Opportunities Manager</h4>
                    </div>
                    <div class="col-lg-6">
                        <ul>
                            <li><button id="drafts" onclick="opportunitiesType('drafts')"
                                    class="ss-darfts-sec-draft-btn">Drafts</button></li>
                            <li><button id="published" onclick="opportunitiesType('published')"
                                    class="ss-darfts-sec-publsh-btn">Published</button></li>
                            <li><button id="onhold" onclick="opportunitiesType('onhold')"
                                    class="ss-darfts-sec-publsh-btn">On Hold</button></li>

                            <li><a href="#" onclick="request_job_form_appear()" class="ss-opr-mngr-plus-sec"><i
                                        class="fas fa-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="ss-no-job-mn-dv" id="no-job-posted">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ss-nojob-dv-hed">
                            <h6>No Job Posted.<br>
                                Start Creating Job Request</h6>
                            <a href="#" onclick="request_job_form_appear()">Create Job Request</a>
                        </div>
                    </div>
                </div>
            </div>



<!-- add job form -->
            <div class="all d-none" id="create_job_request_form">
    <div class="bodyAll">
        <div class="ss-account-form-lft-1 container">
            <header>Create Job Request</header>
            <div class="row progress-bar-item">
                <div class="col-3 step">
                    <p>Job information</p>
                    <div class="bullet">
                        <span>1</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>

                <div class=" col-3 step">
                    <p>Preferences and Requirements</p>
                    <div class="bullet">
                        <span>1</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
                <div class="col-3 step">
                    <p>Job Details</p>
                    <div class="bullet">
                        <span>1</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>

                <div class="col-3 step">
                    <p>Work Schedule & Requirements</p>
                    <div class="bullet">
                        <span>4</span>
                    </div>
                    <div class="check fas fa-check"></div>
                </div>
            </div>
            <div class="form-outer">
                <form method="post" action="{{route('addJob.store')}}">
                    @csrf
                    <!-- first form slide required inputs for adding jobs -->

                    <div class=" page slide-page">
                        <div class="row">
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter job name">
                                <span class="help-block-job_name"></span>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_type" id="job_type" placeholder="Enter job type">
                                <span class="help-block-job_type"></span>
                            </div>

                            <div class="ss-form-group col-md-4">

                                <select name="preferred_specialty" id="preferred_specialty">
                                    <option value="">Specialty</option>
                                    <option value="1">Term Option 1</option>
                                    <option value="2">Term Option 2</option>
                                    <option value="3">Term Option 3</option>
                                </select>
                                <span class="help-block-preferred_specialty"></span>
                            </div>
                            <div class="ss-form-group col-md-4">

                                <select name="perferred_profession" id="perferred_profession">
                                    <option value="">Proffession</option>
                                    <option value="1">Term Option 1</option>
                                    <option value="2">Term Option 2</option>
                                    <option value="3">Term Option 3</option>
                                </select>
                                <span class="help-block-perferred_profession"></span>
                            </div>

                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_city" id="job_city"
                                    placeholder="Enter Job Location (City)">
                                    <span class="help-block-job_city"></span>
                            </div>

                            <div class="ss-form-group col-md-4">


                                <input type="text" name="job_state" id="job_state"
                                    placeholder="Enter Job Location (State)">
                                    <span class="help-block-job_state"></span>
                            </div>


                            <div class="ss-form-group col-md-4">
                                <input type="text" name="preferred_work_location" id="preferred_work_location"
                                    placeholder="Enter Work Location">
                                <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                    (Location not required)
                                </span>
                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="number" name="preferred_assignment_duration"
                                    id="preferred_assignment_duration" placeholder="Enter Work Duration Per Week">
                                <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                    (Duration not required)
                                </span><br>

                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="number" step="0.01" name="weekly_pay" id="weekly_pay" placeholder="Enter Weekly Pay">
                                <span class="help-block-weekly_pay"></span>
                            </div>


                            <div class="ss-form-group col-md-4">
                                <textarea type="text" name="description" id="description"
                                    placeholder="Enter Job Description"></textarea>
                                <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                    (Description not required)
                                </span>
                            </div>

                            <div class="field btns col-12 d-flex justify-content-center">
                                <button class="saveDrftBtn">Save as draft</button>
                                <button class="firstNext next">Next</button>
                            </div>
                        </div>
                    </div>


                    <!-- Second form slide required inputs for adding jobs -->
                    <div class="page">
                        <div class="row">
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="preferred_work_area" id="preferred_work_area"
                                    placeholder="Enter Preferred Work Area">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="preferred_experience" id="preferred_experience"
                                    placeholder="Enter Preferred Experience">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="number" name="preferred_shift_duration"
                                    placeholder="Enter Preferred Shift Duration">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="number" name="preferred_days_of_the_week"
                                    placeholder="Enter Preferred Days of the Week">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="number" name="preferred_hourly_pay_rate"
                                    placeholder="Enter Preferred Hourly Pay Rate">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="text" name="preferred_shift" id="preferred_shift"
                                    placeholder="Enter Preferred Shift">
                            </div>

                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                ( The above fields are not required )
                            </span>
                            <div class="field btns col-12 d-flex justify-content-center">
                                <button class="saveDrftBtn">Save as draft</button>
                                <button class="prev-1 prev">Previous</button>
                                <button class="next-1 next">Next</button>
                            </div>
                        </div>
                    </div>

                    <!-- Third form slide required inputs for adding jobs -->

                    <div class="page">
                        <div class="row">
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_function" id="job_function"
                                    placeholder="Enter Job Function">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_cerner_exp" id="job_cerner_exp"
                                    placeholder="Enter Cerner Experience">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_meditech_exp" id="job_meditech_exp"
                                    placeholder="Enter Meditech Experience">
                            </div>



                            <div class="ss-form-group col-md-4">
                                <input type="text" name="seniority_level" id="seniority_level"
                                    placeholder="Enter Seniority Level">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="text" name="job_epic_exp" id="job_epic_exp"
                                    placeholder="Enter Epic Experience">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <textarea type="text" name="job_other_exp" id="job_other_exp"
                                    placeholder="Enter Other Experiences"></textarea>

                            </div>
                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                ( The above fields are not required )
                            </span>

                            <div class="field btns col-12 d-flex justify-content-center">
                                <button class="saveDrftBtn">Save as draft</button>
                                <button class="prev-2 prev">Previous</button>
                                <button class="next-2 next">Next</button>

                            </div>

                        </div>
                    </div>

                    <!-- Forth form slide for adding jobs -->

                    <div class="page">
                        <div class="row">
                            <div class="ss-form-group col-md-4">
                            <label>Start Date</label>
                                <input type="date" name="start_date" id="start_date" placeholder="Enter Start Date">
                            </div>
                            <div class="ss-form-group col-md-4">
                            <label>End Date</label>
                                <input type="date" name="end_date" id="end_date" placeholder="Enter End Date">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="number" name="hours_shift" id="hours_shift"
                                    placeholder="Enter Hours per Shift">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="number" name="hours_per_week" id="hours_shift"
                                    placeholder="Enter Hours per week">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <input type="text" name="responsibilities" id="responsibilities"
                                    placeholder="Enter Responsibilities">
                            </div>

                            <div class="ss-form-group col-md-4">
                                <input type="text" name="qualifications" id="qualifications"
                                    placeholder="Enter Qualifications">
                            </div>
                            <span style="color:#b5649e;" id="passwordHelpInline" class="form-text">
                                ( The above fields are not required )
                            </span>
                            <div class="field btns col-12 d-flex justify-content-center">
                                <button class="prev-3 prev">Previous</button>
                                <button class="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end add job form -->

            <div class="ss-acount-profile d-none" id="published-job-details">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-account-form-lft-1">
                            <h5 class="mb-4 text-capitalize" id="opportunitylistname"></h5>
                            <div class="col-12 ss-job-prfle-sec">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src=" {{URL::asset('frontend/img/location.png')}}">
                                            Los
                                            Angeles, CA</a></li>
                                    <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}">
                                            10
                                            wks</a></li>
                                    <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}">
                                            2500/wk</a></li>
                                </ul>

                            </div>
                            <div id="job-list">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7" id="details_draft">
                        <div class="ss-opp--mng-publ-right-dv">
                            <form class="ss-emplor-form-sec" id="create-new-job">
                                <div class="row">
                                    <h5>Edit Job</h5>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name"
                                            placeholder="Enter Job Location (City, State)" value="Travel Nurse RN PICU">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name" placeholder="Enter job name"
                                            value="Local">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name"
                                            placeholder="Enter Work Location"
                                            value="Memorial Hermann Memorial City Medical ">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name"
                                            placeholder="Enter Work Duration" value="Houston, TX">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name" placeholder="Enter Weekly Pay"
                                            value="12 weeks">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <input type="text" name="job_name" id="job_name"
                                            placeholder="Enter Job Description" value="$250/wk">
                                    </div>
                                    <div class="ss-form-group col-md-12">
                                        <label>Job Name</label>
                                        <input type="text" name="job_id" id="job_id" placeholder="Enter job id"
                                            class="d-none">
                                        <textarea style="overflow:hidden" type="textarea" name="job_name" id="job_name"
                                            placeholder="Enter Job Description">This position is accountable and responsible for nursing care administered under the direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse). Nurse interns must utilize personal protective equipment such as gloves, gown, mask.</textarea>
                                    </div>

                                </div>
                                <div class="ss-crt-opper-buttons">
                                    <a href="javascript:void(0)" class="ss-reject-offer-btn text-center w-50"
                                        onclick="createDraft()">Save As Draft</a>
                                    <a href="javascript:void(0)" class="ss-counter-button text-center w-50"
                                        onclick="createJob()">Publish Now</a>
                                </div>
                            </form>
                            <div id="job-details">
                            </div>
                        </div>
                    </div>
                    <!-- published and Onhold details start-->

                    <div class="col-lg-7" id="details_onhold_published">
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
                                            <h6 class="col-6 text-end"><a href="#"
                                                    class="emloyes_download_resume">Download</a></h6>
                                        </div>


                                    </div>

                                    <div class="ss-jb-dtl-disc-sec">
                                        <h6>Employer</h6>
                                        <p>AGC Hospital</p>
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
                                                    <li><a href="#"><img
                                                                src="{{URL::asset('frontend/img/location.png')}}">
                                                            Los
                                                            Angeles, CA</a></li>
                                                    <li><a href="#"><img
                                                                src="{{URL::asset('frontend/img/calendar.png')}}">
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
                                                    <li><a href="#"><img
                                                                src="{{URL::asset('frontend/img/location.png')}}">
                                                            Los
                                                            Angeles, CA</a></li>
                                                    <li><a href="#"><img
                                                                src="{{URL::asset('frontend/img/calendar.png')}}">
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
                                                    onclick="open_modal(this)">Move to Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- details for apply type -->

                                <div class="row" id="application-details-apply">

                                    <h5 class="job_details_h5">Job Detail</h5>

                                    <div class="ss-jb-dtl-disc-sec">
                                        <h6>Description</h6>
                                        <p>This position is accountable and responsible for nursing care administered
                                            under the direction of a Registered Nurse (Nurse Manager, Charge Nurse,
                                            and/or Staff Nurse). Nurse interns must utilize personal protective
                                            equipment such as gloves, gown, mask.</p>
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



                                    <div class="ss-jb-dtl-disc-sec">
                                        <h5>Start Date:</h5>
                                        <p>28th Feb, 2023</p>
                                    </div>

                                    <div class="ss-jb-dtl-disc-sec">

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
                                        <div class="modal-content" style="border-radius: 36px; background: #FFF8FD;">
                                            <div class="ss-pop-cls-vbtn">
                                                <div> <button type="button" class="btn-close black"
                                                        data-target="#file_modal" onclick="close_modal(this)"
                                                        aria-label="Close"></button>
                                                    <h4 class="modal_h4" id="title_model">Move to Apply</h4>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <div class="ss-mesg-sml-div" style="padding:0px;">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-sm-4 col-md-4 "
                                                                style="width: 20%;">
                                                                <img class="proffession_application_profil"
                                                                    src="{{URL::asset('employer/assets/images/recomand-img-1.png')}}"
                                                                    alt="">
                                                            </div>
                                                            <div class="col-7 col-sm-7">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 ">
                                                                        <p class="app_info_home1">
                                                                            Associate Degree in Nursing</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home2">
                                                                            Mary Smith</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home3">
                                                                            BLS, CCRN</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 d-flex justify-content-end"> <img
                                                                    class="modal_icon"
                                                                    class="proffession_application_profil"
                                                                    src="{{URL::asset('frontend/img/comment.png')}}"
                                                                    alt=""></div>

                                                            <!-- information of application -->
                                                            <div class="modal_location_card">
                                                                <div class="col-3"> <a href="#" class="application-a">
                                                                        Los Angeles, CA</a>
                                                                </div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Permanent</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Day Shift</a></div>
                                                            </div>
                                                            <!-- information of application -->
                                                            <!-- end first card -->
                                                        </div>
                                                        <!-- start second card -->
                                                    </div>
                                                </div>
                                                <!-- start second card -->
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <div class="ss-mesg-sml-div" style="padding:0px;">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-sm-4 col-md-4 "
                                                                style="width: 20%;">
                                                                <img class="proffession_application_profil"
                                                                    src="{{URL::asset('employer/assets/images/recomand-img-2.png')}}"
                                                                    alt="">
                                                            </div>
                                                            <div class="col-7 col-sm-7">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 ">
                                                                        <p class="app_info_home1">
                                                                            Associate Degree in Nursing</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home2">
                                                                            David Lee</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home3">
                                                                            BLS</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 d-flex justify-content-end"> <img
                                                                    class="modal_icon"
                                                                    class="proffession_application_profil"
                                                                    src="{{URL::asset('frontend/img/comment.png')}}"
                                                                    alt=""></div>

                                                            <!-- information of application -->
                                                            <div class="modal_location_card">
                                                                <div class="col-3"> <a href="#" class="application-a">
                                                                        Los Angeles, CA</a>
                                                                </div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Permanent</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Day Shift</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        $2500/wk</a></div>
                                                            </div>



                                                            <!-- information of application -->
                                                            <!-- end first card -->





                                                        </div>


                                                    </div>
                                                </div>
                                                <!-- start third card -->
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <div class="ss-mesg-sml-div" style="padding:0px;">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-sm-4 col-md-4 "
                                                                style="width: 20%;">
                                                                <img class="proffession_application_profil"
                                                                    src="{{URL::asset('employer/assets/images/recomand-img-3.png')}}"
                                                                    alt="">
                                                            </div>
                                                            <div class="col-7 col-sm-7">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 ">
                                                                        <p class="app_info_home1">
                                                                            Associate Degree in Nursing</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home2">
                                                                            Jane Johnson</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home3">
                                                                            Anesthesia</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 d-flex justify-content-end"> <img
                                                                    class="modal_icon"
                                                                    class="proffession_application_profil"
                                                                    src="{{URL::asset('frontend/img/comment.png')}}"
                                                                    alt=""></div>

                                                            <!-- information of application -->
                                                            <div class="modal_location_card">
                                                                <div class="col-3"> <a href="#" class="application-a">
                                                                        Los Angeles, CA</a>
                                                                </div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Permanent</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Day Shift</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        $2500/wk</a></div>
                                                            </div>



                                                            <!-- information of application -->


                                                        </div>


                                                    </div>
                                                </div>
                                                <!-- end third card -->

                                                <!-- start forth card -->



                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <div class="ss-mesg-sml-div" style="padding:0px;">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-sm-4 col-md-4 "
                                                                style="width: 20%;">
                                                                <img class="proffession_application_profil"
                                                                    src="{{URL::asset('employer/assets/images/message-img4.png')}}"
                                                                    alt="">
                                                            </div>
                                                            <div class="col-7 col-sm-7">
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 ">
                                                                        <p class="app_info_home1">
                                                                            Associate Degree in Nursing</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home2">
                                                                            Ben Stone</p>
                                                                    </div>
                                                                    <div class="col-12 col-sm-12">
                                                                        <p class="app_info_home3">
                                                                            BLS, CCRN</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-2 d-flex justify-content-end"> <img
                                                                    class="modal_icon"
                                                                    class="proffession_application_profil"
                                                                    src="{{URL::asset('frontend/img/comment.png')}}"
                                                                    alt=""></div>

                                                            <!-- information of application -->
                                                            <div class="modal_location_card">
                                                                <div class="col-3"> <a href="#" class="application-a">
                                                                        Los Angeles, CA</a>
                                                                </div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Permanent</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        Day Shift</a></div>
                                                                <div class="col-3"><a href="#" class="application-a">
                                                                        $2500/wk</a></div>
                                                            </div>
                                                            <!-- information of application -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end forth card -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- published and Onhold details end-->
                </div>
            </div>
        </div>
    </div>



</main>
<script>

    function request_job_form_appear() {
        document.getElementById('no-job-posted').classList.add('d-none');
        document.getElementById('published-job-details').classList.add('d-none');
        document.getElementById('create_job_request_form').classList.remove('d-none');

    }
    function opportunitiesType(type, id = "", formtype) {

        var draftsElement = document.getElementById('drafts');
        var publishedElement = document.getElementById('published');
        var onholdElement = document.getElementById('onhold');


        if (type == "drafts") {
            document.getElementById("no-job-posted").classList.add("d-none");
            document.getElementById("details_draft").classList.remove("d-none");
            document.getElementById("details_onhold_published").classList.add("d-none");
            document.getElementById('published-job-details').classList.remove('d-none');
            document.getElementById("create_job_request_form").classList.add("d-none");
        } else {
            document.getElementById("no-job-posted").classList.add("d-none");
            document.getElementById("details_onhold_published").classList.remove("d-none");
            document.getElementById("details_draft").classList.add("d-none");

            document.getElementById('published-job-details').classList.remove('d-none');
            document.getElementById("create_job_request_form").classList.add("d-none");
        }


        if (draftsElement.classList.contains("active")) {
            draftsElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }
        if (publishedElement.classList.contains("active")) {
            publishedElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }
        if (onholdElement.classList.contains("active")) {
            onholdElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }

        document.getElementById(type).classList.add("active")

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
        let activestatus = 0;
        document.getElementById('opportunitylistname').innerHTML = type;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('employer/employer-get-job-listing') }}",
                data: {
                    'token': csrfToken,
                    'type': type,
                    'id': id,
                    'formtype': formtype,
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    // $("#job-list").html(result.joblisting);
                    // $("#job-details").html(result.jobdetails);

                    window.allspecialty = result.allspecialty;
                    window.allvaccinations = result.allvaccinations;
                    window.allcertificate = result.allcertificate;
                    list_specialities();
                    list_vaccinations();
                    list_certifications();
                    // console.log(result.joblisting);
                    // if (result.joblisting != "") {
                    //     document.getElementById("published-job-details").classList.remove("d-none");
                    //     document.getElementById("no-job-posted").classList.add("d-none");
                    // }
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
        // editJob();
    }
    $(document).ready(function () {


        document.getElementById("details_onhold_published").classList.add("d-none");
        document.getElementById("details_draft").classList.add("d-none");
        document.getElementById('published-job-details').classList.add('d-none');
    });

    function editOpportunity(id = "", formtype) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('employer/employer-get-job-listing') }}",
                data: {
                    'id': id,
                    'formtype': formtype
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {

                    // $("#application-list").html(result.applicationlisting);
                    // $("#application-details").html(result.applicationdetails);
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }

    function editJob(inputField) {
        var value = inputField.value;
        var name = inputField.name;

        if (value != "") {
            if (name == "vaccinations" || name == "preferred_specialty" || name == "preferred_experience" || name == "certificate") {
                var inputFields = document.querySelectorAll(name == "vaccinations" ? 'select[name="vaccinations"]' : name == "preferred_specialty" ? 'select[name="preferred_specialty"]' : name == "preferred_experience" ? 'input[name="preferred_experience"]' : 'select[name="certificate"]');
                var data = [];
                inputFields.forEach(function (input) {
                    data.push(input.value);
                });

                value = data.join(', ');
            }

            var formData = {};
            formData[inputField.name] = value;
            formData['job_id'] = document.getElementById('job_id').value;
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                type: 'POST',
                url: "{{ url('employer/employer-create-opportunity') }}/update",
                data: formData,
                dataType: 'json',
                success: function (data) {
                    // notie.alert({
                    //   type: 'success',
                    //   text: '<i class="fa fa-check"></i> ' + data.message,
                    //   time: 5
                    // });
                    if (document.getElementById('job_id').value.trim() == '' || document.getElementById('job_id').value.trim() == 'null' || document.getElementById('job_id').value.trim() == null) {
                        document.getElementById("job_id").value = data.job_id;
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    }

    function addmoreexperience() {
        var allExperienceDiv = document.getElementById('all-experience');
        var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
        newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
        newExperienceDiv.querySelector('input[type="number"]').value = '';
        allExperienceDiv.appendChild(newExperienceDiv);
    }

    function changeStatus(type, id = '0') {
        if (type == "draft") {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Draft Updated Successfully',
                time: 5
            });
        } else {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                let check_type = type;
                console.log(document.getElementById('job_id'));
                // if (document.getElementById('job_id').value) {
                if (id == '0') {
                    id = document.getElementById('job_id').value;
                }
                let formData = {
                    'job_id': id
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ url('employer/employer-create-opportunity') }}/" + check_type,

                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });

                        if (type == "hidejob") {
                            opportunitiesType('onhold')
                        } else {
                            opportunitiesType('published')
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                // }
            } else {
                console.error('CSRF token not found.');
            }
        }
    }




    function offerSend(id, jobid, type, workerid, employerid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            let counterstatus = "1";
            if (type == "rejectcounter") {
                counterstatus = "0";
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('employer/employer-send-job-offer') }}",
                data: {
                    'token': csrfToken,
                    'id': id,
                    'job_id': jobid,
                    'counterstatus': counterstatus,
                    'worker_user_id': workerid,
                    'employer_id': employerid,
                    'is_draft': "1",
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + result.message,
                        time: 5
                    });
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
    setInterval(function () {
        $(document).ready(function () {
            $('.application-job-slider-owl').owlCarousel({
                items: 3,
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                margin: 20,
                nav: false,
                dots: false,
                navText: ['<span class="fa fa-angle-left  fa-2x"></span>', '<span class="fas fa fa-angle-right fa-2x"></span>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    480: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    992: {
                        items: 2
                    }
                }
            })
        })
    }, 3000)

    function updateJob() {
        notie.alert({
            type: 'success',
            text: '<i class="fa fa-check"></i> Job Updated Successfully.',
            time: 3
        });
    }

</script>
<script>
    var speciality = {};

    // console.log(window.allspecialty)
    // console.log(window.allvaccinations)
    // console.log(window.allcertificate)

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

        // var container = document.getElementById('add-more-certifications');

        // var newSelect = document.createElement('select');
        // newSelect.name = 'certificate';
        // newSelect.className = 'mb-3';

        // var originalSelect = document.getElementById('certificate');
        // var options = originalSelect.querySelectorAll('option');
        // for (var i = 0; i < options.length; i++) {
        //     var option = options[i].cloneNode(true);
        //     newSelect.appendChild(option);
        // }
        // container.querySelector('.col-md-11').appendChild(newSelect);

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

    function remove_vaccinations(obj, key) {
        if (vaccinations.hasOwnProperty($(obj).data('key'))) {

            var element = document.getElementById("remove-vaccinations");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'vaccinations': key,
                    }
                    let removetype = 'vaccinations';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('employer/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function (data) {
                            // notie.alert({
                            //     type: 'success',
                            //     text: '<i class="fa fa-check"></i> ' + data.message,
                            //     time: 5
                            // });
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }
                delete window.allvaccinations[$(obj).data('key')];
                delete vaccinations[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_vaccinations();
        }
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

    function remove_certificate(obj, key) {
        if (certificate.hasOwnProperty($(obj).data('key'))) {
            var element = document.getElementById("remove-certificate");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'certificate': key,
                    }
                    let removetype = 'certificate';
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
                delete window.allcertificate[$(obj).data('key')];
                delete certificate[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_certifications();
        }
    }
</script>
<script>
    function askWorker(e, type, workerid, jobid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('employer/ask-employer-notification') }}",
                data: {
                    'token': csrfToken,
                    'worker_id': workerid,
                    'update_key': type,
                    'job_id': jobid,
                },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> ' + result.message,
                        time: 5
                    });
                },
                error: function (error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
    const numberOfReferencesField = document.getElementById('number_of_references');
    numberOfReferencesField.addEventListener('input', function () {
        if (numberOfReferencesField.value.length > 9) {
            numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
        }
    });
    $(document).ready(function () {
        let formData = {
            'country_id': '233',
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-states') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#facility-state-code');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility State Code"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    function searchCity(e) {
        var selectedValue = e.value;
        console.log("Selected Value: " + selectedValue);
        let formData = {
            'state_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-cities') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#facility-city');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility City"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
    function getSpecialitiesByProfession(e) {
        var selectedValue = e.value;
        let formData = {
            'profession_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-profession-specialities') }}",
            data: formData,
            dataType: 'json',
            success: function (data) {
                var stateSelect = $('#preferred_specialty');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Specialty"
                }));
                $.each(data.data, function (index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
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
<script type="text/javascript">


    const slidePage = document.querySelector(".slide-page");
    const nextBtnFirst = document.querySelector(".firstNext");
    const prevBtnSec = document.querySelector(".prev-1");
    const nextBtnSec = document.querySelector(".next-1");
    const prevBtnThird = document.querySelector(".prev-2");
    const nextBtnThird = document.querySelector(".next-2");
    const prevBtnFourth = document.querySelector(".prev-3");
    const submitBtn = document.querySelector(".submit");
    const progressText = document.querySelectorAll(".step p");
    const progressCheck = document.querySelectorAll(".step .check");
    const bullet = document.querySelectorAll(".step .bullet");
    let current = 1;

    // Validation
    // first Slide
    function validateFirst() {
        var access = true;
        var jobName = document.getElementById("job_name").value;
        var jobType = document.getElementById("job_type").value;
        var specialty = document.getElementById("preferred_specialty").value;
        var profession = document.getElementById("perferred_profession").value;
        var city = document.getElementById("job_city").value;
        var state = document.getElementById("job_state").value;
        var weeklyPay = document.getElementById("weekly_pay").value;


        if (jobName.trim() === '') {
            $('.help-block-job_name').text('Please enter the job name');
            $('.help-block-job_name').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_name').text('');

        }

        if (jobType.trim() === "") {
            $('.help-block-job_type').text('Please enter the job type');
            $('.help-block-job_type').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_type').text('');

        }

        if (specialty.trim() === '') {
            $('.help-block-preferred_specialty').text('Please enter the job speciality');
            $('.help-block-preferred_specialty').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialty').text('');

        }

        if (profession.trim() === '') {
            $('.help-block-perferred_profession').text('Please enter the job profession');
            $('.help-block-perferred_profession').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_profession').text('');

        }

        if (city.trim() === '') {
            $('.help-block-job_city').text('Please enter the job city');
            $('.help-block-job_city').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_city').text('');

        }

        if (state.trim() === '') {
            $('.help-block-job_state').text('Please enter the job state');
            $('.help-block-job_state').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_state').text('');

        }

        if (weeklyPay.trim() === '') {
            $('.help-block-weekly_pay').text('Please enter the job weekly pay');
            $('.help-block-weekly_pay').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-weekly_pay').text('');

        }
        if(access){
            return true;
        }else{
            return false;
        }


    }

    nextBtnFirst.addEventListener("click", function (event) {
        event.preventDefault();
        if(validateFirst()){
            slidePage.style.marginLeft = "-25%";
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }


    });
    nextBtnSec.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-50%";
        bullet[current - 1].classList.add("active");
        progressCheck[current - 1].classList.add("active");
        progressText[current - 1].classList.add("active");
        current += 1;
    });
    nextBtnThird.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-75%";
        bullet[current - 1].classList.add("active");
        progressCheck[current - 1].classList.add("active");
        progressText[current - 1].classList.add("active");
        current += 1;
    });
    submitBtn.addEventListener("click", function () {
        bullet[current - 1].classList.add("active");
        progressCheck[current - 1].classList.add("active");
        progressText[current - 1].classList.add("active");
        current += 1;
        // setTimeout(function () {
        //     alert("Your Form Successfully Signed up");
        //     location.reload();
        // }, 800);
    });

    prevBtnSec.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "0%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });
    prevBtnThird.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-25%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });
    prevBtnFourth.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-50%";
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });





</script>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    .all {
        font-family: 'Neue Kabel';
        margin: 0;
        padding: 0;
        outline: none;

    }

    .bodyAll {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        overflow: hidden;
        width: 80%;
        margin: 0 auto;


    }

    ::selection {
        color: #fff;
        background: #b5649e;
    }

    .container {

        margin-top: 9%;
        /* background: #fff; */
        text-align: center;
        /* border-radius: 5px; */
        padding: 50px 35px 10px 35px;
        /* shadow */
        border: 2px solid #3D2C39 !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
    }

    .container header {
        font-size: 35px;
        font-weight: 500;
        margin: 0 0 30px 0;
    }

    .container .form-outer {
        width: 100%;
        overflow: hidden;
    }

    .container .form-outer form {
        display: flex;
        width: 400%;
    }

    .form-outer form .page {
        width: 25%;
        transition: margin-left 0.3s ease-in-out;
    }

    .form-outer form .page .title {
        text-align: left;
        font-size: 25px;
        font-weight: 500;
    }

    .form-outer form .page .field {

        height: 45px;
        margin: 45px 0;
        display: flex;
        position: relative;
    }

    form .page .field .label {
        position: absolute;
        top: -30px;
        font-weight: 500;
    }

    form .page .field input {
        height: 100%;
        width: 100%;
        /* border: 1px solid lightgrey; */
        /* border-radius: 5px;
        padding-left: 15px;
        font-size: 18px; */


    }

    .ss-account-form-lft-1 .ss-form-group select {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
    }

    .ss-account-form-lft-1 input,
    .ss-account-form-lft-1 select {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
        margin-bottom: 2px !important;
    }

    textarea {
        border: 2px solid #3D2C39 !important;
        width: 90%;
        padding: 10px !important;
        box-shadow: 10px 10px 0px 0px #3D2C39;
        border-radius: 12px;
        background: #fff !important;
    }


    form .page .field select {
        width: 100%;
        padding-left: 10px;
        font-size: 17px;
        font-weight: 500;
    }

    form .page .field button {
        width: 20%;
        height: calc(100% + 5px);
        border: none;
        /* background: #d33f8d; */
        margin-top: -20px;
        /* border-radius: 5px; */
        color: #fff;
        cursor: pointer;
        font-size: 18px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: 0.5s ease;

        background: #3D2C39;
        color: #fff;
        padding: 10px;
        border-radius: 100px;

    }

    form .page .field button:hover {
        background: #000;
    }

    form .page .btns button {
        margin-top: -20px !important;
    }

    form .page .btns button.prev {
        margin-right: 3px;
        font-size: 17px;
    }

    form .page .btns button.next {
        margin-left: 3px;
    }

    .container .progress-bar-item {
        display: flex;
        margin: 40px 0;
        user-select: none;
    }

    .container .progress-bar-item .step {
        text-align: center;
        position: relative;
    }

    .container .progress-bar-item .step p {
        font-weight: 500;
        font-size: 18px;
        color: #000;
        margin-bottom: 8px;
    }

    .progress-bar-item .step .bullet {
        height: 25px;
        width: 25px;
        border: 2px solid #000;
        display: inline-block;
        border-radius: 50%;
        position: relative;
        transition: 0.2s;
        font-weight: 500;
        font-size: 17px;
        line-height: 25px;
    }

    .progress-bar-item .step .bullet.active {
        border-color: #b5649e;
        background: #b5649e;
    }

    .progress-bar-item .step .bullet span {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .progress-bar-item .step .bullet.active span {
        display: none;
    }

    /* transition progress line */

    /* .progress-bar-item .step .bullet:before,
    .progress-bar-item .step .bullet:after {
        position: absolute;
        content: '';
        bottom: 11px;

        height: 3px;
        width: 1000%;
        background: #262626;
    } */

    .progress-bar-item .step .bullet.active:after {
        background: #b5649e;
        transform: scaleX(0);
        transform-origin: left;
        animation: animate 0.3s linear forwards;
    }

    @keyframes animate {
        100% {
            transform: scaleX(1);
        }
    }

    .progress-bar-item .step:last-child .bullet:before,
    .progress-bar-item .step:last-child .bullet:after {
        display: none;
    }

    .progress-bar-item .step p.active {
        color: #b5649e !important;
        transition: 0.2s linear;
    }

    .progress-bar-item .step .check {
        position: absolute;
        left: 50%;
        top: 70%;
        font-size: 15px;
        transform: translate(-50%, -50%);
        display: none;
    }

    .progress-bar-item .step .check.active {
        display: block;
        color: #fff;
    }

    .saveDrftBtn {
        border: 1px solid #3D2C39 !important;
        color: #3D2C39 !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: 100%;
        margin-top: 25px;
        background: transparent !important;
        margin-right: 6px;

    }
</style>
@endsection

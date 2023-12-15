@extends('employer::layouts.main')

@section('content')
<main style="padding-top: 120px" class="ss-main-body-sec">
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




            <div class="ss-account-form-lft-1 border-0 p-0 m-0 bg-transparent d-none" id="create_job_request_form">
                <div class="ss-opport-mngr-hedr">
                    <h5>Create Job Request</h5>
                </div>
                <div class="row">
                    <form class="ss-emplor-form-sec" id="create-new-job">
                        <div class="row">
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name"
                                    placeholder="Enter Job Location (City, State)">
                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter job name">
                            </div>
                            <div class="ss-form-group col-md-4">

                                <select name="terms" id="term">
                                    <option value="">Specialty</option>
                                    <option value="1">Term Option 1</option>
                                    <option value="2">Term Option 2</option>
                                    <option value="3">Term Option 3</option>
                                    <!-- Add more static options as needed -->
                                </select>

                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter Work Location">
                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter Work Duration">
                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter Weekly Pay">
                            </div>
                            <div class="ss-form-group col-md-4">

                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <textarea type="text" name="job_name" id="job_name"
                                    placeholder="Enter Job Description"></textarea>
                            </div>

                        </div>
                        <div class="ss-crt-opper-buttons">
                            <a href="javascript:void(0)" class="ss-reject-offer-btn text-center w-50"
                                onclick="createDraft()">Save As Draft</a>
                            <a href="javascript:void(0)" class="ss-counter-button text-center w-50"
                                onclick="createJob()">Publish Now</a>
                        </div>
                    </form>
                </div>
            </div>

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
                                    <li><a href="#"><img src="  {{URL::asset('frontend/img/location.png')}}">
                                            Los
                                            Angeles, CA</a></li>
                                    <li><a href="#"><img src="http://127.0.0.1:8000/frontend/img/calendar.png">
                                            10
                                            wks</a></li>
                                    <li><a href="#"><img src="http://127.0.0.1:8000/frontend/img/dollarcircle.png">
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
                                                                src="http://127.0.0.1:8000/frontend/img/location.png">
                                                            Los
                                                            Angeles, CA</a></li>
                                                    <li><a href="#"><img
                                                                src="http://127.0.0.1:8000/frontend/img/calendar.png">
                                                            10
                                                            wks</a></li>
                                                    <li><a href="#"><img
                                                                src="http://127.0.0.1:8000/frontend/img/dollarcircle.png">
                                                            2500/wk</a></li>
                                                </ul>

                                            </div>
                                            <div class="col-6 ss-job-prfle-sec">
                                                <p>Travel <span>+50 Applied</span></p>
                                                <h4>Manager CRNA - Anesthesia</h4>
                                                <h6>Medical Solutions Recruiter</h6>
                                                <ul>
                                                    <li><a href="#"><img
                                                                src="http://127.0.0.1:8000/frontend/img/location.png">
                                                            Los
                                                            Angeles, CA</a></li>
                                                    <li><a href="#"><img
                                                                src="http://127.0.0.1:8000/frontend/img/calendar.png">
                                                            10
                                                            wks</a></li>
                                                    <li><a href="#"><img
                                                                src="http://127.0.0.1:8000/frontend/img/dollarcircle.png">
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
                                                                    src="http://127.0.0.1:8000/recruiter/assets/images/recomand-img-1.png"
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
                                                                    src="http://127.0.0.1:8000/recruiter/assets/images/recomand-img-2.png"
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
                                                                    src="http://127.0.0.1:8000/recruiter/assets/images/recomand-img-3.png"
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
                                                                    src="http://127.0.0.1:8000/recruiter/assets/images/message-img4.png"
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
            document.getElementById("details_draft").classList.remove("d-none");
            document.getElementById("details_onhold_published").classList.add("d-none");
        } else {
            document.getElementById("details_onhold_published").classList.remove("d-none");
            document.getElementById("details_draft").classList.add("d-none");
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
                url: "{{ url('recruiter/get-job-listing') }}",
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
                    if (result.joblisting != "") {
                        document.getElementById("published-job-details").classList.remove("d-none");
                        document.getElementById("no-job-posted").classList.add("d-none");
                    }
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

        opportunitiesType('published')
        document.getElementById("details_onhold_published").classList.add("d-none");
    });

    function editOpportunity(id = "", formtype) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/get-job-listing') }}",
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
                url: "{{ url('recruiter/recruiter-create-opportunity') }}/update",
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
                    url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

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




    function offerSend(id, jobid, type, workerid, recruiterid) {
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
                url: "{{ url('recruiter/recruiter-send-job-offer') }}",
                data: {
                    'token': csrfToken,
                    'id': id,
                    'job_id': jobid,
                    'counterstatus': counterstatus,
                    'worker_user_id': workerid,
                    'recruiter_id': recruiterid,
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
                        url: "{{ url('recruiter/remove') }}/" + removetype,
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
                        url: "{{ url('recruiter/remove') }}/" + removetype,
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
                        url: "{{ url('recruiter/remove') }}/" + removetype,
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
                url: "{{ url('recruiter/ask-recruiter-notification') }}",
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
@endsection

@extends('layouts.dashboard')
@section('mytitle', 'My Profile')
@section('content')
    <!--Main layout-->
    <main style="padding-top: 130px" class="ss-main-body-sec">
        <div class="container">

            <div class="ss-my-work-jorny-btn-dv">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>My Work Journey</h2>
                    </div>

                    <div class="col-lg-6">
                        <div class="ss-my-work-tab-div">
                            <ul onclick="myFunction(event)" id='navList'>
                                <li><a href="#" class="ss-saved-btn active">Saved</a></li>
                                <li><a href="#" class="ss-applied-btn">Applied</a></li>
                                <li><a href="#" class="ss-offered-btn">Offered</a></li>
                                <li><a href="#" class="ss-hired-btn">Hired</a></li>
                                <li><a href="#" class="ss-past-btn">Past</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <!--------------my work journey saved---------------->

            <div class="ss-my-work-jorny-sved-div">
                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-mywrk-jrny-left-dv">
                            <div class="ss-jb-dtl-icon-ul">
                                <h5>Saved</h5>
                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec my-work-sved-job-div1">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/bookmark.png" /></a>
                            </div>


                            <div class="ss-job-prfle-sec my-work-sved-job-div2">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/bookmark.png" /></a>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-7">
                        <!--------saved rit div-1--------->

                        <div class="ss-journy-svd-jbdtl-dv ss-saved-jb-dtls-dv1">

                            <!----------------jobs applay view details--------------->

                            <div class="ss-job-apply-on-view-detls-mn-dv">
                                <div class="ss-job-apply-on-tx-bx-hed-dv">
                                    <ul>
                                        <li>
                                            <p>Recruiter</p>
                                        </li>
                                        <li><img src="img/recruiteri-img.png" />Emma Watson</li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <span>GWJ234065</span>
                                            <h6>17 Applied</h6>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-jb-aap-on-txt-abt-dv">
                                    <h5>About job</h5>
                                    <ul>
                                        <li>
                                            <h6>Organization Name</h6>
                                            <p>Hogwarts</p>
                                        </li>
                                        <li>
                                            <h6>Date Posted</h6>
                                            <p>May 18</p>
                                        </li>
                                        <li>
                                            <h6>Type</h6>
                                            <p>Clinical</p>
                                        </li>
                                        <li>
                                            <h6>Terms</h6>
                                            <p>Contact</p>
                                        </li>

                                    </ul>
                                </div>


                                <div class="ss-jb-apply-on-disc-txt">
                                    <h5>Description</h5>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>


                                <!-------Work Information------->
                                <div class="ss-jb-apl-oninfrm-mn-dv">
                                    <ul class="ss-jb-apply-on-inf-hed">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Diploma</span>
                                            <h6>College Diploma</h6>
                                        </li>
                                        <li>
                                            <p>Did you really graduate?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>drivers license</span>
                                            <h6>Required</h6>
                                        </li>
                                        <li>
                                            <p>Are you really allowed to drive?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Worked at Facility Before</span>
                                            <h6>In the last 18 months</h6>
                                        </li>
                                        <li>
                                            <p>Are you sure you never worked here as staff?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>SS# or SS Card</span>
                                            <h6>Last 4 digits of SS#</h6>
                                        </li>
                                        <li>
                                            <p>Yes we need your SS# to submit you</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
                                        <li>
                                            <span>Profession</span>
                                            <h6>RN</h6>
                                        </li>
                                        <li>
                                            <p>RN</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Specialty</span>
                                            <h6>Peds CVICU</h6>
                                        </li>
                                        <li>
                                            <p>ICU</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Professional Licensure</span>
                                            <h6>TX, Compact</h6>
                                        </li>
                                        <li>
                                            <p>Where are you licensed?</p>
                                        </li>
                                    </ul>


                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Experience</span>
                                            <h6>3 Years </h6>
                                        </li>
                                        <li>
                                            <p>How long have you done this?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Vaccinations & Immunizations</span>
                                            <h6>COVID Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Did you get the COVID Vaccines?</p>
                                            <p>Did you get the Flu Vaccines?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>References</span>
                                            <h6>2 references </h6>
                                            <h6>12 months Recency</h6>
                                        </li>
                                        <li>
                                            <p>Who are your References?</p>
                                            <p>Is this from your last assignment?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Certifications</span>
                                            <h6>BLS Required</h6>
                                            <h6>ACLS Required</h6>
                                            <h6>PALS Preferred</h6>
                                            <h6>CCRN Preferred</h6>
                                        </li>
                                        <li>
                                            <p>You don't have a BLS?</p>
                                            <p>No ACLS?</p>
                                            <p>No PALS?</p>
                                            <p>No CCRN?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Skills checklist</span>
                                            <h6>Peds CVICU RN </h6>

                                        </li>
                                        <li>
                                            <p>Upload your latest skills checklist</p>

                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Eligible to work in the US</span>
                                            <h6>Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Does Congress allow you to work here?</p>

                                        </li>
                                    </ul>

                                    <div class="ss-job-apl-on-app-btn">
                                        <button>Apply Now</button>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!--------saved rit div-2--------->

                        <div class="ss-journy-svd-jbdtl-dv ss-saved-jb-dtls-dv2">
                            <div class="ss-job-dtls-view-bx">
                                <h6>Recruiter</h6>
                                <ul>
                                    <li><img src="img/recruiteri-img.png"></li>
                                    <li>
                                        <p>Emma Watson2</p>
                                    </li>
                                </ul>
                                <div class="ss-jb-dtl-abt-txt">
                                    <h6>About job</h6>
                                    <h5>Preferred shift:</h5>
                                    <p>Day Shift</p>
                                    <ul>
                                        <li>
                                            <h5>Relevant experience: </h5>
                                            <p>3 Years</p>
                                        </li>
                                        <li>
                                            <h5>EMR:</h5>
                                            <p>MedTech (&lt; 2 experience)</p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-meditch-text-bx">
                                    <ul>
                                        <li>
                                            <h5>Meditech: </h5>
                                            <p>Beginner(1 years experience)</p>
                                        </li>
                                        <li>
                                            <h5>Epic: </h5>
                                            <p>Advanced (5+ year's experience)</p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Description</h6>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>

                                <div class="ss-jb-dtl-apply-btn">
                                    <button type="text">Already Applied</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------my work journey saved---------------->



            <!--------------my work journey Applied---------------->

            <div class="ss-my-wrk-apply-mn-sec">

                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-mywrk-jrny-left-dv">

                            <div class="ss-jb-dtl-icon-ul ss-my-wrk-aply-jb-dv1">
                                <h5>Applied</h5>

                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv1">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/job-icon-bx-Vector.png"></a>
                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv2">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/job-icon-bx-Vector.png"></a>
                            </div>



                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="ss-journy-svd-jbdtl-dv">
                            <!----------------jobs applay view details--------------->

                            <div class="ss-job-apply-on-view-detls-mn-dv">
                                <div class="ss-job-apply-on-tx-bx-hed-dv">
                                    <ul>
                                        <li>
                                            <p>Recruiter</p>
                                        </li>
                                        <li><img src="img/recruiteri-img.png" />Emma Watson</li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <span>GWJ234065</span>
                                            <h6>17 Applied</h6>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-jb-aap-on-txt-abt-dv">
                                    <h5>About job</h5>
                                    <ul>
                                        <li>
                                            <h6>Organization Name</h6>
                                            <p>Hogwarts</p>
                                        </li>
                                        <li>
                                            <h6>Date Posted</h6>
                                            <p>May 18</p>
                                        </li>
                                        <li>
                                            <h6>Type</h6>
                                            <p>Clinical</p>
                                        </li>
                                        <li>
                                            <h6>Terms</h6>
                                            <p>Contact</p>
                                        </li>

                                    </ul>
                                </div>


                                <div class="ss-jb-apply-on-disc-txt">
                                    <h5>Description</h5>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>


                                <!-------Work Information------->
                                <div class="ss-jb-apl-oninfrm-mn-dv">
                                    <ul class="ss-jb-apply-on-inf-hed">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Diploma</span>
                                            <h6>College Diploma</h6>
                                        </li>
                                        <li>
                                            <p>Did you really graduate?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>drivers license</span>
                                            <h6>Required</h6>
                                        </li>
                                        <li>
                                            <p>Are you really allowed to drive?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Worked at Facility Before</span>
                                            <h6>In the last 18 months</h6>
                                        </li>
                                        <li>
                                            <p>Are you sure you never worked here as staff?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>SS# or SS Card</span>
                                            <h6>Last 4 digits of SS#</h6>
                                        </li>
                                        <li>
                                            <p>Yes we need your SS# to submit you</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-blue">
                                        <li>
                                            <span>Profession</span>
                                            <h6>RN</h6>
                                        </li>
                                        <li>
                                            <p>RN</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul ss-s-jb-apl-bg-pink">
                                        <li>
                                            <span>Specialty</span>
                                            <h6>Peds CVICU</h6>
                                        </li>
                                        <li>
                                            <p>ICU</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Professional Licensure</span>
                                            <h6>TX, Compact</h6>
                                        </li>
                                        <li>
                                            <p>Where are you licensed?</p>
                                        </li>
                                    </ul>


                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Experience</span>
                                            <h6>3 Years </h6>
                                        </li>
                                        <li>
                                            <p>How long have you done this?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Vaccinations & Immunizations</span>
                                            <h6>COVID Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Did you get the COVID Vaccines?</p>
                                            <p>Did you get the Flu Vaccines?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>References</span>
                                            <h6>2 references </h6>
                                            <h6>12 months Recency</h6>
                                        </li>
                                        <li>
                                            <p>Who are your References?</p>
                                            <p>Is this from your last assignment?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Certifications</span>
                                            <h6>BLS Required</h6>
                                            <h6>ACLS Required</h6>
                                            <h6>PALS Preferred</h6>
                                            <h6>CCRN Preferred</h6>
                                        </li>
                                        <li>
                                            <p>You don't have a BLS?</p>
                                            <p>No ACLS?</p>
                                            <p>No PALS?</p>
                                            <p>No CCRN?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Skills checklist</span>
                                            <h6>Peds CVICU RN </h6>

                                        </li>
                                        <li>
                                            <p>Upload your latest skills checklist</p>

                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Eligible to work in the US</span>
                                            <h6>Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Does Congress allow you to work here?</p>

                                        </li>
                                    </ul>

                                    <div class="ss-job-apl-on-app-btn">
                                        <button class="apply-disable">Apply Now</button>
                                    </div>

                                </div>

                            </div>




                        </div>
                    </div>
                </div>

            </div>



            <!--------------my work journey Applied---------------->





            <!--------------my work journey offred---------------->

            <div class="ssmy-jorey-offred-mn-sec">
                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-mywrk-jrny-left-dv">
                            <div class="ss-jb-dtl-icon-ul">
                                <h5>Offred</h5>
                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv2">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-countrsave-ico">Counter Saved</a>
                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv2">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/job-icon-bx-Vector.png"></a>
                            </div>





                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="ss-journy-svd-jbdtl-dv">
                            <div class="ss-job-apply-on-view-detls-mn-dv">

                                <div class="ss-job-view-off-text-fst-dv">
                                    <p>On behalf of <a href="">Albus Percival , Hogwarts</a> would like to offer <a
                                            href="#">GWJ234065</a> to <a href="#">James Bond</a> with the
                                        following terms. This offer is only available for the next <a hre="#">6
                                            weeks:</a></p>
                                </div>
                                <div class="ss-jb-apply-on-disc-txt">
                                    <h5>Description</h5>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>


                                <!-------Work Information------->
                                <div class="ss-job-ap-on-offred-new-dv">

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-bg-pink">
                                        <li>
                                            <p>Profession</p>
                                            <h6>RN</h6>
                                        </li>
                                        <li>
                                            <p> Specialty</p>
                                            <h6>Peds CVICU</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Professional Licensure</p>
                                            <h6>TX, Compact</h6>
                                        </li>
                                        <li>
                                            <p>Experience</p>
                                            <h6>3 Years</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>COVID Required</h6>
                                        </li>
                                        <li>
                                            <p>Vaccinations & Immunizations name</p>
                                            <h6>Flu 2022 preferred</h6>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                        <li>
                                            <p>Type</p>
                                            <h6>Clinical</h6>
                                        </li>
                                    </ul>

                                    <div class="ss-job-apl-on-offer-btn">
                                        <button class="ss-acpect-offer-btn">Accept Offer</button>
                                        <ul>
                                            <li><a href="counter-offered.html"><button class="ss-counter-btn">Counter
                                                        offer</button></a></li>
                                            <li><button class="ss-reject-offer-btn">Reject Offer</button></li>
                                        </ul>
                                    </div>

                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <!--------------my work journey offred---------------->




            <!--------------my work journey Hired---------------->

            <div class="ss-my-work-jorny-hired">
                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-mywrk-jrny-left-dv">

                            <div class="ss-jb-dtl-icon-ul ss-my-wrk-aply-jb-dv1">
                                <h5>Hired</h5>

                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv1">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/job-icon-bx-Vector.png"></a>
                            </div>


                            <!-------->
                            <div class="ss-job-prfle-sec ss-my-wrk-aply-jb-dv2">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jb-prfl-save-ico"><img src="img/job-icon-bx-Vector.png"></a>
                            </div>



                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="ss-journy-svd-jbdtl-dv">
                            <!----------------jobs applay view details--------------->

                            <div class="ss-job-apply-on-view-detls-mn-dv">
                                <div class="ss-job-apply-on-tx-bx-hed-dv">
                                    <ul>
                                        <li>
                                            <p>Recruiter</p>
                                        </li>
                                        <li><img src="img/recruiteri-img.png" />Emma Watson</li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <span>GWJ234065</span>
                                            <h6>17 Applied</h6>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-jb-aap-on-txt-abt-dv">
                                    <h5>About job</h5>
                                    <ul>
                                        <li>
                                            <h6>Organization Name</h6>
                                            <p>Hogwarts</p>
                                        </li>
                                        <li>
                                            <h6>Date Posted</h6>
                                            <p>May 18</p>
                                        </li>
                                        <li>
                                            <h6>Type</h6>
                                            <p>Clinical</p>
                                        </li>
                                        <li>
                                            <h6>Terms</h6>
                                            <p>Contact</p>
                                        </li>

                                    </ul>
                                </div>


                                <div class="ss-jb-apply-on-disc-txt">
                                    <h5>Description</h5>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>


                                <!-------Work Information------->
                                <div class="ss-jb-apl-oninfrm-mn-dv">
                                    <ul class="ss-jb-apply-on-inf-hed">
                                        <li>
                                            <h5>Work Information</h5>
                                        </li>
                                        <li>
                                            <h5>Your Information</h5>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Diploma</span>
                                            <h6>College Diploma</h6>
                                        </li>
                                        <li>
                                            <p>Did you really graduate?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>drivers license</span>
                                            <h6>Required</h6>
                                        </li>
                                        <li>
                                            <p>Are you really allowed to drive?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Worked at Facility Before</span>
                                            <h6>In the last 18 months</h6>
                                        </li>
                                        <li>
                                            <p>Are you sure you never worked here as staff?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>SS# or SS Card</span>
                                            <h6>Last 4 digits of SS#</h6>
                                        </li>
                                        <li>
                                            <p>Yes we need your SS# to submit you</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Profession</span>
                                            <h6>RN</h6>
                                        </li>
                                        <li>
                                            <p>RN</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Specialty</span>
                                            <h6>Peds CVICU</h6>
                                        </li>
                                        <li>
                                            <p>ICU</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Professional Licensure</span>
                                            <h6>TX, Compact</h6>
                                        </li>
                                        <li>
                                            <p>Where are you licensed?</p>
                                        </li>
                                    </ul>


                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Experience</span>
                                            <h6>3 Years </h6>
                                        </li>
                                        <li>
                                            <p>How long have you done this?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Vaccinations & Immunizations</span>
                                            <h6>COVID Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Did you get the COVID Vaccines?</p>
                                            <p>Did you get the Flu Vaccines?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>References</span>
                                            <h6>2 references </h6>
                                            <h6>12 months Recency</h6>
                                        </li>
                                        <li>
                                            <p>Who are your References?</p>
                                            <p>Is this from your last assignment?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Certifications</span>
                                            <h6>BLS Required</h6>
                                            <h6>ACLS Required</h6>
                                            <h6>PALS Preferred</h6>
                                            <h6>CCRN Preferred</h6>
                                        </li>
                                        <li>
                                            <p>You don't have a BLS?</p>
                                            <p>No ACLS?</p>
                                            <p>No PALS?</p>
                                            <p>No CCRN?</p>
                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Skills checklist</span>
                                            <h6>Peds CVICU RN </h6>

                                        </li>
                                        <li>
                                            <p>Upload your latest skills checklist</p>

                                        </li>
                                    </ul>

                                    <ul class="ss-s-jb-apl-on-inf-txt-ul">
                                        <li>
                                            <span>Eligible to work in the US</span>
                                            <h6>Required</h6>
                                            <h6>Flu 2022 Preferred</h6>
                                        </li>
                                        <li>
                                            <p>Does Congress allow you to work here?</p>

                                        </li>
                                    </ul>

                                    <div class="ss-job-apl-on-app-btn">
                                        <button class="">Chat</button>
                                    </div>

                                </div>

                            </div>




                        </div>
                    </div>
                </div>
            </div>

            <!--------------my work journey Hired---------------->



            <!--------------my work journey Past---------------->

            <div class="ss-my-jorney-past-mn-sec">

                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-mywrk-jrny-left-dv">
                            <div class="ss-jb-dtl-icon-ul">
                                <ul class="ss-jb-dtl-btn">
                                    <li><a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                                    <li>
                                        <h5>Past</h5>
                                    </li>
                                    <li><a href="#"><i class="fa fa-share" aria-hidden="true"></i></a></li>
                                </ul>
                            </div>



                            <!-------->
                            <div class="ss-job-prfle-sec">
                                <p>Travel <span>+50 Applied</span></p>
                                <h4>Manager CRNA - Anesthesia</h4>
                                <h6>Medical Solutions Recruiter</h6>
                                <ul>
                                    <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                                    <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                                    <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                                </ul>
                                <h5>Recently Added</h5>
                                <a href="#" class="ss-jnry-app-btn">Applied</a>
                            </div>





                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="ss-journy-svd-jbdtl-dv">
                            <div class="ss-job-dtls-view-bx">
                                <h6>Recruiter</h6>
                                <ul>
                                    <li><img src="img/recruiteri-img.png"></li>
                                    <li>
                                        <p>Emma Watson</p>
                                    </li>
                                </ul>
                                <div class="ss-jb-dtl-abt-txt">
                                    <h6>About job</h6>
                                    <h5>Preferred shift:</h5>
                                    <p>Day Shift</p>
                                    <ul>
                                        <li>
                                            <h5>Relevant experience: </h5>
                                            <p>3 Years</p>
                                        </li>
                                        <li>
                                            <h5>EMR:</h5>
                                            <p>MedTech (&lt; 2 experience)</p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-meditch-text-bx">
                                    <ul>
                                        <li>
                                            <h5>Meditech: </h5>
                                            <p>Beginner(1 years experience)</p>
                                        </li>
                                        <li>
                                            <h5>Epic: </h5>
                                            <p>Advanced (5+ year's experience)</p>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-jb-dtl-disc-sec">
                                    <h6>Description</h6>
                                    <p>This position is accountable and responsible for nursing care administered under the
                                        direction of a Registered Nurse (Nurse Manager, Charge Nurse, and/or Staff Nurse).
                                        Nurse interns must utilize personal protective equipment such as gloves, gown, mask.
                                        <a href="#">Read More</a></p>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <!--------------my work journey Past---------------->








        </div>

    </main>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $(".ss-saved-btn").click(function() {
                $(".ss-my-work-jorny-sved-div").show();
                $(".ss-my-wrk-apply-mn-sec").hide();
                $(".ssmy-jorey-offred-mn-sec").hide();
                $(".ss-my-work-jorny-hired").hide();
                $(".ss-my-jorney-past-mn-sec").hide();
            });

            $(".ss-applied-btn").click(function() {
                $(".ss-my-wrk-apply-mn-sec").show();
                $(".ss-my-work-jorny-sved-div").hide();
                $(".ssmy-jorey-offred-mn-sec").hide();
                $(".ss-my-work-jorny-hired").hide();
                $(".ss-my-jorney-past-mn-sec").hide();
            });

            $(".ss-offered-btn").click(function() {
                $(".ssmy-jorey-offred-mn-sec").show();
                $(".ss-my-work-jorny-sved-div").hide();
                $(".ss-my-wrk-apply-mn-sec").hide();
                $(".ss-my-work-jorny-hired").hide();
                $(".ss-my-jorney-past-mn-sec").hide();
            });

            $(".ss-hired-btn").click(function() {
                $(".ss-my-work-jorny-hired").show();
                $(".ss-my-work-jorny-sved-div").hide();
                $(".ss-my-wrk-apply-mn-sec").hide();
                $(".ssmy-jorey-offred-mn-sec").hide();
                $(".ss-my-jorney-past-mn-sec").hide();
            });

            $(".ss-past-btn").click(function() {
                $(".ss-my-jorney-past-mn-sec").show();
                $(".ss-my-work-jorny-sved-div").hide();
                $(".ss-my-wrk-apply-mn-sec").hide();
                $(".ss-my-work-jorny-hired").hide();
                $(".ssmy-jorey-offred-mn-sec").hide();
            });


        });
    </script>

    <!----------saved script----->
    <script>
        $(document).ready(function() {
            $(".my-work-sved-job-div1").click(function() {
                $(".ss-saved-jb-dtls-dv1").show();
                $(".ss-saved-jb-dtls-dv2").hide();
            });

            $(".my-work-sved-job-div2").click(function() {
                $(".ss-saved-jb-dtls-dv2").show();
                $(".ss-saved-jb-dtls-dv1").hide();
            });
        });
    </script>


    <!----------Applyed script----->
    <script>
        $(document).ready(function() {
            $(".ss-my-wrk-aply-jb-dv1").click(function() {
                $(".ss-applyd-jb-dtl-bx-1").show();
                $(".ss-applyd-jb-dtl-bx-2").hide();
                $(".ss-applyd-jb-dtl-bx-3").hide();
            });

            $(".ss-my-wrk-aply-jb-dv2").click(function() {
                $(".ss-applyd-jb-dtl-bx-2").show();
                $(".ss-applyd-jb-dtl-bx-1").hide();
                $(".ss-applyd-jb-dtl-bx-3").hide();
            });

            $(".ss-my-wrk-aply-jb-dv3").click(function() {
                $(".ss-applyd-jb-dtl-bx-3").show();
                $(".ss-applyd-jb-dtl-bx-2").hide();
                $(".ss-applyd-jb-dtl-bx-1").hide();
            });
        });
    </script>


    <!----------offred script----->
    <script>
        $(document).ready(function() {
            $(".ss-jb-offrd-dv1").click(function() {
                $(".ss-my-jrny-offrd-dtls1").show();
                $(".ss-my-jrny-offrd-dtls2").hide();
            });

            $(".ss-jb-offrd-dv2").click(function() {
                $(".ss-my-jrny-offrd-dtls2").show();
                $(".ss-my-jrny-offrd-dtls1").hide();
            });


        });
    </script>




    <script>
        function myFunction(e) {
            if (document.querySelector('#navList a.active') !== null) {
                document.querySelector('#navList a.active').classList.remove('active');
            }
            e.target.className = "active";
        }
    </script>
@stop

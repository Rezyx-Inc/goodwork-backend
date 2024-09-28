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
                            {{-- <ul onclick="myFunction(event)" id='navList'> --}}
                            <ul>
                                <li><a href="{{ route('my-work-journey') }}"
                                        class="ss-saved-btn {{ request()->route()->getName() == 'my-work-journey' ? 'active' : '' }}">Saved</a>
                                </li>
                                <li><a href="{{ route('applied-jobs') }}"
                                        class="ss-applied-btn {{ request()->route()->getName() == 'applied-jobs' ? 'active' : '' }}">Applied</a>
                                </li>
                                <li><a href="{{ route('offered-jobs') }}"
                                        class="ss-offered-btn {{ request()->route()->getName() == 'offered-jobs' ? 'active' : '' }}">Offered</a>
                                </li>
                                <li><a href="{{ route('hired-jobs') }}"
                                        class="ss-hired-btn {{ request()->route()->getName() == 'hired-jobs' ? 'active' : '' }}">Hired</a>
                                </li>
                                <li><a href="{{ route('past-jobs') }}"
                                        class="ss-past-btn {{ request()->route()->getName() == 'past-jobs' ? 'active' : '' }}">Past</a>
                                </li>
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
                                <h5>Countr eoffer</h5>
                            </div>




                            {{-- <div class="ss-job-prfle-sec my-work-sved-job-div2">
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
                    </div> --}}

                        </div>
                    </div>

                    <!-----JOB CONTENT---->
                    <div class="col-lg-7">

                        {{-- <div class="ss-journy-svd-jbdtl-dv job-content"></div> --}}

                        <!-----counter form------>
                        <div class="ss-counter-form-mn-dv">
                            <form>
                                <div class="ss-form-group">
                                    <label>Type</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Clinical</option>
                                        <option value="saab">RN</option>
                                        <option value="mercedes">Allied Health Professional</option>
                                        <option value="audi">Therapy</option>
                                        <option value="audi">LPN/LVN</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Terms</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Contract</option>
                                        <option value="saab">RN</option>
                                        <option value="mercedes">Allied Health Professional</option>
                                        <option value="audi">Therapy</option>
                                        <option value="audi">LPN/LVN</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Profession</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">RN</option>
                                        <option value="saab">RN</option>
                                        <option value="mercedes">Allied Health Professional</option>
                                        <option value="audi">Therapy</option>
                                        <option value="audi">LPN/LVN</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-counter-form-uplpls-dv">
                                    <label>Specialty and Experience</label>
                                    <ul>
                                        <li>
                                            <div class="ss-form-group">

                                                <select name="cars" id="cars">
                                                    <option value="volvo">Peds CVICU</option>
                                                    <option value="saab">RN</option>
                                                    <option value="mercedes">Allied Health Professional</option>
                                                    <option value="audi">Therapy</option>
                                                    <option value="audi">LPN/LVN</option>
                                                    <option value="audi">Nurse Practitioner</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="ss-form-group">

                                                <select name="cars" id="cars">
                                                    <option value="volvo">3 Years</option>
                                                    <option value="saab">RN</option>
                                                    <option value="mercedes">Allied Health Professional</option>
                                                    <option value="audi">Therapy</option>
                                                    <option value="audi">LPN/LVN</option>
                                                    <option value="audi">Nurse Practitioner</option>
                                                </select>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href=""><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-count-profsn-lins-dv">
                                    <ul>
                                        <li>
                                            <label>Professional Licensure</label>
                                        </li>
                                        <li> <input type="checkbox" id="AutoOffers" name="AutoOffers" value="Bike"> <label
                                                for="AutoOffers">Compact</label></li>
                                    </ul>
                                    <select name="cars" id="cars">
                                        <option value="volvo">TX</option>
                                        <option value="saab">TX</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-countr-vacny-imznt">
                                    <label>Vaccinations & Immunizations name</label>
                                    <ul>
                                        <li>
                                            <p>COVID Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <p>Flu 2022 Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>
                                </div>

                                <div class="ss-counter-immu-plus-div">
                                    <ul>
                                        <li>
                                            <input type="text" name="Enter Vacc. or Immu. name"
                                                placeholder="Enter Vacc. or Immu. name">
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href=""><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-form-group">
                                    <label>number of references</label>
                                    <input type="text" name="number of references" placeholder="number of references">
                                </div>

                                <div class="ss-form-group">
                                    <label>min title of reference</label>
                                    <input type="text" name="number of references"
                                        placeholder="min title of reference">
                                </div>

                                <div class="ss-form-group">
                                    <label>recency of reference</label>
                                    <input type="text" name="number of references" placeholder="recency of reference">
                                </div>


                                <div class="ss-countr-certifctn-dv ss-countr-vacny-imznt">
                                    <label>Certifications</Label>
                                    <ul>
                                        <li>
                                            <p>BLS Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <p>ACLS Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <p>PALS Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>

                                    <ul>
                                        <li>
                                            <p>CCRN Required</p>
                                        </li>
                                        <li><a href="#"><img src="img/delete-img.png" /></a></li>
                                    </ul>
                                </div>

                                <div class="ss-counter-immu-plus-div">
                                    <ul>
                                        <li>
                                            <input type="text" name="Enter Vacc. or Immu. name"
                                                placeholder="Enter Certification">
                                        </li>
                                        <li>
                                            <div class="ss-prsn-frm-plu-div"><a href=""><i class="fa fa-plus"
                                                        aria-hidden="true"></i></a></div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="ss-form-group">
                                    <label>Skills checklist</label>
                                    <input type="text" name="number of references" placeholder="Enter Skills">
                                </div>

                                <div class="ss-form-group">
                                    <label>Urgency</label>
                                    <input type="text" name="number of references" placeholder="Enter Urgency ">
                                </div>

                                <div class="ss-form-group">
                                    <label># of Positions Available</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter # of Positions Available">
                                </div>

                                <div class="ss-form-group">
                                    <label>MSP</label>
                                    <input type="text" name="number of references" placeholder="Enter MSP">
                                </div>

                                <div class="ss-form-group">
                                    <label>VMS</label>
                                    <input type="text" name="number of references" placeholder="Enter VMS">
                                </div>

                                <div class="ss-form-group">
                                    <label># of Submissions in VMS</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter # of Submissions in VMS">
                                </div>

                                <div class="ss-form-group">
                                    <label>Block scheduling</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Block scheduling">
                                </div>

                                <div class="ss-form-group">
                                    <label>Float requirements</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Float requirements">
                                </div>

                                <div class="ss-form-group">
                                    <label>Facility Shift Cancellation Policy</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Facility Shift Cancellation Policy">
                                </div>

                                <div class="ss-form-group">
                                    <label>Float requirements</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Float requirements">
                                </div>

                                <div class="ss-form-group">
                                    <label>Contract Termination Policy</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Contract Termination Policy">
                                </div>

                                <div class="ss-form-group">
                                    <label>Traveler Distance From Facility</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Traveler Distance From Facility">
                                </div>

                                <div class="ss-form-group">
                                    <label>Facility</label>
                                    <input type="text" name="number of references" placeholder="Enter Facility">
                                </div>

                                <div class="ss-form-group">
                                    <label>Facility's Parent System</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Facility's Parent System">
                                </div>

                                <div class="ss-form-group">
                                    <label>Facility Average Rating</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Facility Average Rating">
                                </div>

                                <div class="ss-form-group">
                                    <label>Recruiter Average Rating</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Recruiter Average Rating">
                                </div>

                                <div class="ss-form-group">
                                    <label>Organization Average Rating</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Organization Average Rating">
                                </div>

                                <div class="ss-form-group">
                                    <label>Clinical Setting</label>
                                    <input type="text" name="number of references"
                                        placeholder="What setting do you prefer?">
                                </div>

                                <div class="ss-form-group">
                                    <label>Patient ratio</label>
                                    <input type="text" name="number of references"
                                        placeholder="How many patients can you handle?">
                                </div>

                                <div class="ss-form-group">
                                    <label>Clinical Setting</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Clinical Setting">
                                </div>

                                <div class="ss-form-group">
                                    <label>EMR</label>
                                    <input type="text" name="number of references"
                                        placeholder="What EMRs have you used?">
                                </div>

                                <div class="ss-form-group">
                                    <label>Unit</label>
                                    <input type="text" name="number of references" placeholder="Enter Unit">
                                </div>

                                <div class="ss-form-group">
                                    <label>Department</label>
                                    <input type="text" name="number of references" placeholder="Enter Department">
                                </div>

                                <div class="ss-form-group">
                                    <label>Bed Size</label>
                                    <input type="text" name="number of references" placeholder="Enter Bed Size">
                                </div>

                                <div class="ss-form-group">
                                    <label>Trauma Level</label>
                                    <input type="text" name="number of references" placeholder="Enter Trauma Level">
                                </div>

                                <div class="ss-form-group">
                                    <label>Scrub Color</label>
                                    <input type="text" name="number of references" placeholder="Enter Scrub Color">
                                </div>

                                <div class="ss-count-strt-dte ss-count-profsn-lins-dv">
                                    <ul>
                                        <li>
                                            <label>Start Date</label>
                                        </li>
                                        <li> <input type="checkbox" id="AutoOffers" name="AutoOffers" value="Bike">
                                            <label for="AutoOffers">As soon As Posible</label></li>
                                    </ul>
                                    <input type="date" id="birthday" name="birthday" placeholder="Select Date">
                                </div>

                                <div class="ss-form-group">
                                    <label>RTO</label>
                                    <input type="text" name="number of references" placeholder="Enter RTO">
                                </div>

                                <div class="ss-form-group">
                                    <label>Shift Time of Day</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Select Shift of Day</option>
                                        <option value="saab">TX</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Hours/Week</label>
                                    <input type="text" name="number of references" placeholder="Enter Hours/Week">
                                </div>

                                <div class="ss-form-group">
                                    <label>Guaranteed Hours</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Guaranteed Hours">
                                </div>

                                <div class="ss-form-group">
                                    <label>Hours/Shift</label>
                                    <input type="text" name="number of references" placeholder="Enter Hours/Shift">
                                </div>

                                <div class="ss-form-group">
                                    <label>Referral Bonus</label>
                                    <input type="text" name="number of references" placeholder="Enter Referral Bonus">
                                </div>

                                <div class="ss-form-group">
                                    <label>Sign-On Bonus</label>
                                    <input type="text" name="number of references" placeholder="Enter Sign-On Bonus">
                                </div>

                                <div class="ss-form-group">
                                    <label>Completion Bonus</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Completion Bonus">
                                </div>

                                <div class="ss-form-group">
                                    <label>Extension Bonus</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Extension Bonus">
                                </div>

                                <div class="ss-form-group">
                                    <label>Other Bonus</label>
                                    <input type="text" name="number of references" placeholder="Enter Other Bonus">
                                </div>

                                <div class="ss-form-group">
                                    <label>401K</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Select</option>
                                        <option value="saab">401K</option>
                                        <option value="audi">401K</option>
                                        <option value="audi">401K</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Health Insurance</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Select</option>
                                        <option value="saab">TX</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Dental</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Select</option>
                                        <option value="saab">TX</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Vision</label>
                                    <select name="cars" id="cars">
                                        <option value="volvo">Select</option>
                                        <option value="saab">TX</option>
                                        <option value="audi">Nurse Practitioner</option>
                                    </select>
                                </div>

                                <div class="ss-form-group">
                                    <label>Actual Hourly rate</label>
                                    <input type="text" name="number of references" placeholder="Enter Actual Rate">
                                </div>

                                <div class="ss-form-group">
                                    <label>Feels Like $/hrs</label>
                                    <input type="text" name="number of references" placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Overtime</label>
                                    <input type="text" name="number of references" placeholder="Enter Overtime">
                                </div>

                                <div class="ss-form-group">
                                    <label>Holiday</label>
                                    <input type="date" id="birthday" name="birthday">
                                </div>

                                <div class="ss-form-group">
                                    <label>On Call</label>
                                    <input type="text" name="number of references" placeholder="Enter On Call">
                                </div>

                                <div class="ss-form-group">
                                    <label>Orientation Rate</label>
                                    <input type="text" name="number of references"
                                        placeholder="Enter Orientation Rate">
                                </div>

                                <div class="ss-form-group">
                                    <label>Weekly Taxable amount</label>
                                    <input type="text" name="number of references" placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Weekly non-taxable amount</label>
                                    <input type="text" name="number of references" placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Organization Weekly Amount</label>
                                    <input type="text" id="Organization Weekly Amount"
                                        name="Organization Weekly Amount" placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Goodwork Weekly Amount</label>
                                    <input type="text" id="Goodwork Weekly Amount" name="Goodwork Weekly Amount"
                                        placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Total Organization Amount</label>
                                    <input type="text" id="Total Organization Amount" name="Total Organization Amount"
                                        placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Total Goodwork Amount</label>
                                    <input type="text" id="Total Goodwork Amount" name="Total Goodwork Amount"
                                        placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Total Contract Amount</label>
                                    <input type="text" id="Contract Amount" name="Contract Amount" placeholder="---">
                                </div>

                                <div class="ss-form-group">
                                    <label>Goodwork Number</label>
                                    <input type="text" id="Goodwork Number" name="Goodwork Number"
                                        placeholder="Unique Key">
                                </div>

                                <div class="ss-counter-buttons-div">
                                    <button class="ss-counter-button">Counter Offer</button>
                                    <button class="counter-save-for-button">Save for Later</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

            <!--------------my work journey saved---------------->

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

    <script>
        $(document).ready(function() {
            $('.job-list')[0].click('active');
        });
        // function fetch_job_content(obj)
        // {
        //     if (!$(obj).hasClass('active')) {
        //         $('.job-list').removeClass('active')
        //         ajaxindicatorstart();
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             url: full_path+'fetch-job-content',
        //             type: 'POST',
        //             dataType: 'json',
        //             // processData: false,
        //             // contentType: false,
        //             data: {
        //                 jid: $(obj).data('id'),
        //                 type: $(obj).data('type')
        //             },
        //             success: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //                 if (resp.success) {

        //                     $('.job-content').html(resp.content);
        //                     $(obj).addClass('active')
        //                 }
        //             },
        //             error: function (resp) {
        //                 console.log(resp);
        //                 ajaxindicatorstop();
        //             }
        //         });
        //     }
        // }
    </script>
@stop

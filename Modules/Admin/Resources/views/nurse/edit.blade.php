@extends('admin::layouts.master')

@section('content')

    <!-- page content -->

    <div class="ss-admin-wr-prfil-mn-dv">
        <div class="row">
            <div class="col-lg-12">
                <div class="ss-admn-wrkr-pfl-tabs">
                    <ul class="nav nav-tabs md-tabs" id="myTabEx" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active show" id="home-tab-ex" data-toggle="tab" href="#home-ex" role="tab"
                                aria-controls="home-ex" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab-ex" data-toggle="tab" href="#profile-ex" role="tab"
                                aria-controls="profile-ex" aria-selected="false">Professional Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab-ex" data-toggle="tab" href="#contact-ex" role="tab"
                                aria-controls="contact-ex" aria-selected="false">Vaccination & Immunization</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="refrence-tab-ex" data-toggle="tab" href="#refrence-ex" role="tab"
                                aria-controls="contact-ex" aria-selected="false">References</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="cert-tab-ex" data-toggle="tab" href="#cert-ex" role="tab"
                                aria-controls="contact-ex" aria-selected="false">Certifications</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content pt-5" id="myTabContentEx">

                    <div class="tab-pane fade active show" id="home-ex" role="tabpanel" aria-labelledby="home-tab-ex">
                        <div class="ss-all-Profile-mn-dv">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="ss-my-profil-div">
                                        <h2>Profile</h2>
                                        <div class="ss-my-profil-img-div">
                                            <img src="{{ URL::asset('backend/assets/images/profile-pic-big.png') }}">
                                            <h4>James Bond</h4>
                                        </div>
                                        <div class="ss-profil-complet-div">
                                            <ul>
                                                <li><img src="{{ URL::asset('backend/assets/images/progress.png') }}"></li>
                                                <li>
                                                    <h6>Profile Incomplete</h6>
                                                    <a href="#">mohan07@gmail.com</a>
                                                    <a href="#">+1 (124) 545-4554</a>
                                                    <p>GWW234065 </p>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="ss-my-presnl-btn-mn">

                                            <div class="ss-profile-btn-div" onclick="myFunction(event)" id="navList1">
                                                <button class="active work-jorn-anlst-btn"> Work Journey Analysis </button>
                                                <button class="apply-job-btn">Applied Jobs </button>
                                                <button class="offer-job-bn"> Offered Jobs </button>
                                                <button class="certifi-btn-click"> Hired Jobs</button>
                                                <button class="certifi-btn-click"> Past Jobs</button>


                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-7">

                                    <!--------------graph div----->
                                    <div class="ss-wrkr-prfil-grph-dv">
                                        <img src="{{ URL::asset('backend/assets/images/worker-profile-graph.png') }}" />
                                    </div>

                                    <!---------- Applied Jobs------------------>
                                    <div class="ss-admn-aply-jb-mn-dv">
                                        <h5>Applied Jobs</h5>

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-left">ID</th>
                                                    <th scope="col">Jobs</th>
                                                    <th scope="col">Locationt</th>
                                                    <th scope="col">Rate<span>($/hr)</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span> Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><a href="#">GWW000088</a></td>
                                                    <td>
                                                        <div class="ss-adm-wrk-tbl-jb">
                                                            <p>OR - Operating Room</p>
                                                            <span>RN <span class="ss-adm-wrkr-tblmrg">|</span>
                                                                Travel</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p>Washington, DC</p>
                                                    </td>
                                                    <td>
                                                        <p class="ss-ad-wr-tbl-jb-rt">$26</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile-ex" role="tabpanel" aria-labelledby="profile-tab-ex">
                        <div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="ss-my-profil-div ss-prof-prfil-dv">
                                        <h2>Professional Information</h2>
                                        <div class="ss-my-profil-img-div">
                                            <img src="{{ URL::asset('backend/assets/images/profile-pic-big.png') }}">
                                            <h4>James Bond</h4>
                                        </div>
                                        <div class="ss-profil-complet-div">
                                            <ul>
                                                <li><img src="{{ URL::asset('backend/assets/images/progress.png') }}"></li>
                                                <li>
                                                    <h6>Profile Incomplete</h6>
                                                    <a href="#">mohan07@gmail.com</a>
                                                    <a href="#">+1 (124) 545-4554</a>
                                                    <p>GWW234065 </p>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="ss-my-presnl-btn-mn">

                                            <div class="ss-profile-btn-div" onclick="myFunction(event)" id="navList1">
                                                <button class="active per-basicinfo-btn"> Basic Info </button>
                                                <button class="per-info-req-btn">Info required to submit </button>
                                                <button class="per-urgen-btn"> Urgency </button>
                                                <button class="per-float-btn"> Float Requirements</button>
                                                <button class="per-patient-btn"> Patient Ratio</button>
                                                <button class="per-interview-btn"> Interview Dates </button>
                                                <button class="per-dign-b-btn"> Sign-On Bonus</button>
                                                <button class="per-overtm-btn"> Overtime</button>


                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-7">
                                    <!---------------Professional Information------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-prfs-info-open">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><span><img
                                                            src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}"></span>Basic
                                                    Info</p>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Title</label>
                                                <input type="text" name="text"
                                                    placeholder="What should they call you?">
                                            </div>
                                            <div class="ss-form-group">
                                                <label>Profession</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">What Kind of Professional are you?</option>
                                                    <option value="saab">RN</option>
                                                    <option value="mercedes">Allied Health Professional</option>
                                                    <option value="audi">Therapy</option>
                                                    <option value="audi">LPN/LVN</option>
                                                    <option value="audi">Nurse Practitioner</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                                <!--         <label>Specialty and Experience</label>
                        <div class="ss-speilty-exprnc-add-list">
                          <ul>
                            <li>ICU</li>
                            <li>4 Years</li>
                            <li><button><img src="img/delete-img.png')}}"></button></li>
                          </ul>
                          <ul>
                            <li>L&amp;D</li>
                            <li>2 Years</li>
                            <li><button><img src="img/delete-img.png')}}"></button></li>
                          </ul>
                        </div> -->
                                                <ul>
                                                    <li>
                                                        <select name="cars" id="cars">
                                                            <option value="volvo">Select Specialty</option>
                                                            <option value="saab">RN</option>
                                                            <option value="mercedes">Allied Health Professional</option>
                                                            <option value="audi">Therapy</option>
                                                            <option value="audi">LPN/LVN</option>
                                                            <option value="audi">Nurse Practitioner</option>
                                                        </select>
                                                        <input type="text" name="text"
                                                            placeholder="Enter Experience in years">
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><i class="fa fa-plus"
                                                                aria-hidden="true"></i></div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="ss-professional-license-div">
                                                <h6>Professional License</h6>

                                                <div class="ss-form-group">
                                                    <label>License Type</label>
                                                    <select name="cars" id="cars">
                                                        <option value="volvo">What type of nursing license do you hold?
                                                        </option>
                                                        <option value="saab">RN</option>
                                                        <option value="mercedes">PN</option>
                                                        <option value="audi">APRN-CNP</option>
                                                    </select>
                                                </div>


                                                <div class="ss-form-group">
                                                    <label>License State</label>
                                                    <select name="cars" id="cars">
                                                        <option value="volvo">Where are you licensed?</option>
                                                        <option value="saab">Georgia(GA)</option>
                                                        <option value="mercedes">California(CA)</option>
                                                        <option value="audi">Kentucky(KY)</option>

                                                    </select>
                                                </div>

                                                <div class="ss-form-group">
                                                    <label>License Number</label>
                                                    <input id="number" type="number"
                                                        placeholder="What is your license number?">
                                                </div>

                                                <div class="ss-form-group">
                                                    <label>Expiration Date</label>
                                                    <input type="date" id="birthday" name="birthday"
                                                        placeholder="Month Day, Year">
                                                </div>
                                            </div>

                                            <div class="ss-ss-licens-check">
                                                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                                                <label>This is a compact license</label>

                                            </div>
                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>


                                    <!-----------------Info required to submit-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-infosub-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">Info
                                                    required to submit</p>

                                            </div>

                                            <div class="ss-form-group">
                                                <label>Diploma</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Did you really graduate?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="mercedes">No</option>

                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>drivers license</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Are you really allowed to drive?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="mercedes">No</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Recent Experience</label>
                                                <input type="text" name="text"
                                                    placeholder="Where did you work recently">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Worked at Facility Before</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Have you been on staff here before?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="mercedes">No</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>SS# or SS Card</label>
                                                <input type="text" name="text"
                                                    placeholder="Yes we need your SS# to submit you">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Eligible to work in the US</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Does Congress allow you to work here?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="mercedes">No</option>
                                                </select>
                                            </div>


                                            <div class="ss-prsnl-skill-chk-sec">
                                                <h6>Skills Checklist</h6>
                                                <div class="ss-form-group">
                                                    <label>Completion Date</label>
                                                    <input type="date" id="birthday" name="birthday">
                                                </div>

                                                <div class="ss-file-choose-sec">
                                                    <div class="ss-form-group fileUploadInput">
                                                        <label>Upload File</label>
                                                        <input type="file">
                                                        <button>Choose File</button>
                                                    </div>
                                                </div>




                                            </div>

                                            <div class="ss-add-more-sec">
                                                <a href="#">Add More</a>
                                            </div>

                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>


                                    <!-----------------Urgency-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-urgncy-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">Urgency
                                                </p>

                                            </div>

                                            <div class="ss-form-group">
                                                <label>Urgency</label>
                                                <input type="text" name="text"
                                                    placeholder="How quickly can you be ready to submit?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label># of Positions Available</label>
                                                <input type="text" name="text"
                                                    placeholder="You have applied to # jobs?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>MSP</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Any MSPs you prefer to avoid?</option>
                                                    <option value="saab">List of managed service provider</option>


                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>VMS</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Who's your favorite VMS?</option>
                                                    <option value="saab">List of Vendor Management System</option>

                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label># of Submissions in VMS</label>
                                                <input type="text" name="text" placeholder="">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Block scheduling</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Do you want block scheduling?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="mercedes">No</option>
                                                </select>
                                            </div>


                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>


                                    <!-----------------Float Requirements-------------->
                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-flot-req-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">
                                                    Float Requirements</p>

                                            </div>


                                            <div class="ss-form-group">
                                                <label>Float Requirement </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Are you willing float to?</option>
                                                    <option value="saab">Yes</option>
                                                    <option value="saab">No</option>

                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Facility Shift Cancellation Policy</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">What terms do you prefer?</option>
                                                    <option value="saab">1 Shift/wk</option>
                                                    <option value="saab">1 Shift/2wk</option>
                                                    <option value="saab">3 Shift/13wk</option>
                                                    <option value="saab">No Shift Cancelations</option>
                                                    <option value="saab">No Preference</option>

                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Contract Termination Policy</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">What terms do you prefer?</option>
                                                    <option value="saab">2 week notice</option>
                                                    <option value="saab">30 days notice</option>
                                                    <option value="saab">same terms as facility</option>
                                                    <option value="saab">No Preference</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Traveler Distance From Facility</label>
                                                    <input type="text" name="text"
                                                        placeholder="Where does the IRS think you live?">
                                                </div>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Facility</label>
                                                <input type="text" name="text"
                                                    placeholder="What facilities have you worked at?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Facility's Parent System</label>
                                                <input type="text" name="text"
                                                    placeholder="What facilities would you like to work at?">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Facility Average Rating</label>
                                                <input type="text" name="text"
                                                    placeholder="Your average rating by your facilities">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Recruiter Average Rating</label>
                                                <input type="text" name="text"
                                                    placeholder="Your average rating by your recruiters">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Facility Average Rating</label>
                                                <input type="text" name="text"
                                                    placeholder="Your average rating by your organizations">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Organization Average Rating</label>
                                                <input type="text" name="text"
                                                    placeholder="Your average rating by your organizations">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Clinical Setting</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">What setting do you prefer?</option>
                                                    <option value="saab">School</option>
                                                    <option value="mercedes">Corrections</option>
                                                    <option value="mercedes">Clinic</option>
                                                    <option value="saab">Home Health</option>
                                                    <option value="mercedes">Hospital</option>

                                                </select>
                                            </div>


                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-----------------Patient Ratio-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-pnt-rtio-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">
                                                    Patient Ratio</p>

                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Patient Ratio</label>
                                                    <input type="text" name="text"
                                                        placeholder="How many patients can you handle?">
                                                </div>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>EMR </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">What EMRs have you used?</option>
                                                    <option value="saab">EPIC</option>
                                                    <option value="saab">Cerner</option>

                                                </select>
                                            </div>



                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Unit</label>
                                                    <input type="text" name="text" placeholder="Fav Unit?">
                                                </div>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Department</label>
                                                <input type="text" name="text" placeholder="Fav Department?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Bed Size</label>
                                                <input type="text" name="text"
                                                    placeholder="king or california king ?">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Trauma Level</label>
                                                <input type="text" name="text" placeholder="Ideal trauma level?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Scrub Color</label>
                                                <input type="text" name="text" placeholder="Fav Scrub Brand?">
                                            </div>



                                            <div class="ss-form-group">
                                                <label>Facility City</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">cities you'd like to work?</option>
                                                    <option value="saab">List of Cities</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Facility State Code</label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">States you'd like to work?</option>
                                                    <option value="saab">List of State</option>
                                                </select>
                                            </div>


                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-----------------Interview Dates-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-int-dat-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">
                                                    Interview Dates</p>

                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>interview Date</label>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal"><input type="date"
                                                            id="birthday" name="birthday"></a>
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group ss-date-check-div">
                                                    <ul>
                                                        <li> <label>Start Date</label></li>
                                                        <li><input type="checkbox" id="vehicle1" name="vehicle1"
                                                                value="Bike">
                                                            <label for="vehicle1"> As soon As Posible</label>
                                                        </li>
                                                    </ul>
                                                    <input type="date" id="birthday" name="birthday">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>RTO</label>
                                                    <input id="appt-time" type="time" name="appt-time" value="13:30"
                                                        placeholder="Any time off?">
                                                </div>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Shift Time of Day </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">Shift Time of Day</option>
                                                    <option value="saab">EPIC</option>
                                                    <option value="saab">Cerner</option>

                                                </select>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Hours/Week</label>
                                                    <input type="text" name="text"
                                                        placeholder="Ideal Hours per week?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Guaranteed Hours</label>
                                                    <input type="text" name="text"
                                                        placeholder="Open to jobs with no guaranteed hours?">
                                                </div>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Hours/Shift</label>
                                                <input type="text" name="text" placeholder="How many weeks?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Weeks/Assignment</label>
                                                <input type="text" name="text" placeholder="How many weeks?">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Shifts/Week</label>
                                                <input type="text" name="text" placeholder="Ideal shifts per week">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Referral Bonus</label>
                                                <input type="text" name="text"
                                                    placeholder="# of people you have referred">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Referral Bonus</label>
                                                <input type="text" name="text"
                                                    placeholder="# of people you have referred">
                                            </div>




                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-----------------Sign-On Bonus-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-sin-bon-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">Sign-On
                                                    Bonus</p>

                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Sign-On Bonus</label>
                                                    <input type="text" name="text"
                                                        placeholder="What kind of bonus do you expect?">
                                                </div>
                                            </div>


                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Completion Bonus</label>
                                                    <input type="text" name="text"
                                                        placeholder="What kind of bonus do you deserve?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Extension Bonus</label>
                                                    <input type="text" name="text"
                                                        placeholder="What are you comparing this too?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Other Bonus</label>
                                                    <input type="text" name="text"
                                                        placeholder="Other bonuses you want?">
                                                </div>
                                            </div>



                                            <div class="ss-form-group">
                                                <label>401K </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">How much do you want this?</option>
                                                    <option value="saab">I have to have</option>
                                                    <option value="saab">I prefer to have</option>
                                                    <option value="saab">I don’t Care</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Health Insurance </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">How much do you want this?</option>
                                                    <option value="saab">I have to have</option>
                                                    <option value="saab">I prefer to have</option>
                                                    <option value="saab">I don’t Care</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Dental </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">How much do you want this?</option>
                                                    <option value="saab">I have to have</option>
                                                    <option value="saab">I prefer to have</option>
                                                    <option value="saab">I don’t Care</option>
                                                </select>
                                            </div>


                                            <div class="ss-form-group">
                                                <label>Vision </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">How much do you want this?</option>
                                                    <option value="saab">I have to have</option>
                                                    <option value="saab">I prefer to have</option>
                                                    <option value="saab">I don’t Care</option>
                                                </select>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Actual Hourly rate</label>
                                                    <input type="text" name="text"
                                                        placeholder="What range is fair?">
                                                </div>
                                            </div>



                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>


                                    <!-----------------Overtime-------------->

                                    <div class="ss-pers-info-form-mn-dv ss-wrkr-per-overtime-ope">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">
                                                    Overtime</p>

                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Overtime </label>
                                                    <select name="cars" id="cars">
                                                        <option value="volvo">Would you work more for a higher rate?
                                                        </option>
                                                        <option value="saab">yes</option>
                                                        <option value="saab">No</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Holiday</label>
                                                    <input type="date" id="birthday" name="birthday">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Extension Bonus</label>
                                                    <select name="cars" id="cars">
                                                        <option value="volvo">Would you work more for a higher rate?
                                                        </option>
                                                        <option value="saab">yes</option>
                                                        <option value="saab">No</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>On Call</label>
                                                    <input type="text" name="text" placeholder="Will you do call?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Call Back</label>
                                                    <input type="text" name="text"
                                                        placeholder="Is this rate reasonable?">
                                                </div>
                                            </div>


                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Orientation Rate</label>
                                                    <input type="text" name="text" placeholder="-">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Weekly non-taxable amount</label>
                                                    <input type="text" name="text"
                                                        placeholder="Are you going to duplicate expenses?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Organization Weekly Amount</label>
                                                    <input type="text" name="text"
                                                        placeholder="What range is reasonable?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Goodwork Weekly Amount</label>
                                                    <input type="text" name="text"
                                                        placeholder="you only have (count down time) left before your rate drops from 5% to 2%">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Total Organization Amount</label>
                                                    <input type="text" name="text" placeholder="-">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Total Goodwork Amount</label>
                                                    <input type="text" name="text"
                                                        placeholder="How would you spend those extra funds?">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Total Contract Amount</label>
                                                    <input type="text" name="text" placeholder="--">
                                                </div>
                                            </div>

                                            <div class="ss-form-live-location">
                                                <div class="ss-form-group">
                                                    <label>Goodwork Number</label>
                                                    <input type="text" name="text" placeholder="---">
                                                </div>
                                            </div>





                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save &amp; Next
                                                </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!---------Vaccination & Immunization------------->

                    <div class="tab-pane fade" id="contact-ex" role="tabpanel" aria-labelledby="contact-tab-ex">
                        <div class="ss-wrk-vacenimz-mn-dv">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="ss-my-profil-div ss-prof-prfil-dv">
                                        <h2>Vaccination & Immunization</h2>
                                        <div class="ss-my-profil-img-div">
                                            <img src="{{ URL::asset('backend/assets/images/profile-pic-big.png') }}">
                                            <h4>James Bond</h4>
                                        </div>
                                        <div class="ss-profil-complet-div">
                                            <ul>
                                                <li><img src="{{ URL::asset('backend/assets/images/progress.png') }}">
                                                </li>
                                                <li>
                                                    <h6>Profile Incomplete</h6>
                                                    <a href="#">mohan07@gmail.com</a>
                                                    <a href="#">+1 (124) 545-4554</a>
                                                    <p>GWW234065 </p>
                                                </li>
                                            </ul>
                                        </div>



                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="ss-vaccin-immnz-mn-dv-view">
                                        <div class="ss-pers-info-form-mn-dv ss-vaccnt-immu-form-mn-dv"
                                            style="display: block;">
                                            <form>
                                                <div class="ss-persnl-frm-hed">
                                                    <p><img
                                                            src="{{ URL::asset('backend/assets/images/my-per-con-user.png') }}">
                                                        Vaccinations &amp; Immunizations</p>

                                                </div>


                                                <div class="ss-form-group">
                                                    <div class="ss-counter-immu-plus-div">
                                                        <ul>
                                                            <li>
                                                                <input type="text" name="Enter Vacc. or Immu. name"
                                                                    placeholder="Enter Vacc. or Immu. name">
                                                            </li>
                                                            <li>
                                                                <div class="ss-prsn-frm-plu-div"><a href=""><i
                                                                            class="fa fa-plus" aria-hidden="true"></i></a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="ss-file-choose-sec">
                                                    <div class="ss-form-group fileUploadInput">
                                                        <label>Upload File</label>
                                                        <input type="file">
                                                        <button>Choose File</button>
                                                    </div>
                                                </div>



                                                <div class="ss-form-group">
                                                    <label>COVID Vaccines Expiration Date</label>
                                                    <input type="date" id="birthday" name="birthday">
                                                </div>



                                                <div class="ss-form-group">
                                                    <label>Flu Vaccines </label>
                                                    <select name="cars" id="cars">
                                                        <option value="volvo">Did you get the Flu Vaccines?</option>
                                                        <option value="saab">yes</option>
                                                        <option value="saab">No</option>
                                                    </select>
                                                </div>



                                                <div class="ss-prsn-form-btn-sec">
                                                    <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                    <button type="text" class="ss-prsnl-save-btn"> Save </button>
                                                </div>

                                                <div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!---------References------------->
                    <div class="tab-pane fade" id="refrence-ex" role="tabpanel" aria-labelledby="refrence-tab-ex">
                        <div class="ss-wrk-references-mn-dv">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="ss-my-profil-div ss-prof-prfil-dv">
                                        <h2>References</h2>
                                        <div class="ss-my-profil-img-div">
                                            <img src="{{ URL::asset('backend/assets/images/profile-pic-big.png') }}">
                                            <h4>James Bond</h4>
                                        </div>
                                        <div class="ss-profil-complet-div">
                                            <ul>
                                                <li><img src="{{ URL::asset('backend/assets/images/progress.png') }}">
                                                </li>
                                                <li>
                                                    <h6>Profile Incomplete</h6>
                                                    <a href="#">mohan07@gmail.com</a>
                                                    <a href="#">+1 (124) 545-4554</a>
                                                    <p>GWW234065 </p>
                                                </li>
                                            </ul>
                                        </div>



                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="ss-pers-info-form-mn-dv ss-profl-refrnc-form-mn-dv"
                                        style="display: block;">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per--con-refren.png') }}">
                                                    References</p>

                                            </div>


                                            <div class="ss-form-group">
                                                <label>number of references </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">0</option>
                                                    <option value="saab">1</option>
                                                    <option value="saab">2</option>
                                                    <option value="saab">3</option>
                                                    <option value="saab">4</option>
                                                    <option value="saab">5</option>
                                                    <option value="saab">6</option>
                                                    <option value="saab">7</option>
                                                    <option value="saab">8</option>
                                                    <option value="saab">9</option>
                                                </select>
                                            </div>

                                            <div class="ss-refre-deta-hed">
                                                <h6>Details of 1st Reference</h6>
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Name </label>
                                                <input type="text" name="text" placeholder="Enter Referred Name">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Phone </label>
                                                <input type="tel" id="phone" name="phone"
                                                    pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
                                                    placeholder="Enter Referred Phone">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Email </label>
                                                <input type="email" id="email" name="email"
                                                    placeholder="Enter Referred Email">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>Date referred </label>
                                                <input type="date" id="birthday" name="birthday">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>min title of reference </label>
                                                <input type="text" name="text" placeholder="What was their title?">
                                            </div>

                                            <div class="ss-form-group">
                                                <label>recency of reference </label>
                                                <input type="text" name="text" placeholder="Enter Referred Name">
                                            </div>


                                            <div class="ss-form-group">
                                                <label>recency of reference </label>
                                                <select name="cars" id="cars">
                                                    <option value="volvo">0</option>
                                                    <option value="saab">1</option>
                                                    <option value="saab">2</option>
                                                    <option value="saab">3</option>
                                                    <option value="saab">4</option>
                                                    <option value="saab">5</option>
                                                    <option value="saab">6</option>
                                                    <option value="saab">7</option>
                                                    <option value="saab">8</option>
                                                    <option value="saab">9</option>
                                                </select>
                                            </div>
                                            <div class="ss-file-choose-sec">
                                                <div class="ss-form-group fileUploadInput">
                                                    <label>Upload Doc</label>
                                                    <input type="file">
                                                    <button>Choose File</button>
                                                </div>
                                            </div>

                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save </button>
                                            </div>

                                            <div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!---------Certifications------------->
                    <div class="tab-pane fade" id="cert-ex" role="tabpanel" aria-labelledby="cert-tab-ex">
                        <div class="ss-wrk-certific-mn-dv">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="ss-my-profil-div ss-prof-prfil-dv">
                                        <h2>Certifications</h2>
                                        <div class="ss-my-profil-img-div">
                                            <img src="{{ URL::asset('backend/assets/images/profile-pic-big.png') }}">
                                            <h4>James Bond</h4>
                                        </div>
                                        <div class="ss-profil-complet-div">
                                            <ul>
                                                <li><img src="{{ URL::asset('backend/assets/images/progress.png') }}">
                                                </li>
                                                <li>
                                                    <h6>Profile Incomplete</h6>
                                                    <a href="#">mohan07@gmail.com</a>
                                                    <a href="#">+1 (124) 545-4554</a>
                                                    <p>GWW234065 </p>
                                                </li>
                                            </ul>
                                        </div>



                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="ss-pers-info-form-mn-dv ss-profl-certif-form-mn-dv">
                                        <form>
                                            <div class="ss-persnl-frm-hed">
                                                <p><img
                                                        src="{{ URL::asset('backend/assets/images/my-per--con-key.png') }}">
                                                    Certifications</p>

                                            </div>

                                            <div class="ss-counter-immu-plus-div">
                                                <ul>
                                                    <li>
                                                        <input type="text" name="Enter Vacc. or Immu. name"
                                                            placeholder="Enter Certification Name">
                                                    </li>
                                                    <li>
                                                        <div class="ss-prsn-frm-plu-div"><a href=""><i
                                                                    class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="ss-file-choose-sec">
                                                <div class="ss-form-group fileUploadInput">
                                                    <label>BLS Certificate</label>
                                                    <input type="file">
                                                    <button>Choose File</button>
                                                </div>
                                            </div>



                                            <div class="ss-file-choose-sec">
                                                <div class="ss-form-group fileUploadInput">
                                                    <label>ACLS Certificate</label>
                                                    <input type="file">
                                                    <button>Choose File</button>
                                                </div>
                                            </div>




                                            <div class="ss-file-choose-sec">
                                                <div class="ss-form-group fileUploadInput">
                                                    <label>PALS Certificate</label>
                                                    <input type="file">
                                                    <button>Choose File</button>
                                                </div>
                                            </div>




                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn"> Skip </button>
                                                <button type="text" class="ss-prsnl-save-btn"> Save </button>
                                            </div>

                                            <div>
                                            </div>
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



    <!-- /page content -->



    <script>
        // Author: Ali Soueidan
        // Author URI: https//: www.alisoueidan.com

        let content = "🦗";
    </script>




@stop

@section('js')
    @php
        $specialty = explode(',', $model->specialty);
        $experience = explode(',', $model->experience);
        $vaccinations = explode(',', $model->vaccinations);
        $certificate = explode(',', $model->certificate);

        $specialty = [];
        $experience = [];
        $vaccinations = [];
        $certificate = [];
        if (!empty($model->specialty)) {
            $specialty = explode(',', $model->specialty);
        }
        if (!empty($model->experience)) {
            $experience = explode(',', $model->experience);
        }
        if (!empty($model->vaccinations)) {
            $vaccinations = explode(',', $model->vaccinations);
        }
        if (!empty($model->certificate)) {
            $certificate = explode(',', $model->certificate);
        }
    @endphp
    <script>
        var speciality = {};
        var vac_content = [];
        var cer_content = [];

        @if (count($specialty))
            @for ($i = 0; $i < count($specialty); $i++)
                speciality['{{ $specialty[$i] }}'] = '{{ $experience[$i] }}';
            @endfor
        @endif
        console.log(speciality);

        @if (count($vaccinations))
            @for ($i = 0; $i < count($vaccinations); $i++)
                vac_content.push('{{ $vaccinations[$i] }}');
            @endfor
        @endif

        @if (count($certificate))
            @for ($i = 0; $i < count($certificate); $i++)
                cer_content.push('{{ $certificate[$i] }}');
            @endfor
        @endif

        var dynamic_elements = {
            vac: {
                id: '#vaccination',
                name: 'Vaccination',
                listing_class: '.vaccination-content',
                items: vac_content
            },
            cer: {
                id: '#certificate',
                name: 'Certificate',
                listing_class: '.certifications-content',
                items: cer_content
            }
        }

        function add_speciality(obj) {
            if (!$('#speciality').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the speciality please.',
                    time: 3
                });
            } else if (!$('#experience').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Enter total year of experience.',
                    time: 3
                });
            } else {
                if (!speciality.hasOwnProperty($('#speciality').val())) {
                    speciality[$('#speciality').val()] = $('#experience').val();
                    $('#experience').val('');
                    list_specialities();
                }
                console.log(speciality);
            }
        }

        function remove_speciality(obj) {
            if (speciality.hasOwnProperty($(obj).data('key'))) {
                delete speciality[$(obj).data('key')];
                // $(obj).parent().parent().parent().remove();
                list_specialities();
                console.log(speciality);
            }
        }

        function list_specialities() {
            // $('.speciality-content').empty();
            var str = '';
            var i = 1;
            for (const key in speciality) {
                if (speciality.hasOwnProperty(key)) {
                    const value = speciality[key];
                    str += '<div class="item form-group">';
                    str += '<label class="col-form-label col-md-4 col-sm-4 label-align"></label>';
                    str += '<div class="col-md-1 col-sm-1 ">';
                    str += '<label class="col-form-label label-align"> ' + (i++) + '.</label>';
                    str += '</div>';
                    str += '<div class="col-md-2 col-sm-2">';
                    str += '<label class="col-form-label label-align"> ' + key + '</label>';
                    str += '</div>';
                    str += '<div class="col-md-2 col-sm-2">';
                    str += '<label class="col-form-label label-align">' + value + ' </label>';
                    str += '</div>';
                    str += '<div class="col-md-2 col-sm-1">';
                    str += '<label class="col-form-label label-align" title="delete"><button type="button" data-key="' +
                        key + '" onclick="remove_speciality(this)"><i class="fa fa-trash"></i></button></label>';
                    str += '</div>';
                    str += '</div>';

                }
            }
            $('.speciality-content').html(str);
        }

        function get_speciality(obj) {
            speciality = {};
            $('.speciality-content').empty();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: full_path + 'get-speciality',
                type: 'POST',
                dataType: 'json',
                // processData: false,
                // contentType: false,
                data: {
                    kid: $(obj).find('option:selected').data('id')
                },
                success: function(resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                    if (resp.success) {
                        $('#speciality').html(resp.content);
                    }
                },
                error: function(resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                }
            });

        }

        var vaccination = [];

        function add_vaccination() {
            if (!$('#vaccination').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the Vaccination please.',
                    time: 3
                });
            } else {
                if (!vaccination.includes($('#vaccination').val())) {
                    vaccination.push($('#vaccination').val());
                    list_vaccination();
                }
                $('#vaccination').val('');
                console.log(vaccination);
            }
        }

        function remove_vaccination(obj) {
            if (vaccination.includes($(obj).data('key'))) {
                const elementToRemove = $(obj).data('key');
                const newArray = vaccination.filter(item => item !== elementToRemove);
                vaccination = newArray;
                // $(obj).parent().parent().parent().remove();
                list_vaccination();
                console.log(vaccination);
            }
        }


        function list_vaccination() {
            if (vaccination.length) {
                str = '';
                vaccination.forEach(function(value, index) {
                    str += '<div class="item form-group">';
                    str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
                    str += '<div class="col-md-1 col-sm-1 ">';
                    str += '<label class="col-form-label label-align"> ' + (index + 1) + '.</label>';
                    str += '</div>';
                    str += '<div class="col-md-4 col-sm-4">';
                    str += '<label class="col-form-label label-align"> ' + value + '</label>';
                    str += '</div>';
                    str += '<div class="col-md-2 col-sm-1">';
                    str +=
                        '<label class="col-form-label label-align" title="delete"><button type="button" data-key="' +
                        value + '" onclick="remove_vaccination(this)"><i class="fa fa-trash"></i></button></label>';
                    str += '</div>';
                    str += '</div>';
                });
                $('.vaccination-content').html(str);
            }
        }


        function add_element(obj) {
            const type = $(obj).data('type');
            if (dynamic_elements.hasOwnProperty(type)) {
                let element, id, value, name;
                element = dynamic_elements[type];
                id = element.id;
                name = element.name;
                value = $(id).val();

                if (!value) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i> Select the ' + name + ' please.',
                        time: 3
                    });
                } else {
                    if (!element.items.includes(value)) {
                        element.items.push($(id).val());
                        console.log(element.items);
                        list_elements(type);
                    }
                    $(id).val('');
                }
            }
        }

        function remove_element(obj) {
            const type = $(obj).data('type');
            if (dynamic_elements.hasOwnProperty(type)) {
                let element = dynamic_elements[type];

                if (element.items.includes($(obj).data('key'))) {
                    const elementToRemove = $(obj).data('key');
                    const newArray = element.items.filter(item => item !== elementToRemove);
                    element.items = newArray;
                    // $(obj).parent().parent().parent().remove();
                    list_elements(type);
                    console.log(element.items);
                }
            }

        }

        function list_elements(type) {
            if (dynamic_elements.hasOwnProperty(type)) {
                const element = dynamic_elements[type];
                if (element.items.length) {
                    str = '';
                    element.items.forEach(function(value, index) {
                        str += '<div class="item form-group">';
                        str += '<label class="col-form-label col-md-3 col-sm-3 label-align"></label>';
                        str += '<div class="col-md-1 col-sm-1 ">';
                        str += '<label class="col-form-label label-align"> ' + (index + 1) + '.</label>';
                        str += '</div>';
                        str += '<div class="col-md-4 col-sm-4">';
                        str += '<label class="col-form-label label-align"> ' + value + '</label>';
                        str += '</div>';
                        str += '<div class="col-md-2 col-sm-1">';
                        str +=
                            '<label class="col-form-label label-align" title="delete"><button type="button" data-type="' +
                            type + '" data-key="' + value +
                            '" onclick="remove_element(this)"><i class="fa fa-trash"></i></button></label>';
                        str += '</div>';
                        str += '</div>';
                    });
                    $(element.listing_class).html(str);
                }
            }
        }

        $(document).ready(function() {
            list_specialities();
            // list_elements('vac');
            // list_elements('cer');

            $('.nurse-edit-profile').on('submit', function(event) {
                var form = $(this);
                event.preventDefault();
                form.find('.parsley-error').removeClass('parsley-error');
                form.find('.parsley-errors-list').remove();
                form.find('.parsley-custom-error-message').remove();
                ajaxindicatorstart();
                const url = form.attr('action');
                const specialities = Object.keys(speciality).join(',');
                const experiences = Object.values(speciality).join(',');
                // const vaccinations = dynamic_elements.vac.items.join(',');
                // const certificate = dynamic_elements.cer.items.join(',');
                var data = new FormData(form[0]);
                data.set('specialty', specialities);
                data.set('experience', experiences);
                // data.set('vaccinations', vaccinations);
                // data.set('certificate', certificate);
                console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function(resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if (resp.success) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> ' + resp.msg,
                                time: 3
                            });
                        }
                    },
                    error: function(resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        // $('#basic-info-form').parsley().destroy();
                        // Reset any previous errors
                        form.parsley().reset();

                        if (resp.responseJSON && resp.responseJSON.errors) {
                            var errors = resp.responseJSON.errors;

                            // Loop through the errors and add classes and error messages
                            $.each(errors, function(field, messages) {
                                // Find the input element for the field
                                var inputElement = form.find('[name="' + field + '"]');

                                // Add the parsley-error class to the input element
                                inputElement.addClass('parsley-error');

                                // Display the error messages near the input element
                                $.each(messages, function(index, message) {
                                    inputElement.after(
                                        '<ul class="parsley-errors-list filled"><li class="parsley-required">' +
                                        message + '</li></ul>');
                                });
                            });
                        }
                    }
                })
            });

        });
    </script>
    <script>
        // $('#skills').tagsinput();
        $(document).ready(function() {
            $('#skills').tagsinput();
        });
    </script>

    <script>
        /* Job references count */
        var old_references_count;
        old_references_count = $('.job-references input[name="old_name[]"]').length;
        console.log('Number: ' + old_references_count);
    </script>
    <!----------Applyed script----->
    <script>
        $(document).ready(function() {
            $(".work-jorn-anlst-btn").click(function() {
                $(".ss-wrkr-prfil-grph-dv").show();
                $(".ss-admn-aply-jb-mn-dv").hide();
            });

            $(".apply-job-btn").click(function() {
                $(".ss-admn-aply-jb-mn-dv").show();
                $(".ss-wrkr-prfil-grph-dv").hide();
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $(".per-basicinfo-btn").click(function() {
                $(".ss-wrkr-prfs-info-open").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-info-req-btn").click(function() {
                $(".ss-wrkr-per-infosub-ope").show();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-urgen-btn").click(function() {
                $(".ss-wrkr-per-urgncy-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });
            $(".per-float-btn").click(function() {
                $(".ss-wrkr-per-flot-req-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-patient-btn").click(function() {
                $(".ss-wrkr-per-pnt-rtio-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-interview-btn").click(function() {
                $(".ss-wrkr-per-int-dat-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-dign-b-btn").click(function() {
                $(".ss-wrkr-per-sin-bon-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
                $(".ss-wrkr-per-overtime-ope").hide();
            });

            $(".per-overtm-btn").click(function() {
                $(".ss-wrkr-per-overtime-ope").show();
                $(".ss-wrkr-per-infosub-ope").hide();
                $(".ss-wrkr-per-urgncy-ope").hide();
                $(".ss-wrkr-per-flot-req-ope").hide();
                $(".ss-wrkr-per-pnt-rtio-ope").hide();
                $(".ss-wrkr-per-int-dat-ope").hide();
                $(".ss-wrkr-per-sin-bon-ope").hide();
                $(".ss-wrkr-prfs-info-open").hide();
            });

        });
    </script>



    <script>
        function add_more_skills(obj) {
            let skill_parent = $('.skills-content')
            let str = '';
            str += '<div class="item form-group skills-item">';
            str += '<label class="col-form-label col-md-4 col-sm-4 label-align"> Completion Date </label>';
            str += '<div class="col-md-6 col-sm-6">';
            str += '<input type="date" class="form-control" name="completion_date[]" value="">';
            str += '</div>';
            str += '</div>';
            str += '<div class="item form-group skills-item">';
            str += '<label class="col-form-label col-md-4 col-sm-4 label-align"> Upload File </label>';
            str += '<div class="col-md-6 col-sm-6">';
            str += '<input type="file" class="form-control" name="skill[]" >';
            str += '</div>';
            str += '</div>';
            skill_parent.append(str);
        }
    </script>
@stop

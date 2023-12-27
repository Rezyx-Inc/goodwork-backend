@extends('employer::layouts.main')

@section('content')


<!-- nav WITH ONLY LOGOUT -->

<!-----header section------>
<div class="all">
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

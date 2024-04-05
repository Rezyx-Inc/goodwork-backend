@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('content')
    @php
        $user = auth()->guard('frontend')->user();
    @endphp
    <!--Main layout-->
    <main style="padding-top: 130px; padding-bottom: 100px;" class="ss-main-body-sec">
        <div class="container">
            <div class="ss-my-profile--basic-mn-sec">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-my-profil-div">
                            <h2>My <span class="ss-pink-color">Profile</span></h2>
                            <div class="ss-my-profil-img-div">
                                <img src="{{ URL::asset('frontend/img/account-img.png') }}"
                                    onerror="this.onerror=null;this.src='{{ USER_IMG }}';" id="preview"
                                    width="112px" height="112px" style="object-fit: cover;" />
                                <h4>James Bond</h4>
                                <p>GWW234065 </p>
                            </div>
                            <div class="ss-profil-complet-div">
                                <ul>
                                    <li><img src="{{ URL::asset('frontend/img/progress.png') }}" /></li>
                                    <li>
                                        <h6>Profile Incomplete</h6>
                                        <p>You're just a few percent away from revenue. Complete your profile and get 5%.
                                        </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="ss-my-presnl-btn-mn">

                                <div class="ss-my-prsnl-wrapper">
                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-1" checked />
                                        <label for="option-1" class="option option-1">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></li>
                                                <li>
                                                    <p>Profile settings</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-2">
                                        <label for="option-2" class="option option-2">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}" />
                                                </li>
                                                <li>
                                                    <p>Account settings</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-3">
                                        <label for="option-3" class="option option-3">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-refren.png') }}" />
                                                </li>
                                                <li>
                                                    <p>Bonus Transfers</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-4">
                                        <label for="option-4" class="option option-4">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-key.png') }}" /></li>
                                                <li>
                                                    <p>Support</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>


                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-5">
                                        <label for="option-5" class="option option-5">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-key.png') }}" /></li>
                                                <li>
                                                    <p>Disable account</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--------Professional Information form--------->
                    <div class="col-lg-7 bodyAll">
                        <div class="ss-pers-info-form-mn-dv">
                            {{-- <form method="post" action="{{route('')}}"> --}}
                              <div class="ss-persnl-frm-hed">
                                {{-- Basic Information Or Professional Information Or Document management --}}
                                <p id="information_type"><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Basic
                                    Information</p>
                                <div class="progress">
                                    <div id="progress" class="progress-bar" role="progressbar" style="width: 33%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="form-outer">
                            <form>
                                @csrf
                                
                                <!-- first form slide Basic Information -->
                                <div class="page slide-page">
                                    <div class="row justify-content-center">
                                        {{-- First Name --}}
                                        <div class="ss-form-group col-11">
                                            <label>First Name</label>
                                            <input type="text" name="text" placeholder="Please enter your first name">
                                        </div>
                                        {{-- Last Name --}}
                                        <div class="ss-form-group col-11">
                                            <label>Last Name</label>
                                            <input type="text" name="text" placeholder="Please enter your last name">
                                        </div>
                                        {{-- Phone Number --}}
                                        <div class="ss-form-group col-11">
                                            <label>Phone Number</label>
                                            <input type="text" name="text"
                                                placeholder="Please enter your phone number">
                                        </div>
                                        {{-- Address Information --}}
                                        <div class="ss-form-group col-11>
                                            <label>Address</label>
                                            <input type="text" name="text" placeholder="Please enter your address">
                                        </div>
                                        {{-- City Information --}}
                                        <div class="ss-form-group col-11">
                                            <label>City</label>
                                            <select name="job_city" id="job_city">
                                                <option value="">What City are you located in?</option>
                                            </select>
                                        </div>
                                        {{-- State Information --}}
                                        <div class="ss-form-group col-11">
                                            <label>State</label>
                                            <select name="job_state" id="job_state">
                                              <option value="">What State are you located in?</option>
                                              @foreach($states as $state)
                                              <option id="{{$state->id}}" value="{{$state->name}}">{{$state->name}}
                                              </option>
                                              @endforeach
                                          </select>
                                        </div>
                                        {{-- Zip Code Information --}}
                                        <div class="ss-form-group col-11">
                                            <label>Zip Code</label>
                                            <input type="text" name="text"
                                                placeholder="Please enter your Zip Code">
                                        </div>

                                        {{-- Skip && Save --}}
                                        <div class="ss-prsn-form-btn-sec col-11">
                                            {{-- <button type="text" class="ss-prsnl-skip-btn"> Skip </button> --}}
                                            <button type="text" class="ss-prsnl-save-btn firstNext"> Save & Next
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end first form slide Basic Information -->

                                <!-- second form slide Professional Information -->
                                <div class="page slide-page">
                                    <div class="row justify-content-center">
                                        {{-- Profession --}}
                                        <div class="ss-form-group col-11">
                                            <label>Profession</label>
                                            <select name="cars" id="cars">
                                                <option value="">What Kind of Professional are you?</option>
                                                @foreach($proffesions as $proffesion)
                                                <option value="{{$proffesion->full_name}}">{{$proffesion->full_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Specialty --}}
                                        <div class="ss-form-group ss-prsnl-frm-specialty col-11">
                                            <label>Specialty</label>
                                            <select name="cars" id="cars">
                                                <option value="">Select Specialty</option>
                                                @foreach($specialities as $specialty)
                                                <option value="{{$specialty->full_name}}">{{$specialty->full_name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Skip && Save --}}
                                        <div class="ss-prsn-form-btn-sec col-11">
                                            {{-- <button type="text" class="ss-prsnl-skip-btn"> Skip </button> --}}
                                            <button type="text" class="ss-prsnl-skip-btn prev-1"> Previous </button>
                                            <button type="text" class="ss-prsnl-save-btn next-1"> Save & Next </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end second form slide (Profession, Slide) -->

                                <!-- Third form slide Document management -->
                                <div class="page slide-page ">
                                    <div class="row justify-content-center">
                                        {{-- Upload Document --}}
                                        <div class="ss-form-group">
                                            <label>Upload Document</label>
                                            <input type="file" name="file" id="file" class="inputfile" />
                                            <label class="mt-2" for="file">Choose a file</label>
                                        </div>

                                        {{-- manage file table --}}
                                        <table style="font-size: 16px;" class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Document Name</th>
                                                    <th scope="col">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($documenst as $item) --}}
                                                {{-- add {{}} to teh varibales once i fix it from the mogoose db --}}
                                                <tr>

                                                    <td>$item->name</td>
                                                    <td><button type="button" class="delete" data-id="$item->id">Delete
                                                            Documenst</button>
                                                    </td>
                                                </tr>
                                                {{-- @endforeach --}}
                                            </tbody>
                                        </table>
                                        {{-- <div class="ss-nojob-dv-hed">
                <button type="submit" name="action" value="add" id="add_key" class="add">Add New Documents</button>
                <button type="submit" name="action" value="save" id="save_key" class="save d-none">Save changes</button>
            </div> --}}
                                        {{-- Skip && Save --}}
                                        <div class="ss-prsn-form-btn-sec">
                                            <button type="text" class="ss-prsnl-skip-btn prev-2"> Previous </button>
                                            <button type="text" class="ss-prsnl-save-btn next-2"> Save & Next </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- end Third form slide Document management -->
                                <div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
    </main>
@stop

@section('js')

    <script type="text/javascript">

$(document).ready(function () {
        $('#job_state').change(function () {
            const selectedState = $(this).find(':selected').attr('id');
            const CitySelect = $('#job_city');

            $.get(`/api/cities/${selectedState}`, function (data) {
                CitySelect.empty();
                CitySelect.append('<option value="">Select City</option>');
                $.each(data, function (index, city) {
                    CitySelect.append(new Option(city.name, city.name));
                });
            });
        });
    });



        const slidePage = document.querySelector(".slide-page");
        const nextBtnFirst = document.querySelector(".firstNext");
        console.log(nextBtnFirst);
        const prevBtnSec = document.querySelector(".prev-1");
        const nextBtnSec = document.querySelector(".next-1");
        const prevBtnThird = document.querySelector(".prev-2");
        const nextBtnThird = document.querySelector(".next-2");
        // const submitBtn = document.querySelector(".submit");
        // const saveDrftBtn = document.querySelector(".saveDrftBtn");
        const progress = document.getElementById("progress");
        // const progressCheck = document.querySelectorAll(".step .check");
        //const bullet = document.querySelectorAll(".step .bullet");
        // let current = 1;
        const infoType = document.getElementById("information_type");

        nextBtnFirst.addEventListener("click", function(event) {
            event.preventDefault();
            // if (validateFirst()) {
            slidePage.style.marginLeft = "-25%";
            //bullet[current - 1].classList.add("active");
            progress.style.width = "66%";
            // img need to be modified
            infoType.innerHTML = "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";
            // current += 1;
            // }
        });

        nextBtnSec.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-50%";
        //bullet[current - 1].classList.add("active");
        progress.style.width = "100%";
        // img need to be modified
        infoType.innerHTML = "<span><img src='{{ URL::asset('frontend/img/my-per--con-refren.png') }}' /></span>Document management";
        //progressText[current - 1].classList.add("active");
        //current += 1;
    });

    prevBtnSec.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "0%";
        // bullet[current - 2].classList.remove("active");
        progress.style.width = "25%";
        infoType.innerHTML = "<span><img src='{{ URL::asset('frontend/img/my-per--con-user.png') }}' /></span>Basic Information";
        // progressText[current - 2].classList.remove("active");
        // current -= 1;
    });
    prevBtnThird.addEventListener("click", function (event) {
        event.preventDefault();
        slidePage.style.marginLeft = "-25%";
        //bullet[current - 2].classList.remove("active");
        progress.style.width = "75%";
        infoType.innerHTML = "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";
        // progressText[current - 2].classList.remove("active");
        // current -= 1;
    });
    </script>

@stop

<style>
  
    .add {
        border: 1px solid #3D2C39 !important;
        color: #fff !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: fit-content;
        margin-top: 25px;
        background: #3D2C39 !important;
        margin-right: 6px;
    }

    .save {
        border: 1px solid #3D2C39 !important;
        color: #3D2C39 !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: fit-content;
        margin-top: 25px;
        background: transparent !important;
        margin-right: 6px;
    }

    .form-check-input[type=checkbox] {
        border-radius: .25rem;
        margin-top: .19em;
        margin-right: 6px
    }

    .form-check-input[type=checkbox]:focus:after {
        content: "";
        position: absolute;
        width: .875rem;
        height: .875rem;
        z-index: 1;
        display: block;
        border-radius: 0;
        background-color: #fff
    }

    .form-check-input[type=checkbox]:checked {
        background-image: none;
        background-color: black;
    }

    .form-check-input[type=checkbox]:checked:after {
        display: block;
        transform: rotate(45deg)
            /*!rtl:ignore*/
        ;
        width: .375rem;
        height: .8125rem;
        border: .125rem solid #fff;
        border-top: 0;
        border-left: 0
            /*!rtl:ignore*/
        ;
        margin-left: .25rem;
        margin-top: -1px;
        background-color: transparent
    }

    .form-check-input[type=checkbox]:checked:focus {
        background-color: black
    }

    .form-check-input[type=checkbox]:indeterminate {
        border-color: black
    }

    .form-check-input:checked {
        border-color: black;
    }

    .form-check-input:checked:focus {
        border-color: black;
    }

    .delete {
        border-radius: 57px;
        border: 1px solid var(--border, #111011);
        background: var(--light-bg-purple, #FFF8FD);
        width: 170px;
        height: 32px;
        color: var(--darkpurple, #3D2C39);
        text-align: center;
        font-family: Poppins;
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        text-transform: capitalize;
    }

    .ss-my-prsnl-wrapper .option {
        height: auto !important;
    }
</style>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

   

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
        width: fit-content;
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
        display: flex;

    flex-direction: column;

    align-items: center;
    justify-content: space-between;
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

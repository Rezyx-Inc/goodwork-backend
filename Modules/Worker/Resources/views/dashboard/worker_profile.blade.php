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
                                <form method="post" action="{{ route('update-worker-profile') }}">
                                    {{-- <form> --}}
                                    @csrf
                                    <!-- first form slide Basic Information -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- First Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>First Name</label>
                                                <input type="text" name="first_name"
                                                    placeholder="Please enter your first name">
                                            </div>
                                            {{-- Last Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Last Name</label>
                                                <input type="text" name="last_name"
                                                    placeholder="Please enter your last name">
                                            </div>
                                            {{-- Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Phone Number</label>
                                                <input type="text" name="mobile"
                                                    placeholder="Please enter your phone number">
                                            </div>
                                            {{-- Address Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Address</label>
                                                <input type="text" name="address"
                                                    placeholder="Please enter your address">
                                            </div>
                                            {{-- City Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>City</label>
                                                <select name="city" id="job_city">
                                                    <option value="">What City are you located in?</option>
                                                </select>
                                            </div>
                                            {{-- State Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>State</label>
                                                <select name="state" id="job_state">
                                                    <option value="">What State are you located in?</option>
                                                    @foreach ($states as $state)
                                                        <option id="{{ $state->id }}" value="{{ $state->name }}">
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Zip Code Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Zip Code</label>
                                                <input type="text" name="zip_code"
                                                    placeholder="Please enter your Zip Code">
                                            </div>

                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec col-11">
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
                                                <select name="profession" id="profession">
                                                    <option value="">What Kind of Professional are you?</option>
                                                    @foreach ($proffesions as $proffesion)
                                                        <option value="{{ $proffesion->full_name }}">
                                                            {{ $proffesion->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Specialty --}}
                                            <div class="ss-form-group  col-11">
                                                <label>Specialty</label>
                                                <select name="specialty" id="specialty">
                                                    <option value="">Select Specialty</option>
                                                    @foreach ($specialities as $specialty)
                                                        <option value="{{ $specialty->full_name }}">
                                                            {{ $specialty->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="ss-form-group col-11">
                                                <label>Terms</label>
                                                <select name="terms" id="term">
                                                    ';
                                                    @if (isset($allKeywords['Terms']))
                                                        @foreach ($allKeywords['Terms'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="ss-form-group col-11">
                                                <label>Type</label>
                                                <select name="type" id="type">
                                                    <option value="">Select Type</option>';
                                                    @if (isset($allKeywords['Type']))
                                                        @foreach ($allKeywords['Type'] as $value)
                                                            <option value="{{ $value->title }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            {{-- Block scheduling --}}
                                            <div class="ss-form-group row justify-content-center align-items-center col-11"
                                                style="margin-top:36px;">
                                                <label class="col-lg-6 col-sm-8 col-xs-8 col-md-8">Block scheduling</label>
                                                <input style="box-shadow:none; width: auto;"
                                                    class="col-lg-6 col-sm-2 col-xs-2 col-md-2" type="radio"
                                                    id="option1" name="block_scheduling" value="1"
                                                    autocompleted="" disabled>
                                            </div>
                                            {{-- end scheduling --}}
                                            {{-- Float requirements --}}
                                            <div class="ss-form-group col-11">
                                                <label>Float requirements</label>
                                                <select name="float_requirement" class="float_requirement mb-3"
                                                    id="float_requirement" value="">
                                                    <option value="">Select Float requirements</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                                            <span class="help-block-float_requirement"></span>
                                            {{-- end Float requirements --}}
                                            {{-- Facility Shift Cancellation Policy --}}
                                            <div class="ss-form-group  col-11">
                                                <label>Facility Shift Cancellation Policy</label>
                                                <select name="facility_shift_cancelation_policy"
                                                    class="facility_shift_cancelation_policy mb-3"
                                                    id="facility_shift_cancelation_policy" value="">
                                                    <option value="">Select Facility Shift Cancellation Policy
                                                    </option>';
                                                    @if (isset($allKeywords['AssignmentDuration']))
                                                        @foreach ($allKeywords['AssignmentDuration'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>;
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-facility_shift_cancelation_policy"></span>
                                            {{-- End Facility Shift Cancellation Policy --}}
                                            {{-- Contract Termination Policy --}}
                                            <div class="ss-form-group col-11">
                                                <label>Contract Termination Policy</label>
                                                <input type="text" id="contract_termination_policy"
                                                    name="contract_termination_policy"
                                                    placeholder="Enter Contract Termination Policy">
                                            </div>
                                            {{-- end Contract Termination Policy --}}
                                            {{-- Traveler Distance From Facility --}}
                                            <div class="ss-form-group col-11">
                                                <label>Traveler Distance From Facility</label>
                                                <input type="number" id="traveler_distance_from_facility"
                                                    name="distance_from_your_home"
                                                    placeholder="Enter Traveler Distance From Facility">
                                            </div>
                                            {{-- end Traveler Distance From Facility  --}}
                                            {{-- Clinical Setting --}}
                                            <div class="ss-form-group col-11">
                                                <label>Clinical Setting</label>
                                                <input type="text" id="clinical_setting"
                                                    name="clinical_setting_you_prefer"
                                                    placeholder="Enter clinical setting">
                                            </div>
                                            <span class="help-block-clinical_setting"></span>
                                            {{-- End Clinical Setting --}}
                                            {{-- Patient ratio --}}
                                            <div class="ss-form-group col-11">
                                                <label>Patient ratio</label>
                                                <input type="number" id="Patient_ratio" name="worker_patient_ratio"
                                                    placeholder="How many patients can you handle?">
                                            </div>
                                            <span class="help-block-Patient_ratio"></span>
                                            {{-- End Patient ratio --}}
                                            {{-- EMR --}}
                                            <div class="ss-form-group col-11">
                                                <label>EMR</label>
                                                <select name="worker_emr" class="emr mb-3" id="emr">
                                                    <option value="">Select EMR</option>';
                                                    @if (isset($allKeywords['EMR']))
                                                        @foreach ($allKeywords['EMR'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-emr"></span>
                                            {{-- End EMR --}}
                                            {{-- Unit --}}
                                            <div class="ss-form-group col-11">
                                                <label>Unit</label>
                                                <input id="Unit" type="text" name="worker_unit"
                                                    placeholder="Enter Unit">
                                            </div>
                                            <span class="help-block-Unit"></span>
                                            {{-- End Unit --}}
                                            {{-- Scrub Color --}}
                                            <div class="ss-form-group col-11">
                                                <label>Scrub Color</label>
                                                <input id="scrub_color" type="text" name="worker_scrub_color"
                                                    placeholder="Enter Scrub Color">
                                            </div>
                                            <span class="help-block-scrub_color"></span>
                                            {{-- End Scrub Color --}}
                                            {{-- RTO --}}
                                            <div class="ss-form-group col-11">
                                                <label>RTO</label>
                                                <input id="rto" type="text" name="rto"
                                                    placeholder="Enter RTO">
                                            </div>
                                            <span class="help-block-rto"></span>
                                            {{-- End RTO --}}
                                            {{-- Shift Time of Day --}}
                                            <div class="ss-form-group col-11">
                                                <label>Shift Time of Day</label>
                                                <select name="worker_shift_time_of_day" id="shift-of-day">
                                                    <option value="">Enter Shift Time of Day</option>';
                                                    @if (isset($allKeywords['PreferredShift']))
                                                        @foreach ($allKeywords['PreferredShift'] as $value)
                                                            <option value="{{ $value->id }}">{{ $value->title }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <span class="help-block-shift-of-day"></span>
                                            {{-- End Shift Time of Day --}}
                                            {{-- Hours/Week --}}
                                            <div class="ss-form-group col-11">
                                                <label>Hours/Week</label>
                                                <input id="hours_per_week" type="number" name="worker_hours_per_week"
                                                    placeholder="Enter Hours/Week">
                                            </div>
                                            <span class="help-block-hours_per_week"></span>
                                            {{-- End Hours/Week --}}
                                            {{-- Hours/Shift --}}
                                            <div class="ss-form-group col-11">
                                                <label>Hours/Shift</label>
                                                <input id="hours_shift" type="number" name="worker_hours_per_shift "
                                                    placeholder="Enter Hours/Shift">
                                            </div>
                                            <span class="help-block-hours_shift"></span>
                                            {{-- End Hours/Shift --}}
                                            {{-- Weeks/Assignment --}}
                                            <div class="ss-form-group col-11">
                                                <label>Weeks/Assignment</label>
                                                <input id="preferred_assignment_duration" type="number"
                                                    name="worker_weeks_assignment" placeholder="Enter Weeks/Assignment">
                                            </div>
                                            <span class="help-block-preferred_assignment_duration"></span>
                                            {{-- End Weeks/Assignment --}}
                                            {{-- Shifts/Week --}}
                                            <div class="ss-form-group col-11">
                                                <label>Shifts/Week</label>
                                                <input id="weeks_shift" type="number" name="worker_shifts_week"
                                                    placeholder="Enter Shifts/Week">
                                            </div>
                                            <span class="help-block-weeks_shift"></span>
                                            {{-- End Shifts/Week --}}
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec col-11">
                                                {{-- <button type="text" class="ss-prsnl-skip-btn"> Skip </button> --}}
                                                <button type="text" class="ss-prsnl-skip-btn prev-1"> Previous
                                                </button>
                                                <button type="text" class="ss-prsnl-save-btn next-1"> Save & Next
                                                </button>
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
                                                {{-- <input type="file" name="file" id="file" class="inputfile" /> --}}
                                                <input type="file" id="file" name="files" multiple
                                                    required><br><br>
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
                                                    {{-- the model --}}
                                                    <tr>
                                                        <td>$item->name</td>
                                                        <td><button type="button" class="delete"
                                                                data-id="$item->id">Delete
                                                                Documenst</button>
                                                        </td>
                                                    </tr>
                                                    {{-- end of the model --}}
                                                </tbody>
                                            </table>
                                            {{-- <div class="ss-nojob-dv-hed">
                <button type="submit" name="action" value="add" id="add_key" class="add">Add New Documents</button>
                <button type="submit" name="action" value="save" id="save_key" class="save d-none">Save changes</button>
            </div> --}}
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec">
                                                <button type="text" class="ss-prsnl-skip-btn prev-2"> Previous
                                                </button>
                                                <button id="uploadForm" class="ss-prsnl-save-btn next-2"> Save Files
                                                </button>
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
        $(document).ready(function() {
            $('#job_state').change(function() {
                const selectedState = $(this).find(':selected').attr('id');
                const CitySelect = $('#job_city');

                $.get(`/api/cities/${selectedState}`, function(data) {
                    CitySelect.empty();
                    CitySelect.append('<option value="">Select City</option>');
                    $.each(data, function(index, city) {
                        CitySelect.append(new Option(city.name, city.name));
                    });
                });
            });

            $('input[type="file"]').change(function() {
                var file = this.files[0]; // get the selected file
                var tbody = $('.table tbody');
                // tbody.empty(); // remove existing rows
                var row = $('<tr>');
                row.append($('<td>').text(file.name)); // display the file name
                var deleteButton = $('<button>').text('Delete Document').addClass('delete').attr('data-id',
                    file.id).prop('disabled', true); // disable the delete button
                row.append($('<td>').append(deleteButton));
                tbody.append(row);
            });

            @php
                $worker_id = $worker->id;
            @endphp
            const worker_id = '{!! $worker_id !!}';
            $.ajax({

                url: 'http://localhost:4545/documents/list-docs?workerId=' +
                    worker_id, // replace workerId with the actual workerId
                method: 'GET',
                success: function(resp) {
                    var tbody = $('.table tbody');
                    tbody.empty(); // remove existing rows
                    resp.forEach(function(file) {
                        var row = $('<tr>');
                        row.append($('<td>').text(file.name));
                        console.log(file.id);
                        var deleteButton = $('<button>').text('Delete Document').addClass(
                            'delete').attr('data-id', file.id);
                        deleteButton.click(function() {
                            $.ajax({
                                url: 'http://localhost:4545/documents/del-doc',
                                method: 'POST',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                contentType: 'application/json',
                                success: function() {
                                    row
                                        .remove(); // remove the row from the table
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });
                        row.append($('<td>').append(deleteButton));
                        tbody.append(row);
                    });
                },
                error: function(resp) {
                    console.log('Error:', resp);
                }
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
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";
            // current += 1;
            // }
        });

        nextBtnSec.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "-50%";
            //bullet[current - 1].classList.add("active");
            progress.style.width = "100%";
            // img need to be modified
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-refren.png') }}' /></span>Document management";
            //progressText[current - 1].classList.add("active");
            //current += 1;
        });

        prevBtnSec.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "0%";
            // bullet[current - 2].classList.remove("active");
            progress.style.width = "25%";
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-user.png') }}' /></span>Basic Information";
            // progressText[current - 2].classList.remove("active");
            // current -= 1;
        });
        prevBtnThird.addEventListener("click", function(event) {
            event.preventDefault();
            slidePage.style.marginLeft = "-25%";
            //bullet[current - 2].classList.remove("active");
            progress.style.width = "75%";
            infoType.innerHTML =
                "<span><img src='{{ URL::asset('frontend/img/my-per--con-vaccine.png') }}' /></span>Professional Information";
            // progressText[current - 2].classList.remove("active");
            // current -= 1;
        });

        document.getElementById('uploadForm').addEventListener('click', function(event) {
            event.preventDefault();

            @php
                $worker_id_json = $worker->id;
            @endphp
            let worker_id = '{!! $worker_id_json !!}';
            console.log(worker_id);
            var workerId = worker_id;
            var filesInput = document.getElementById('file');
            var files = Array.from(filesInput.files);

            Promise.all(files.map(file => {
                return new Promise((resolve, reject) => {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        resolve({
                            name: file.name,
                            content: event.target.result.split(',')[1]
                        });
                    };
                    reader.readAsDataURL(file);
                });
            })).then(files => {
                var body = {
                    workerId: workerId,
                    files: files
                };

                /// using ajax
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: 'http://localhost:4545/documents/add-docs',
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(body),
                    success: function(resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if (resp.ok) {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Files uploaded successfully',
                                time: 5
                            });

                        }
                    },
                    error: function(resp) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-check"></i> Error ! Please try again later .',
                            time: 5
                        });
                    }
                });
                // using fetch

                // fetch('http://localhost:4545/documents/add-docs', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json'
                //     },
                //     body: JSON.stringify(body)
                // }).then(response => {
                //     if (response.ok) {
                //         notie.alert({
                //         type: 'success',
                //         text: '<i class="fa fa-check"></i> Files uploaded successfully' ,
                //         time: 5
                //     });
                //     } else {
                //         alert('Failed to upload files');
                //     }
                // });
            });
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

@extends('worker::layouts.main')
@section('mytitle', 'My Profile')
@section('content')
    @php
        $user = auth()->guard('frontend')->user();

        // formatAmount :: helper function to remove .00 from amount
        $formatAmount = function ($value) {
            return !empty($value) && $value != 0 ? (fmod($value, 1) == 0 ? intval($value) : $value) : null;
        };
    @endphp
    <!--Main layout-->
    <main style="padding-top: 130px; padding-bottom: 100px;" class="ss-main-body-sec">
        <div class="container">

            <div class="ss-my-profil-div mb-5">

                <div class="row">
                    <h2>Worker <span class="ss-pink-color">Profile</span></h2>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="ss-profil-complet-div">
                            <div class="row d-flex justify-content-center align-items-center ">
                                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 m-0 p-0">
                                    <svg viewBox="-25 -25 250 250" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        style="transform:rotate(-90deg)">
                                        <circle r="90" cx="100" cy="100" fill="transparent" stroke="#e9d1e2"
                                            stroke-width="16px" stroke-dasharray="565.48px" stroke-dashoffset="0">
                                        </circle>
                                        <circle r="90" cx="100" cy="100" stroke="#ad66a3" stroke-width="16px"
                                            stroke-linecap="round" fill="transparent" stroke-dasharray="565.48px"
                                            stroke-dashoffset="{{ 565.48 * (1 - $progress_percentage / 100) }}px">
                                        </circle>
                                        <text x="71px" y="115px" fill="#3d2c39" font-size="40px" font-weight="bold"
                                            style="transform:rotate(90deg) translate(0px, -196px)">{{ $progress_percentage }}%</text>
                                    </svg>
                                </div>
                                {{-- if the profile is not complete --}}
                                <div id="profile_incomplete" class="row col-lg-9 col-md-6 col-sm-12 col-xs-12 p-0">
                                    <div class="col-12">
                                        <h6>Profile Incomplete</h6>
                                    </div>

                                    <div class="col-12">
                                        <p>You're just a few percent away from revenue. Complete your profile and get 50%.
                                        </p>
                                    </div>
                                </div>
                                {{-- if the profile is complete --}}
                                <div id="profile_complete" class="row col-lg-9 col-md-6 col-sm-12 col-xs-12 p-0 d-none">
                                    <div class="col-12">
                                        <h6>Profile complete</h6>
                                    </div>
                                    <div class="col-12">
                                        <p>Congratulations! Your profile is complete. You can now start earning.</p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="ss-my-profil-img-div">
                            <div class="profile-pic">
                                <label class="-label" for="file">
                                    <span class="glyphicon glyphicon-camera"></span>
                                    <span>Change Image</span>
                                </label>
                                <input id="file" type="file" accept=".heic, .png, .jpeg, .gif"
                                    onchange="loadFile(event)" />
                                @if (isset($user->image))
                                    <img src="{{ asset('uploads/' . $user->image) }}" id="output" width="200" />
                                @else
                                    <img src="{{ URL::asset('frontend/img/account-img.png') }}" id="output"
                                        width="200" />
                                @endif
                            </div>
                            <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                            <p>{{ $worker->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ss-opport-mngr-hedr mb-3">
                <div class="row">
                    {{-- <div class="col-lg-4">
                        <h4>titel</h4>
                    </div> --}}
                    <div class="col">
                        <ul>
                            <li><button id="profile_settings" onclick="ProfileIinformationDisplay()"
                                    class="ss-darfts-sec-draft-btn">Your Info & Requirements</button></li>
                            <li><button id="account_settings" onclick="AccountSettingDisplay()"
                                    class="ss-darfts-sec-publsh-btn">Account settings</button></li>
                            <li><button id="bonus_transfers" onclick="BonusTransfersDisplay()"
                                    class="ss-darfts-sec-publsh-btn">Bonus Transfers</button></li>
                            <li><button id="support" onclick="SupportDisplay()"
                                    class="ss-darfts-sec-publsh-btn">Support</button></li>
                            <li><button id="disable_account" onclick="DisactivateAccountDisplay()"
                                    class="ss-darfts-sec-publsh-btn">Disable account</button></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="ss-my-profile--basic-mn-sec">
                <div class="row">
                    {{-- ---------------------------------------------------------- Profile settings Form ---------------------------------------------------------- --}}
                    <div class=" profile_setting">
                        <div class="ss-pers-info-form-mn-dv" style="width: 100%;">

                            <div class="form-outer">

                                @include('worker::dashboard.profile.settings')
                                {{-- @include('worker::dashboard.profile.settings_backUp') --}}

                            </div>
                        </div>
                    </div>
                    {{-- ---------------------------------------------------------- End Profile settings Form ---------------------------------------------------------- --}}
                    {{-- ---------------------------------------------------------- Account settings Form ---------------------------------------------------------- --}}

                    <div class="account_setting d-none">
                        <div class="ss-pers-info-form-mn-dv">
                            <div class="ss-persnl-frm-hed">
                                <p><span><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Account
                                    Setting</p>
                            </div>
                            <div class="form-outer">
                                <!-- slide Account Setting -->
                                <div class="page slide-page">
                                    <div class="row justify-content-center">
                                        {{-- Email Information --}}
                                        <div class="ss-form-group col-11">
                                            <label>New Email</label>
                                            <input type="text" name="newEmail" id="newEmail"
                                                placeholder="Please enter your new Email">
                                        </div>
                                        <button type="button" class="mt-3 col-11 w-50 ss-prsnl-save-btn rounded-5"
                                            id="sendOTPforVerifyEmail">
                                            Send OTP
                                        </button>
                                        <span class="help-block-email"></span>

                                        {{-- OTP for new email --}}
                                        <div class="ss-form-group col-7 d-flex align-items-center">
                                            <label class="me-3">OTP:</label>
                                            <ul class="ss-otp-v-ul">
                                                <li><input class="otp-input" type="text" name="otp1"
                                                        oninput="digitValidate(this)" onkeyup="tabChange(1)"
                                                        maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp2"
                                                        oninput="digitValidate(this)" onkeyup="tabChange(2)"
                                                        maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp3"
                                                        oninput="digitValidate(this)" onkeyup="tabChange(3)"
                                                        maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp4"
                                                        oninput="digitValidate(this)" onkeyup="tabChange(4)"
                                                        maxlength="1"></li>
                                            </ul>

                                        </div>
                                        <span class="help-block-otp"></span>

                                        <div
                                            class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                            <button type="button" class="col-12 ss-prsnl-save-btn"
                                                id="SaveAccountInformation" style="display:none;">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- ---------------------------------------------------------- End Account settings  ---------------------------------------------------------- --}}
                    {{-- ----------------------------------------------------------  Bonus Area -------------------------------------------------------------------- --}}
                    <div class=" bonus_transfers d-none">
                        <div class="ss-pers-info-form-mn-dv">
                            <div class="ss-persnl-frm-hed">
                                <p><span><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Bonus
                                    Transfers</p>
                            </div>
                            <div class="form-outer">
                                {{-- <form method="post" action="{{ route('update-worker-profile') }}"> --}}
                                {{-- <form method="post" action="{{ route('update-bonus-transfer') }}">  --}}
                                <form onsubmit="return false;" method="post">
                                    @csrf
                                    <!-- slide Bonus Transfer -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Full name payment --}}
                                            <div class="ss-form-group col-11">
                                                <label>Full Name</label>
                                                <input type="text" name="full_name_payment"
                                                    placeholder="Please enter your full name">
                                            </div>
                                            <span class="help-block-full_name_payment"></span>
                                            {{-- Address payment --}}
                                            <div class="ss-form-group col-11">
                                                <label>Address</label>
                                                <input type="text" name="address_payment"
                                                    placeholder="Please enter your address">
                                            </div>
                                            <span class="help-block-address_payment"></span>
                                            {{-- Email --}}
                                            <div class="ss-form-group row col-11">
                                                <label>Email</label>
                                                <input type="text" name="email_payment"
                                                    placeholder="Please enter your new Email">
                                            </div>
                                            <span class="help-block-email_payment"></span>
                                            {{-- Bank Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Bank Name</label>
                                                <input id="bank_name_payment" type="text" name="bank_name_payment"
                                                    placeholder="Please enter your bank name">
                                            </div>
                                            <span class="help-block-bank_name_payment"></span>
                                            {{-- Rooting Number Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>Routing number payment</label>
                                                <input id="routing_number_payment" type="text"
                                                    name="routing_number_payment"
                                                    placeholder="Please enter your routing number payment">
                                            </div>
                                            <span class="help-block-routing_number_payment"></span>
                                            {{-- Bank Account Payment Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Bank Account Payment Number</label>
                                                <input id="bank_account_payment_number" type="text"
                                                    name="bank_account_payment_number"
                                                    placeholder="Please enter your bank account payment number">
                                            </div>
                                            <span class="help-block-bank_account_payment_number"></span>
                                            {{-- Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Phone Number</label>
                                                <input id="phone_number_payment" type="text"
                                                    name="phone_number_payment"
                                                    placeholder="Please enter your phone number">
                                            </div>
                                            <span class="help-block-phone_number_payment"></span>
                                            {{-- Skip && Save --}}
                                            <div
                                                class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <button type="text" class=" col-12 ss-prsnl-save-btn"
                                                    id="SaveBonusInformation"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    {{-- ----------------------------------------------------------  End Bonus Area -------------------------------------------------------------------- --}}
                    {{-- ----------------------------------------------------------  Support Area -------------------------------------------------------------------- --}}
                    <div class=" support_info d-none">
                        <div class="ss-pers-info-form-mn-dv" style="width:100%">
                            <div class="ss-persnl-frm-hed">
                                <h1
                                    style="font-family: Neue Kabel; font-size: 32px; font-weight: 500; line-height: 34px; text-align: center;color:3D2C39;">
                                    Help & Support
                                </h1>
                            </div>
                            <div class="form-outer">
                                <p style="
                                margin-top: 20px;
                            ">
                                    Please contact us at <span style="font-weight: 500">support@goodwork.com</span></p>

                            </div>

                        </div>
                    </div>
                    {{-- ------------------------------------------------------- End Support Area -------------------------------------------------------------------- --}}

                    {{-- ------------------------------------------------------- Disable account area -------------------------------------------------------------------- --}}
                    <div class=" disable_account d-none">
                        <div class="ss-pers-info-form-mn-dv" style="width:100%">
                            <div class="ss-persnl-frm-hed">
                                <p><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Disactivate
                                    your
                                    account</p>
                            </div>
                            <div class="form-outer">
                                <form method="post">
                                    @csrf
                                    <!-- slide Support -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Disactivate your account --}}
                                            <div
                                                class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">

                                                <button type="text" class="col-12 ss-prsnl-save-btn"
                                                    id="DisactivateAccount">
                                                    {{-- spinner --}}
                                                    <span id="loading_disableOption" class="d-none">
                                                        <span style="width=5%" id="loadSpan_disableOption"
                                                            class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </span>
                                                    <span id="disactivate_account">Disable</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    {{-- ------------------------------------------------------- Disable account area -------------------------------------------------------------------- --}}
                </div>
    </main>
@stop

@section('js')

    <script type="text/javascript">
        let tabChange = function(val) {
            let inputs = document.querySelectorAll('.otp-input'); // Select all OTP input fields
            let saveButton = document.getElementById('SaveAccountInformation'); // Save button element
            if (inputs[val - 1].value !== "") {
                if (val < inputs.length) {
                    inputs[val].focus(); // Move to the next input
                }
            } else if (inputs[val - 1].value === "" && val > 1) {
                inputs[val - 2].focus(); // Move to the previous input
            }
            // Check if all inputs are filled
            let allFilled = Array.from(inputs).every(input => input.value !== "");
            saveButton.style.display = allFilled ? "block" : "none"; // Show or hide the Save button
        };
        let digitValidate = function(ele) {
            ele.value = ele.value.replace(/[^0-9]/g, ""); // Allow only digits
        };
    </script>

    {{-- get elements - prevent defaults behaviors  --}}
    <script>
        // slide control
        const progress = document.getElementById("progress");
        // end slide control

        // inputs
        // Basic Info
        const first_name = document.querySelector('input[name="first_name"]');
        const last_name = document.querySelector('input[name="last_name"]');
        const mobile = document.querySelector('input[name="mobile"]');
        const address = document.querySelector('input[name="address"]');
        const city = document.querySelector('select[name="city"]');
        const state = document.querySelector('select[name="state"]');
        const zip_code = document.querySelector('input[name="zip_code"]');
        // Professional Info
        const profession = document.querySelector('select[name="profession"]');
        const specialty = document.querySelector('select[name="specialty"]');
        const terms = document.querySelector('select[name="terms"]');
        const worker_job_type = document.querySelector('select[name="worker_job_type"]');
        const block_scheduling = document.querySelector('select[name="block_scheduling"]');
        const float_requirement = document.querySelector('select[name="float_requirement"]');
        const facility_shift_cancelation_policy = document.querySelector(
            'select[name="facility_shift_cancelation_policy"]');
        const contract_termination_policy = document.querySelector('input[name="contract_termination_policy"]');
        const traveler_distance_from_facility = document.querySelector('input[name="distance_from_your_home"]');
        const clinical_setting = document.querySelector('input[name="clinical_setting_you_prefer"]');
        const Patient_ratio = document.querySelector('input[name="worker_patient_ratio"]');
        const emr = document.querySelector('select[name="worker_emr"]');
        const Unit = document.querySelector('input[name="worker_unit"]');
        const scrub_color = document.querySelector('input[name="worker_scrub_color"]');
        const rto = document.querySelector('select[name="rto"]');
        const shift_of_day = document.querySelector('select[name="worker_shift_time_of_day"]');
        const hours_shift = document.querySelector('input[name="worker_hours_shift"]');
        const preferred_assignment_duration = document.querySelector('input[name="worker_weeks_assignment"]');
        const weeks_shift = document.querySelector('input[name="worker_shifts_week"]');
        const worker_experience = document.querySelector('input[name="worker_experience"]');
        const worker_eligible_work_in_us = document.querySelector('select[name="worker_eligible_work_in_us"]');
        const nursing_license_state = document.querySelector('select[name="nursing_license_state"]');
        const worker_facility_city = document.querySelector('input[name="worker_facility_city"]');
        const worker_facility_state = document.querySelector('select[name="worker_facility_state"]');
        const worker_start_date = document.querySelector('input[name="worker_start_date"]');
        const worker_guaranteed_hours = document.querySelector('input[name="worker_guaranteed_hours"]');
        const worker_sign_on_bonus = document.querySelector('input[name="worker_sign_on_bonus"]');
        const worker_completion_bonus = document.querySelector('input[name="worker_completion_bonus"]');
        const worker_extension_bonus = document.querySelector('input[name="worker_extension_bonus"]');
        const worker_other_bonus = document.querySelector('input[name="worker_other_bonus"]');
        const worker_four_zero_one_k = document.querySelector('select[name="worker_four_zero_one_k"]');
        const worker_health_insurance = document.querySelector('select[name="worker_health_insurance"]');
        const worker_dental = document.querySelector('select[name="worker_dental"]');
        const worker_vision = document.querySelector('select[name="worker_vision"]');
        const worker_overtime_rate = document.querySelector('input[name="worker_overtime_rate"]');
        const worker_holiday = document.querySelector('input[name="worker_holiday"]');
        const worker_on_call_check = document.querySelector('select[name="worker_on_call_check"]');
        const worker_on_call = document.querySelector('input[name="worker_on_call"]');
        const worker_call_back = document.querySelector('input[name="worker_call_back"]');
        const worker_orientation_rate = document.querySelector('input[name="worker_orientation_rate"]');
        const worker_benefits = document.querySelector('select[name="worker_benefits"]');
        const nurse_classification = document.querySelector('select[name="nurse_classification"]');
        // Document Management
        const file = document.getElementById('document_file');
        // bonus transfer
        const full_name_payment = document.querySelector('input[name="full_name_payment"]');
        const address_payment = document.querySelector('input[name="address_payment"]');
        const email_payment = document.querySelector('input[name="email_payment"]');
        const bank_name_payment = document.querySelector('input[name="bank_name_payment"]');
        const routing_number_payment = document.querySelector('input[name="routing_number_payment"]');
        const bank_account_payment_number = document.querySelector('input[name="bank_account_payment_number"]');
        const phone_number_payment = document.querySelector('input[name="phone_number_payment"]');
        // end inputs
        // change info type title
        const infoType = document.getElementById("information_type");
        // end change info type title

        if (city.value == '') {
            document.querySelector('.help-city').classList.remove('d-none');
        }

        if (worker_facility_city.value == '') {
            document.querySelector('.help-block-worker_facility_city').classList.remove('d-none');
        }

        // end next and prev buttons
    </script>

    {{-- js for multiselect --}}
    <script>
        var selectedFiles = [];
        var selectedValues = [];
        var selectedTypeFile = '';

        function open_file(obj) {
            $(obj).parent().find('input[type="file"]').click();
        }

        function open_modal(obj) {
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);

            $(modal).modal('show');
        }

        function controlInputsFiles(obj) {
            let filesSelected = document.querySelectorAll('.files-upload');
            filesSelected.forEach((fileInput) => {
                fileInput.value = '';
            });

            let fileNameDiv = document.querySelectorAll('.file-name');
            fileNameDiv.forEach((fileDiv) => {
                fileDiv.remove();
            });

            HideAllInputsModal();
            removeAllCheckBox();
            const inputsId = obj.value;
            selectedTypeFile = inputsId;
            console.log(inputsId);
            //removing d-none class
            document.getElementById(inputsId).classList.remove('d-none');
        }

        function HideAllInputsModal() {
            var allInputsDivs = ['skills_checklists', 'certificate', 'driving_license', 'nursing_license_state', 'other',
                'vaccinations',
                'references',
                'diploma', 'professional_license'
            ];
            allInputsDivs.forEach((InputsDiv) => {
                // adding d-none class
                if (document.getElementById(InputsDiv) != null)
                    document.getElementById(InputsDiv).classList.add('d-none');
            });
        }

        function closeModal() {
            let buttons = document.querySelectorAll('.btn-close');
            buttons.forEach(button => {
                button.click();
            });
        }
        document.addEventListener('DOMContentLoaded', function() {

            // options from the combobox
            const filesSelected = document.querySelectorAll('.files-upload');

            // give hight to filesNamesArea
            const types = ['vaccinations', 'certificate', 'nursing_license_state', 'skills_checklists'];


            filesSelected.forEach((fileInput) => {
                fileInput.addEventListener('change', function() {
                    if (!types.includes(selectedTypeFile)) {
                        if (this.files.length > 0) {
                            const file = this.files[0];
                            var fileName = file.name;
                            if (fileName.length > 20) {
                                fileName = fileName.substring(0, 20) + '...';
                            }
                            const fileDiv = document.createElement('div');
                            fileDiv.classList.add('file-name', 'row', 'col-12');
                            const fileSpan = document.createElement('span');
                            fileSpan.classList.add('col-11');
                            const fileText = document.createTextNode(fileName);
                            fileSpan.appendChild(fileText);
                            fileDiv.appendChild(fileSpan);
                            const removeIcon = document.createElement('i');
                            removeIcon.classList.add('fa', 'fa-times', 'remove-file', 'col-1');
                            removeIcon.addEventListener('click', function() {
                                fileDiv.remove();
                                fileInput.value = '';
                            });
                            fileDiv.appendChild(removeIcon);

                            const filesNamesArea = this.closest('.container-multiselect');

                            filesNamesArea.appendChild(fileDiv);
                        }
                    }
                });

            });


            const items = document.querySelectorAll('.list-items .item');
            //store selected file values

            items.forEach((item, index) => {
                item.addEventListener('click', (event) => {
                    const uploadInput = item.nextElementSibling;
                    //console.log('this is the next sibling : ', uploadInput)
                    if (uploadInput) {
                        // class 'checked' check
                        if (item.classList.contains('checked')) {
                            uploadInput.click();
                            uploadInput.addEventListener('change', function() {
                                if (this.files.length > 0) {
                                    // Handling file selection
                                    const file = this.files[0];
                                    selectedFiles.push(file.name);
                                    //console.log(selectedFiles);
                                }
                            }, {
                                once: true //avoid multiple registrations
                            });
                        } else {
                            const index = selectedFiles.indexOf(uploadInput.files[0].name);
                            if (index > -1) {
                                selectedFiles.splice(index, 1);
                            }
                            //console.log(selectedFiles);

                        }
                    }
                });
            });


        });

        function removeAllCheckBox() {
            const items = document.querySelectorAll('.list-items .item');
            items.forEach((item, index) => {
                item.classList.remove('checked');
            });
        }

        function sendMultipleFiles(type) {

            const fileInputs = document.querySelectorAll('.files-upload');
            //console.log('this is my file inputs values', fileInputs);

            const fileReadPromises = [];
            let worker_id = '{!! $worker->id !!}';
            //console.log(worker_id);
            var workerId = worker_id;

            if (type == 'references') {

                let referenceName = document.querySelector('input[name="name"]').value;
                let referencePhone = document.querySelector('input[name="phone"]').value;
                let referenceEmail = document.querySelector('input[name="reference_email"]').value;
                let referenceDate = document.querySelector('input[name="date_referred"]').value;
                let referenceMinTitle = document.querySelector('input[name="min_title_of_reference"]').value;
                let referenceRecency = document.querySelector('select[name="recency_of_reference"]').value;
                let referenceImage = document.querySelector('input[name="image"]').files[0];

                var referenceInfo = {
                    referenceName: referenceName,
                    phoneNumber: referencePhone,
                    email: referenceEmail,
                    dateReferred: referenceDate,
                    minTitle: referenceMinTitle,
                    isLastAssignment: referenceRecency == 1 ? true : false
                }

                //console.log(referenceInfo);

                if (referenceInfo == null) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-times"></i> Please fill all the fields',
                        time: 3
                    });
                    return;
                }

                let readerPromise = new Promise((resolve, reject) => {

                    const reader = new FileReader();

                    reader.onload = function(event) {
                        resolve({
                            name: referenceImage.name,
                            path: referenceImage.name,
                            type: type,
                            content: event.target.result, // Base64 encoded content
                            displayName: referenceImage.name,
                            ReferenceInformation: referenceInfo
                        });
                    };

                    reader.onerror = reject;
                    reader.readAsDataURL(referenceImage);

                });

                fileReadPromises.push(readerPromise);
                removeAllCheckBox();

            } else {

                fileInputs.forEach((input, index) => {

                    let displayName = input.getAttribute("displayName");

                    if (input.files[0]) {

                        const file = input.files[0];

                        const readerPromise = new Promise((resolve, reject) => {

                            const reader = new FileReader();

                            reader.onload = function(event) {
                                resolve({
                                    name: file.name,
                                    path: file.name,
                                    type: type,
                                    content: event.target.result, // Base64 encoded content
                                    displayName: displayName || file.name,
                                });
                            };

                            reader.onerror = reject;
                            reader.readAsDataURL(file);

                        });

                        fileReadPromises.push(readerPromise);
                    }
                });
            }

            if (fileReadPromises.length == 0) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-times"></i> Please select a file',
                    time: 3
                });
                return;
            }


            Promise.all(fileReadPromises).then(files => {
                //console.log(files);
                var body = {
                    workerId: workerId,
                    files: files
                };

                fetch('/worker/add-docs', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            workerId: workerId,
                            files: files
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        //console.log(data); // Handle success
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Files uploaded successfully',
                            time: 3
                        });
                        refreshDocsList();
                        closeModal();
                    })
                    .catch(error => {
                        //console.error('Error:', error); // Handle errors
                    });

            }).catch(error => {
                console.error('Error reading files:', error); // Handle file read errors
            });

            // clear files inputs
            fileInputs.forEach((input) => {
                input.value = '';
            });

            selectedFiles = [];
            removeAllCheckBox();

        }

        const selectBtn = document.querySelectorAll(".select-btn"),

            items = document.querySelectorAll(".item");


        selectBtn.forEach(selectBtn => {
            selectBtn.addEventListener("click", () => {
                selectBtn.classList.toggle("open");
            });
        });

        items.forEach(item => {
            item.addEventListener("click", () => {
                const value = item.getAttribute('value');
                item.classList.toggle("checked");

                if (item.classList.contains("checked")) {
                    // add item
                    selectedValues.push(value);
                    //console.log(selectedValues);
                } else {
                    // remove item
                    const index = selectedValues.indexOf(value);
                    if (index > -1) {
                        selectedValues.splice(index, 1);
                        //console.log(selectedValues);
                    }
                }
                let btnText = document.querySelector(".btn-text");
                if (selectedValues.length > 0) {
                    btnText.innerText = `${selectedValues.length} Selected`;
                } else {
                    btnText.innerText = "Select Language";
                }
            });
        })
    </script>
    {{-- end js for multiselect --}}

    <script type="text/javascript">
        // loding states cities docs on page load
        @php
            $worker_id = $worker->id;
        @endphp
        const worker_id = '{!! $worker_id !!}';

        refreshDocList(worker_id);

        // refresh docs list fun 
        function refreshDocsList() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('list-docs') }}',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    WorkerId: worker_id
                }),
                success: function(resp) {
                    var data;
                    try {
                        // Try to manually parse the response as JSON
                        data = JSON.parse(resp);
                        console.log(data);
                    } catch (e) {
                        // If parsing fails, assume resp is already a JavaScript object
                        data = resp;
                    }

                    var tbody = $('.table tbody');
                    tbody.empty();
                    data.forEach(function(file) {
                        var row = $('<tr>');
                        row.attr('class', 'row');
                        row.append($('<td class="col-3 td-table">').text(file.displayName));

                        row.append($('<td class="col-3 td-table">').text(file.type));
                        console.log(file.id);
                        var deleteButton = $('<button>').text('Delete Document').addClass(
                            'delete').attr('data-id', file.id);
                        deleteButton.click(function(event) {
                            event.preventDefault();
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                url: '{{ route('del-doc') }}',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                success: function() {
                                    row.remove();
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });
                        var viewFile = $('<button>').text('View Document').addClass('delete')
                            .attr('data-id', file.id);
                        viewFile.click(function(event) {
                            event.preventDefault();
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                url: '{{ route('get-doc') }}',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                success: function(resp) {

                                    resp = JSON.parse(resp);

                                    const base64String = resp.content.data;
                                    const mimeType = base64String.match(
                                        /^data:(.+);base64,/)[1];
                                    const base64Data = base64String.split(
                                        ',')[1];


                                    const byteCharacters = atob(base64Data);
                                    const byteNumbers = new Array(
                                        byteCharacters.length);
                                    for (let i = 0; i < byteCharacters
                                        .length; i++) {
                                        byteNumbers[i] = byteCharacters
                                            .charCodeAt(i);
                                    }
                                    const byteArray = new Uint8Array(
                                        byteNumbers);


                                    const blob = new Blob([byteArray], {
                                        type: mimeType
                                    });


                                    const blobUrl = URL.createObjectURL(
                                        blob);
                                    const downloadLink = document
                                        .createElement('a');
                                    downloadLink.href = blobUrl;


                                    const extension = mimeType.split('/')[
                                        1
                                    ];
                                    downloadLink.setAttribute('download', file
                                    .name);

                                    document.body.appendChild(downloadLink);
                                    downloadLink.click();
                                    document.body.removeChild(
                                        downloadLink);
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });


                        row.append($('<td class="col-3 td-table">').append(viewFile));
                        row.append($('<td class="col-3 td-table">').append(deleteButton));

                        tbody.append(row);
                    });
                },
                error: function(resp) {
                    console.log('Error:', resp);
                }
            });
        }

        $(document).ready(function() {
            if (@json($type == 'profile')) {
                ProfileIinformationDisplay();

            } else {
                AccountSettingDisplay();
            }
            // const AccessToStripeAccount = document.getElementById('AccessToStripeAccount');
            // const AddStripeAccount = document.getElementById('AddStripeAccount');

            $('#contact_number').mask('+1 (999) 999-9999');
            $('#new_contact_number').mask('+1 (999) 999-9999');
            $('#phone_number_payment').mask('+1 (999) 999-9999');
            $('#routing_number_payment').mask('999-999-999');
            $('#bank_account_payment_number').mask('9999-9999-9999');
            // solution of the case that we got - when we type a caracter :
            // $('#bank_account_payment_number').on('input', function() {
            // var inputValue = $(this).val();
            // var numericValue = inputValue.replace(/[^0-9]/g, '');
            // var formattedValue = numericValue.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
            // $(this).val(formattedValue);
            // });
            // or we should use a nother plugin


            let account_tier = '{{ $progress_percentage }}';
            if (account_tier > 67) {
                document.getElementById('profile_incomplete').classList.add('d-none');
                document.getElementById('profile_complete').classList.remove('d-none');
            }
            // -----------------------------  Profile Setting area  ---------------------------- //
        });


        var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;

        // validation basic information -ELH-
        function validateBasicInfo() {
            let isValid = true;
            if (first_name.value === '') {
                $('.help-block-first_name').text('Please enter a first name');
                $('.help-block-first_name').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-first_name').text('');
            }
            if (last_name.value === '') {
                $('.help-block-last_name').text('Please enter a last name');
                $('.help-block-last_name').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-last_name').text('');
            }

            // if ((!regexPhone.test(mobile)) && (mobile.value === '')) {
            //     $('.help-block-mobile').text('Please enter a mobile number');
            //     $('.help-block-mobile').addClass('text-danger');
            //     isValid = false;
            // }else{
            //     $('.help-block-mobile').text('');
            // }
            // if (address.value === '') {
            //     $('.help-block-address').text('Please enter an address');
            //     $('.help-block-address').addClass('text-danger');
            //     isValid = false;
            // } else{
            //     $('.help-block-address').text('');
            // }
            // if (city.value === '') {
            //     $('.help-block-city').text('Please enter a city');
            //     $('.help-block-city').addClass('text-danger');
            //     isValid = false;
            // } else{
            //     $('.help-block-city').text('');
            // }
            // if (state.value === '') {
            //     $('.help-block-state').text('Please enter a state');
            //     $('.help-block-state').addClass('text-danger');
            //     isValid = false;
            // }   else{
            //     $('.help-block-state').text('');
            // }
            // if (zip_code.value === '') {
            //     $('.help-block-zip_code').text('Please enter a zip code');
            //     $('.help-block-zip_code').addClass('text-danger');
            //     isValid = false;
            // }   else{
            //     $('.help-block-zip_code').text('');
            // }
            return isValid;
        }
        // end validation basic information
        // validation professional information
        function validateProfessionalInfo() {

            var isValid = true;

            const fields = [{
                    field: profession,
                    errorClass: 'help-block-profession',
                    errorMessage: 'Please enter a profession'
                },
                {
                    field: specialty,
                    errorClass: 'help-block-specialty',
                    errorMessage: 'Please enter a specialty'
                },
                {
                    field: terms,
                    errorClass: 'help-block-terms',
                    errorMessage: 'Please enter a term'
                },
                {
                    field: worker_job_type,
                    errorClass: 'help-block-worker_job_type',
                    errorMessage: 'Please enter a worker type'
                },
                {
                    field: block_scheduling,
                    errorClass: 'help-block-block_scheduling',
                    errorMessage: 'Please enter a block scheduling'
                },
                {
                    field: float_requirement,
                    errorClass: 'help-block-float_requirement',
                    errorMessage: 'Please enter a float requirement'
                },
                {
                    field: facility_shift_cancelation_policy,
                    errorClass: 'help-block-facility_shift_cancelation_policy',
                    errorMessage: 'Please enter a facility shift cancelation policy'
                },
                {
                    field: contract_termination_policy,
                    errorClass: 'help-block-contract_termination_policy',
                    errorMessage: 'Please enter a contract termination policy'
                },
                {
                    field: traveler_distance_from_facility,
                    errorClass: 'help-block-traveler_distance_from_facility',
                    errorMessage: 'Please enter the Distance from your home'
                },
                {
                    field: clinical_setting,
                    errorClass: 'help-block-clinical_setting_you_prefer',
                    errorMessage: 'Please enter a clinical setting'
                },
                {
                    field: Patient_ratio,
                    errorClass: 'help-block-worker_patient_ratio',
                    errorMessage: 'Please enter a patient ratio'
                },
                {
                    field: emr,
                    errorClass: 'help-block-worker_emr',
                    errorMessage: 'Please enter an EMR'
                },
                {
                    field: Unit,
                    errorClass: 'help-block-worker_unit',
                    errorMessage: 'Please enter a unit'
                },
                {
                    field: scrub_color,
                    errorClass: 'help-block-worker_scrub_color',
                    errorMessage: 'Please enter a scrub color'
                },
                {
                    field: rto,
                    errorClass: 'help-block-rto',
                    errorMessage: 'Please enter an RTO'
                },
                {
                    field: shift_of_day,
                    errorClass: 'help-block-worker_shift_time_of_day',
                    errorMessage: 'Please enter a shift time of day'
                },
                {
                    field: hours_shift,
                    errorClass: 'help-block-worker_hours_shift',
                    errorMessage: 'Please enter hours per shift'
                },
                {
                    field: preferred_assignment_duration,
                    errorClass: 'help-block-worker_weeks_assignment',
                    errorMessage: 'Please enter a preferred assignment duration'
                },
                {
                    field: weeks_shift,
                    errorClass: 'help-block-worker_shifts_week',
                    errorMessage: 'Please enter a weeks shift'
                },
                {
                    field: worker_experience,
                    errorClass: 'help-block-worker_experience',
                    errorMessage: 'Please enter worker experience'
                },
                {
                    field: worker_eligible_work_in_us,
                    errorClass: 'help-block-worker_eligible_work_in_us',
                    errorMessage: 'Please enter worker eligible work in us'
                },
                {
                    field: nursing_license_state,
                    errorClass: 'help-block-nursing_license_state',
                    errorMessage: 'Please enter a nursing license state'
                },
                {
                    field: worker_facility_city,
                    errorClass: 'help-block-worker_facility_city',
                    errorMessage: 'Please enter a worker facility city'
                },
                {
                    field: worker_facility_state,
                    errorClass: 'help-block-worker_facility_state',
                    errorMessage: 'Please enter a worker facility state'
                },
                {
                    field: worker_start_date,
                    errorClass: 'help-block-worker_start_date',
                    errorMessage: 'Please enter a worker start date'
                },
                {
                    field: worker_guaranteed_hours,
                    errorClass: 'help-block-worker_guaranteed_hours',
                    errorMessage: 'Please enter worker guaranteed hours'
                },
                {
                    field: worker_sign_on_bonus,
                    errorClass: 'help-block-worker_sign_on_bonus',
                    errorMessage: 'Please enter a worker sign on bonus'
                },
                {
                    field: worker_completion_bonus,
                    errorClass: 'help-block-worker_completion_bonus',
                    errorMessage: 'Please enter a worker completion bonus'
                },
                {
                    field: worker_extension_bonus,
                    errorClass: 'help-block-worker_extension_bonus',
                    errorMessage: 'Please enter a worker extension bonus'
                },
                {
                    field: worker_other_bonus,
                    errorClass: 'help-block-worker_other_bonus',
                    errorMessage: 'Please enter a worker other bonus'
                },
                {
                    field: worker_four_zero_one_k,
                    errorClass: 'help-block-worker_four_zero_one_k',
                    errorMessage: 'Please enter a worker four zero one k'
                },
                {
                    field: worker_health_insurance,
                    errorClass: 'help-block-worker_health_insurance',
                    errorMessage: 'Please enter worker health insurance'
                },
                {
                    field: worker_dental,
                    errorClass: 'help-block-worker_dental',
                    errorMessage: 'Please enter worker dental'
                },
                {
                    field: worker_vision,
                    errorClass: 'help-block-worker_vision',
                    errorMessage: 'Please enter worker vision'
                },
                {
                    field: worker_overtime_rate,
                    errorClass: 'help-block-worker_overtime_rate',
                    errorMessage: 'Please enter worker overtime rate'
                },
                {
                    field: worker_holiday,
                    errorClass: 'help-block-worker_holiday',
                    errorMessage: 'Please enter worker holiday'
                },
                {
                    field: worker_on_call_check,
                    errorClass: 'help-block-worker_on_call_check',
                    errorMessage: 'Please enter worker on call check'
                },
                {
                    field: worker_on_call,
                    errorClass: 'help-block-worker_on_call',
                    errorMessage: 'Please enter worker on call rate'
                },
                {
                    field: worker_call_back,
                    errorClass: 'help-block-worker_call_back',
                    errorMessage: 'Please enter worker on call back rate'
                },
                {
                    field: worker_orientation_rate,
                    errorClass: 'help-block-worker_orientation_rate',
                    errorMessage: 'Please enter worker orientation rate'
                },
                {
                    field: worker_benefits,
                    errorClass: 'help-block-worker_benefits',
                    errorMessage: 'Please enter worker benefits'
                },
                {
                    field: nurse_classification,
                    errorClass: 'help-block-nurse_classification',
                    errorMessage: 'Please enter a worker classification'
                },
                {
                    field: nursing_license_number,
                    errorClass: 'help-block-licence',
                    errorMessage: 'Please enter a licence number'
                }
            ];

            // Validate fields
            fields.forEach(({
                field,
                errorClass,
                errorMessage
            }) => {
                if (field.value === '') {
                    $(`.${errorClass}`).text(errorMessage).addClass('text-danger');
                    isValid = false;
                } else {
                    $(`.${errorClass}`).text('').removeClass('text-danger');
                }
            });

            return isValid;
        }
        // end validation professional information
        // validation document management
        function validateDocumentManagement() {
            let isValid = true;
            if (file.value === '') {
                $('.help-block-file').text('Please select a file');
                $('.help-block-file').addClass('text-danger');
                isValid = false;
            }
            return isValid;
        }
        // end validation document management

        // validation bonus

        function validateBonusInfo() {
            let isValid = true;

            // Full name validation
            const fullNameRegex_payment = /^[a-zA-Z\s]{1,255}$/;
            if (($('input[name="full_name_payment"]').val() === '') && (!fullNameRegex_payment.test(full_name_payment
                    .value))) {
                $('.help-block-full_name_payment').text(
                    'Full name can only contain letters and spaces, and cannot be longer than 255 characters');
                $('.help-block-full_name_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-full_name_payment').text('');
            }


            if ($('input[name="address_payment"]').val() === '') {
                $('.help-block-address_payment').text('Please enter your address');
                $('.help-block-address_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-address_payment').text('');
            }

            const emailRegex_payment = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (($('input[name="email_payment"]').val() === '') || (!emailRegex_payment.test($(
                    'input[name="email_payment"]').val()))) {
                //console.log(email_payment.value);
                $('.help-block-email_payment').text('Please enter a valid email');
                $('.help-block-email_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-email_payment').text('');
            }

            if ($('input[name="bank_name_payment"]').val() === '') {
                $('.help-block-bank_name_payment').text('Please enter your bank name');
                $('.help-block-bank_name_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-bank_name_payment').text('');
            }

            if ($('input[name="routing_number_payment"]').val() === '') {
                $('.help-block-routing_number_payment').text('Please enter your routing number');
                $('.help-block-routing_number_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-routing_number_payment').text('');
            }

            if ($('input[name="bank_account_payment_number"]').val() === '') {
                $('.help-block-bank_account_payment_number').text('Please enter your bank account number');
                $('.help-block-bank_account_payment_number').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-bank_account_payment_number').text('');
            }

            const regexPhone_payment = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            if (($('input[name="phone_number_payment"]').val() === '') && (!regexPhone_payment.test(phone_number_payment
                    .value))) {
                $('.help-block-phone_number_payment').text('Please enter a valid phone number');
                $('.help-block-phone_number_payment').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-phone_number_payment').text('');
            }

            return isValid;
        }

        // end bonus validation


        // Save Bonus Transfer
        const SaveBonusInformation = document.getElementById("SaveBonusInformation");
        SaveBonusInformation.addEventListener("click", function(event) {
            event.preventDefault();
            if (!validateBonusInfo()) {
                return;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/worker/add-worker-payment',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    full_name_payment: full_name_payment.value,
                    address_payment: address_payment.value,
                    email_payment: email_payment.value,
                    bank_name_payment: bank_name_payment.value,
                    routing_number_payment: routing_number_payment.value,
                    bank_account_payment_number: bank_account_payment_number.value,
                    phone_number_payment: phone_number_payment.value,
                }),
                success: function(resp) {
                    //console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Payment Information Successfully',
                            time: 5
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(resp) {
                    console.log(resp);
                    notie.alert({
                        type: 'error',
                        text: resp,
                        time: 5
                    });
                }
            });

        });
        // end Saving Bonus Transfer

        // account disactivating

        const DisactivateAccount = document.getElementById("DisactivateAccount");

        DisactivateAccount.addEventListener("click", function(event) {
            $('#loading_disableOption').removeClass('d-none');
            $('#disactivate_account').addClass('d-none');
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/worker/disactivate-account',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true
                }),
                success: function(resp) {
                    //console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Account disactivated Successfully',
                            time: 5
                        });
                        $('#loading_disableOption').addClass('d-none');
                        $('#disactivate_account').removeClass('d-none');
                        window.location.href = "/";
                    }

                },
                error: function(resp) {
                    //console.log(resp);
                    notie.alert({
                        type: 'error',
                        text: resp,
                        time: 5
                    });
                }
            });
        });

        // end account disactivating

        /*
        // creating a stripe account
        
        AddStripeAccount.addEventListener("click", function(event) {
            $('#loading_disableOption').removeClass('d-none');
            $('#disactivate_account').addClass('d-none');
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/worker/add-stripe-account',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true
                }),
                success: function(resp) {
                    //console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Account created succefuly',
                            time: 5
                        });
                        $('#loading_disableOption').addClass('d-none');
                        $('#disactivate_account').removeClass('d-none');
                        //console.log(resp);
                        window.location.href = resp.account_link;
                    }
                },
                error: function(resp) {
                    //console.log(resp);
                    notie.alert({
                        type: 'error',
                        text: resp,
                        time: 5
                    });
                }
            });
        });

        // end creating stripe account

        // start redirecting to login stripe link

        AccessToStripeAccount.addEventListener("click", function(event) {
            $('#loading_disableOption').removeClass('d-none');
            $('#disactivate_account').addClass('d-none');
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/worker/login-to-stripe-account',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true
                }),
                success: function(resp) {
                    //console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Redirecting',
                            time: 2
                        });
                        $('#loading_disableOption').addClass('d-none');
                        $('#disactivate_account').removeClass('d-none');
                        window.location.href = resp.login_link;
                    }
                },
                error: function(resp) {
                    //console.log(resp);
                    notie.alert({
                        type: 'error',
                        text: resp,
                        time: 5
                    });
                }
            });
        });


        // end redirecting to login stripe link

        */

        // --------------------------- end Profile Setting area  ---------------------------- //

        // --------------------------- Account Setting Area --------------------------------- //

        // inputs account settings

        //const new_mobile = document.querySelector('input[name="new_mobile"]');
        const email = document.querySelector('input[name="newEmail"]');
        var inputs = [];

        // account setting validation here

        function validateAccountSettingInformation() {
            //$('.help-block-new_mobile').text('');
            $('.help-block-validation').text('');
            $('.help-block-email').text('');
            let isValid = true;
            // Create an array of all inputs
            inputs = [email];



            // Check if all inputs are empty
            const allEmpty = inputs.every(input => input.value.trim() === '');

            // If all inputs are empty, show an error
            if (allEmpty) {
                $('.help-block-validation').text('Please fill at least one field');
                $('.help-block-validation').addClass('text-danger');
                isValid = false;
            }

            // Email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email.value)) {
                $('.help-block-email').text('Please enter a valid email');
                $('.help-block-email').addClass('text-danger');
                isValid = false;
            }

            return isValid;
        }
        // end account setting validation


        // send request to update here
        // const SaveAccountInformation = document.getElementById('SaveAccountInformation');
        // SaveAccountInformation.addEventListener("click", function(event) {
        //     event.preventDefault();
        //     if (!validateAccountSettingInformation()) {
        //         return;
        //     }

        //     // clear form data from empty values
        //     const formData = new FormData();
        //     inputs.forEach(input => {
        //         if (input.value.trim() !== '') {
        //             formData.append(input.name, input.value);
        //         }
        //     });

        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: '/worker/update-worker-account-setting',
        //         type: 'POST',
        //         processData: false,
        //         contentType: false,
        //         data: formData,
        //         success: function(resp) {
        //             //console.log(resp);
        //             if (resp.status) {
        //                 notie.alert({
        //                     type: 'success',
        //                     text: '<i class="fa fa-check"></i> ' + resp.message,
        //                     time: 5
        //                 });

        //             } else {
        //                 notie.alert({
        //                     type: 'error',
        //                     text: '<i class="fa fa-check"></i> ' + resp.message,
        //                     time: 5
        //                 });
        //             }
        //         },
        //         error: function(resp) {
        //             notie.alert({
        //                 type: 'error',
        //                 text: '<i class="fa fa-check"></i> Please try again later !',
        //                 time: 5
        //             });
        //         }
        //     });


        // });

        // send otp button
        const sendOTPButton = document.getElementById('sendOTPforVerifyEmail');
        sendOTPButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (!validateAccountSettingInformation()) {
                return;
            }

            let email = document.getElementById('newEmail').value;

            let data = {
                email: email
            };
            $.ajax({
                url: '/worker/send-otp-worker',
                type: 'POST',
                data: data,
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + resp.message,
                            time: 5
                        });
                    } else {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-check"></i> ' + resp.message,
                            time: 5
                        });
                    }
                },
                error: function(resp) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i> Please try again later !',
                        time: 5
                    });
                }
            });

        })

        function ValidateOTP() {
            let inputs = document.querySelectorAll('.otp-input');
            let otp = Array.from(inputs).map(input => input.value).join('');
            let isValid = true;
            if (otp === '') {
                $('.help-block-otp').text('Please enter the OTP');
                $('.help-block-otp').addClass('text-danger');
                isValid = false;
            } else if (otp.length < inputs.length) {
                $('.help-block-otp').text('Please complete the OTP');
                $('.help-block-otp').addClass('text-danger');
                isValid = false;
            } else {
                $('.help-block-otp').text('');
                $('.help-block-otp').removeClass('text-danger');
            }

            return isValid;
        }
        // Verify the OTP and update the email
        const saveButtonForVerifyEmail = document.getElementById('SaveAccountInformation');
        saveButtonForVerifyEmail.addEventListener("click", function(event) {
            event.preventDefault();

            if (!ValidateOTP()) {
                return;
            }

            let inputs = document.querySelectorAll('.otp-input');
            let otp = Array.from(inputs).map(input => input.value).join('');
            let email = document.getElementById('newEmail').value;

            let data = {
                otp: otp,
                email: email
            };

            $.ajax({
                url: '/worker/update-email-worker',
                type: 'POST',
                data: data,
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + resp.message,
                            time: 5
                        });
                    } else {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-times"></i> ' + resp.message,
                            time: 5
                        });
                    }
                },
                error: function() {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-times"></i> Please try again later!',
                        time: 5
                    });
                }
            });
        });


        // this functions to display profile setting / account setting forms
        function AccountSettingDisplay() {
            $('.profile_setting').addClass('d-none');
            $('.account_setting').removeClass('d-none');
            $('.bonus_transfers').addClass('d-none');
            $('.disable_account').addClass('d-none');
        }

        function ProfileIinformationDisplay() {
            $('.account_setting').addClass('d-none');
            $('.profile_setting').removeClass('d-none');
            $('.bonus_transfers').addClass('d-none');
            $('.support_info').addClass('d-none');
            $('.disable_account').addClass('d-none');

        }

        function BonusTransfersDisplay() {
            $('.account_setting').addClass('d-none');
            $('.profile_setting').addClass('d-none');
            $('.bonus_transfers').removeClass('d-none');
            $('.support_info').addClass('d-none');
            $('.disable_account').addClass('d-none');
        }

        function SupportDisplay() {
            $('.account_setting').addClass('d-none');
            $('.profile_setting').addClass('d-none');
            $('.bonus_transfers').addClass('d-none');
            $('.support_info').removeClass('d-none');
            $('.disable_account').addClass('d-none');
        }

        function DisactivateAccountDisplay() {
            $('.account_setting').addClass('d-none');
            $('.profile_setting').addClass('d-none');
            $('.bonus_transfers').addClass('d-none');
            $('.support_info').addClass('d-none');
            $('.disable_account').removeClass('d-none');
        }

        function loadFile(event) {
            var image = document.getElementById("output");
            image.src = URL.createObjectURL(event.target.files[0]);

            // seding the image to server
            var formData = new FormData();
            formData.append('profile_pic', $('#file')[0].files[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/worker/update-worker-profile-picture',
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(resp) {
                    //console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Profile picture updated successfully.',
                            time: 5
                        });

                        setTimeout(function() {
                            location.reload();
                        }, 2000);

                    }

                },
                error: function(resp) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i>' + resp.message,
                        time: 5
                    });
                }
            });
        };

        function refreshDocList(worker_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('list-docs') }}',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    WorkerId: worker_id
                }),
                success: function(resp) {
                    var data;
                    try {
                        // Try to manually parse the response as JSON
                        data = JSON.parse(resp);
                        //console.log(data);
                    } catch (e) {
                        // If parsing fails, assume resp is already a JavaScript object
                        data = resp;
                    }

                    var tbody = $('.table tbody');
                    tbody.empty();
                    data.forEach(function(file) {
                        var row = $('<tr>');
                        row.attr('class', 'row');
                        //row.append($('<td class="col-3 td-table" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">').text(file.name.substring(0,20)));
                        row.append($('<td class="col-3 td-table">').text(file.displayName));
                        row.append($('<td class="col-3 td-table">').text(file.type));
                        //console.log(file.id);
                        var deleteButton = $('<button>').text('Delete Document').addClass(
                            'delete').attr('data-id', file.id);
                        deleteButton.click(function(event) {
                            event.preventDefault();
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                url: '{{ route('del-doc') }}',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                success: function() {
                                    row.remove();
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });
                        var viewFile = $('<button>').text('View Document').addClass('delete')
                            .attr('data-id', file.id);
                        viewFile.click(function(event) {
                            event.preventDefault();
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                url: '{{ route('get-doc') }}',
                                method: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    bsonId: file.id
                                }),
                                success: function(resp) {
                                    resp = JSON.parse(resp);
                                    const base64String = resp.content.data;
                                    //console.log("the resp base64",resp);

                                    const mimeType = base64String.match(
                                        /^data:(.+);base64,/)[1];


                                    const base64Data = base64String.split(
                                        ',')[1];


                                    const byteCharacters = atob(base64Data);
                                    const byteNumbers = new Array(
                                        byteCharacters.length);
                                    for (let i = 0; i < byteCharacters
                                        .length; i++) {
                                        byteNumbers[i] = byteCharacters
                                            .charCodeAt(i);
                                    }
                                    const byteArray = new Uint8Array(
                                        byteNumbers);


                                    const blob = new Blob([byteArray], {
                                        type: mimeType
                                    });


                                    const blobUrl = URL.createObjectURL(
                                        blob);
                                    const downloadLink = document
                                        .createElement('a');
                                    downloadLink.href = blobUrl;


                                    const extension = mimeType.split('/')[
                                        1
                                    ];
                                    downloadLink.setAttribute('download',
                                        file.name
                                    );

                                    document.body.appendChild(downloadLink);
                                    downloadLink.click();
                                    document.body.removeChild(
                                        downloadLink);
                                },
                                error: function(resp) {
                                    console.log('Error:', resp);
                                }
                            });
                        });


                        row.append($('<td class="col-3 td-table">').append(viewFile));
                        row.append($('<td class="col-3 td-table">').append(deleteButton));

                        tbody.append(row);
                    });
                },
                error: function(resp) {
                    console.log('Error:', resp);
                }
            });
        }
    </script>
@stop

<style>
    .file-name {
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;

    }

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
        width: fit-content;
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
        background: #;
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

    .btns_prof_info {
        width: 100% !important;
    }

    .slide-page span {
        margin-top: 10px;
    }

    #loading,
    #send_ticket,
    #loadSpan,
    #disactivate_account,
    #loading_disableOption,
    #loadSpan_disableOption {
        color: #fff;
    }

    /* for the image  */

    .profile-pic {
        color: transparent;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        transition: all .3s ease;
    }

    .profile-pic input {
        display: none;
    }

    .profile-pic img {
        position: absolute;
        object-fit: cover;
        width: 165px;
        height: 165px;
        box-shadow: 0 0 10px 0 rgba(255, 255, 255, .35);
        border-radius: 100px;
        z-index: 0;
    }

    .profile-pic .-label {
        cursor: pointer;
        height: 165px;
        width: 165px;
    }

    .profile-pic:hover .-label {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, .8);
        z-index: 10000;
        color: rgb(250, 250, 250);
        transition: background-color .2s ease-in-out;
        border-radius: 100px;
        margin-bottom: 0;
    }

    .profile-pic span {
        display: inline-flex;
        padding: .2em;
        height: 2em;
    }

    .td-table {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    .modal-content {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.8) !important;
    }
</style>

{{-- style for multi-select --}}

<style>
    /* Google Fonts - Poppins*/
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');


    .container-multiselect {
        position: relative;
        max-width: 320px;
        width: 100%;
        margin: 30px auto 30px;
    }

    .select-btn {
        display: flex;
        height: 50px;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .select-btn .btn-text {
        font-size: 17px;
        font-weight: 400;
        color: #333;
    }

    .select-btn .arrow-dwn {
        display: flex;
        height: 21px;
        width: 21px;
        color: #fff;
        font-size: 14px;
        border-radius: 50%;
        background: #3d2c39;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .select-btn.open .arrow-dwn {
        transform: rotate(-180deg);
    }

    .list-items {
        position: relative;
        margin-top: 15px;
        border-radius: 8px;
        padding: 16px;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        display: none;
        max-height: 500px;
        scroll-behavior: auto;
        overflow: auto;

    }

    .select-btn.open~.list-items {
        display: block;
    }

    .list-items .item,
    .list-items .item-elem {
        display: flex;
        align-items: center;
        list-style: none;
        height: 50px;
        cursor: pointer;
        transition: 0.3s;
        padding: 0 15px;
        border-radius: 8px;
    }

    .list-items .item:hover,
    .list-items .item-elem:hover {
        background-color: #e7edfe;
    }

    .item .item-text {
        font-size: 16px;
        font-weight: 400;
        color: #333;
    }

    .item .checkbox {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 16px;
        width: 16px;
        border-radius: 4px;
        margin-right: 12px;
        border: 1.5px solid #c0c0c0;
        transition: all 0.3s ease-in-out;
    }

    .item.checked .checkbox {
        background-color: #3d2c39;
        border-color: #3d2c39;
    }

    .checkbox .check-icon {
        color: #fff;
        font-size: 11px;
        transform: scale(0);
        transition: all 0.2s ease-in-out;
    }

    .item.checked .check-icon {
        transform: scale(1);
    }

    .ss-job-dtl-pop-sv-btn {
        margin-top: 30px !important;
    }

    .remove-file {
        cursor: pointer;
        color: white;
        background-color: #3d2c39;
        border-radius: 8px;
        padding: 0px !important;
        font-size: 12px;
    }

    .file-name {
        margin-top: 10px;
        padding: 0px;
    }

    /* OTP page css  */
    ul.ss-otp-v-ul {
        list-style: none;
        width: 100%;
    }

    ul.ss-otp-v-ul li {
        width: 19%;
        margin: 0 7px;
        display: inline-block;
    }

    ul.ss-otp-v-ul input {
        border: 2px solid #111011;
        box-shadow: 8px 8px 0px 0px #403B4BE5;
        padding: 12px 15px;
        border-radius: 10px;
        width: 100%;
    }
</style>

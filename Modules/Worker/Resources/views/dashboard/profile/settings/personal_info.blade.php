<div class="col-md-12 mb-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-8" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Identity & Tax Info
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-8">
    <div class="row justify-content-center">
        
        {{-- nurse_classification --}}
        <div class="ss-form-group">
            <label>Classifications</label>
            <select name="nurse_classification" id="nurse_classification">

                <option value="" {{ empty($worker->nurse_classification) ? 'selected' : '' }} disabled hidden>
                    Select Nurse Classification
                </option>
                @if(!empty($allKeywords['NurseClassification']))
                    @foreach ($allKeywords['NurseClassification'] as $value)
                        <option value="{{ $value->title }}"
                            {{ !empty($worker->nurse_classification) && $worker->nurse_classification == $value->title ? 'selected' : '' }}>
                            {{ $value->title }}
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>No classifications available</option>
                @endif
            </select>
            <span class="help-block-nurse_classification"></span>
        </div>
        {{-- End nurse_classification  --}}

        {{-- First Name --}}
        <div class="ss-form-group">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="Please enter your first name"
                value="{{ isset($user->first_name) ? $user->first_name : '' }}">
        </div>
        <span class="help-block-first_name"></span>

        {{-- Last Name --}}
        <div class="ss-form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Please enter your last name"
                value="{{ isset($user->last_name) ? $user->last_name : '' }}">
        </div>
        <span class="help-block-last_name"></span>

        {{-- State Information --}}
        <div class="ss-form-group">
            <label>State</label>
            <select name="state" id="job_state">
                <option value="{{ !empty($worker->state) ? $worker->state : '' }}" disabled selected hidden>
                    {{ !empty($worker->state) ? $worker->state : 'What State are you located in?' }}
                </option>
                @foreach ($states as $state)
                    <option id="{{ $state->id }}" value="{{ $state->name }}">
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <span class="help-block-state"></span>
        
        {{-- City Information --}}
        <div class="ss-form-group">
            <label>City</label>
            <select name="city" id="job_city">
                <option value="{{ !empty($worker->city) ? $worker->city : '' }}" disabled selected hidden>
                    {{ !empty($worker->city) ? $worker->city : 'What City are you located in?' }}
                </option>
            </select>
        </div>
        <span class="help-block-city"></span>
        <span class="help-city">Please select a state first</span>

        {{-- Zip Code Information --}}
        <div class="ss-form-group">
            <label>Zip Code</label>
            <input type="number" name="zip_code" placeholder="Please enter your Zip Code"
                value="{{ isset($user->zip_code) ? $user->zip_code : '' }}">
        </div>
        <span class="help-block-zip_code"></span>

        {{-- Email --}}
        <div class="row w-100 d-flex align-items-center ">
            <div class="col-11">
                <div class="ss-form-group">
                    <label>New Email</label>
                    <input type="text" name="newEmail" id="newEmail"
                        placeholder="Please enter your new Email">
                </div>
            </div>
            <div class="col-1 h-100 d-flex align-items-end justify-content-center">
                <div class="ss-form-group">
                    <button type="button" class="col-11 w-100 ss-prsnl-save-btn rounded-5"
                    id="sendOTPforVerifyEmail">
                    Send OTP
                </button>
                </div>
            </div>
        </div>
        <span class="help-block-email"></span>
        {{-- OTP for new email --}}
        <div id="otpDiv" style="display:none;">
            <center>
                <div class="ss-form-group col-7 d-flex align-items-center justify-content-center" >
                    <label class="me-3">Code:</label>
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
            </center>
        </div>
        <span class="help-block-otp"></span>
        {{-- confirm new email button --}}
        <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
            <button type="button" class="col-12 ss-prsnl-save-btn"
                id="SaveAccountInformation" style="display:none;">Confirm</button>
        </div>

        {{-- Phone Number --}}
        <div class="ss-form-group">
            <label>Phone Number</label>
            <input id="mobile" type="text" name="mobile" placeholder="Please enter your phone number"
                value="{{ isset($user->mobile) ? $user->mobile : '' }}">
        </div>
        <span class="help-block-mobile"></span>
        {{-- Address Information --}}
        <div class="ss-form-group">
            <label>Permanent Tax Home (Address)</label>
            <input type="text" name="address" placeholder="Please enter your address"
                value="{{ isset($worker->address) ? $worker->address : '' }}">
        </div>
        <span class="help-block-address"></span>

    </div>
</div>

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

    <script type="text/javascript">

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
        

        // send otp button
        const sendOTPButton = document.getElementById('sendOTPforVerifyEmail');
        sendOTPButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (!validateAccountSettingInformation()) {
                return;
            }

            // undide the otp input fields
            let OtpDiv = document.getElementById('otpDiv');
            OtpDiv.style.display = OtpDiv.style.display === "none" ? "block" : "none";

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
                console.log(otp.length);
                console.log(inputs.length);
                
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
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
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


       
    </script>
@stop
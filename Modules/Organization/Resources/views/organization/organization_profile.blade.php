@extends('organization::layouts.main')
@section('mytitle', 'My Profile')
@section('content')
    @php
        $user = auth()->guard('organization')->user();

    @endphp
    <!--Main layout-->
    <main style="padding-top: 130px; padding-bottom: 100px;" class="ss-main-body-sec">
        <div class="container">
            <div class="ss-my-profile--basic-mn-sec">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-my-profil-div">
                            <h2>My <span class="ss-pink-color">Profile</span></h2>
                            {{-- <div class="ss-my-profil-img-div">
                                <img src="{{ URL::asset('frontend/img/account-img.png') }}"
                                    onerror="this.onerror=null;this.src='{{ USER_IMG }}';" id="preview"
                                    width="112px" height="112px" style="object-fit: cover;" />
                                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                                <p>{{ $user->id }}</p>
                                <p>{{ $user->about_me }}</p>
                            </div> --}}

                            <div class="ss-my-profil-img-div">
                                <div class="profile-pic">
                                    <label class="-label" for="file">
                                      <span class="glyphicon glyphicon-camera"></span>
                                      <span>Change Image</span>
                                    </label>
                                    <input id="file" type="file" onchange="loadFile(event)"/>
                                    <img src="{{ asset('uploads/' . $user->image) }}" id="output" width="200" onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/account-img.png') }}';"/>
                                  </div>
                                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                                <p><b>{{ $user->organization_name }}</b></p>
                                <p>{{ $user->id }}</p>
                                <p>{{ $user->about_me }}</p>
                            </div>




                            <div class="ss-my-presnl-btn-mn">

                                <div class="ss-my-prsnl-wrapper">
                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-1" checked
                                            onclick="ProfileIinformationDisplay()" />
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
                                        <input type="radio" name="select" id="option-2"
                                            onclick="AccountSettingDisplay()">
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
                                        <input type="radio" name="select" id="option-3"
                                            onclick="BonusTransfersDisplay()">
                                        <label for="option-3" class="option option-3">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-refren.png') }}" />
                                                </li>
                                                <li>
                                                    <p>Billing</p>
                                                </li>
                                                <li><span class="img-white"><img
                                                            src="{{ URL::asset('frontend/img/arrowcircleright.png') }}" /></span>
                                                </li>
                                            </ul>
                                        </label>
                                    </div>

                                    <div class="ss-my-prosnl-rdio-btn">
                                        <input type="radio" name="select" id="option-4" onclick="SupportDisplay()">
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
                                        <input type="radio" name="select" id="option-5"
                                            onclick="DisactivateAccountDisplay()">
                                        <label for="option-5" class="option option-5">
                                            <div class="dot"></div>
                                            <ul>
                                                <li><img src="{{ URL::asset('frontend/img/my-per--con-key.png') }}" />
                                                </li>
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

                    {{-- ---------------------------------------------------------- Profile settings Form ---------------------------------------------------------- --}}
                    <div class="col-lg-7 bodyAll profile_setting">
                        <div class="ss-pers-info-form-mn-dv">

                            <div class="ss-persnl-frm-hed">
                                {{-- Basic Information Or Professional Information Or Document management --}}
                                <p id="information_type"><span><img
                                            src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Basic
                                    Information</p>

                            </div>
                            <div class="form-outer">
                                <form method="post" action="{{ route('update-organization-profile') }}">
                                    {{-- <form> --}}
                                    @csrf
                                    <!-- first form slide Basic Information -->
                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Organization Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Organization Name</label>
                                                <input type="text" name="organization_name"
                                                placeholder="Please enter your organization name"
                                                value="{{ isset($user->organization_name) ? $user->organization_name : '' }}">
                                            </div>
                                            <span class="help-block-org_name"></span>
                                            {{-- First Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>First Name</label>
                                                <input type="text" name="first_name"
                                                    placeholder="Please enter your first name"
                                                    value="{{ isset($user->first_name) ? $user->first_name : '' }}">
                                            </div>
                                            <span class="help-block-first_name"></span>
                                            {{-- Last Name --}}
                                            <div class="ss-form-group col-11">
                                                <label>Last Name</label>
                                                <input type="text" name="last_name"
                                                    placeholder="Please enter your last name"
                                                    value="{{ isset($user->last_name) ? $user->last_name : '' }}">
                                            </div>
                                            <span class="help-block-last_name"></span>
                                            {{-- Phone Number --}}
                                            <div class="ss-form-group col-11">
                                                <label>Phone Number</label>
                                                <input id="contact_number" type="text" name="mobile"
                                                    placeholder="Please enter your phone number"
                                                    value="{{ isset($user->mobile) ? $user->mobile : '' }}">
                                            </div>
                                            <span class="help-block-mobile"></span>



                                            {{-- About Me Information --}}
                                            <div class="ss-form-group col-11">
                                                <label>About Me</label>
                                                <textarea type="text" name="about_me">{{ isset($user->about_me) ? $user->about_me : '' }}</textarea>
                                            </div>
                                            <span class="help-block-about_me"></span>
                                            {{-- Skip && Save --}}
                                            <div class="ss-prsn-form-btn-sec col-11 row d-flex justify-content-center">
                                                <button type="text" class="ss-prsnl-save-btn"
                                                    id="SaveBaiscInformation"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end first form slide Basic Information -->
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- ---------------------------------------------------------- End Profile settings Form ---------------------------------------------------------- --}}
                    {{-- ---------------------------------------------------------- Account settings Form ---------------------------------------------------------- --}}

                    <div class="col-lg-7 bodyAll account_setting d-none">
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
                                            <input type="text" name="email" id="email" placeholder="Please enter your new Email">
                                        </div>
                                        <button type="button" class="mt-3 col-11 w-50 ss-prsnl-save-btn rounded-5" id="sendOTPforVerifyEmail">
                                            Send OTP
                                        </button>
                                        <span class="help-block-email"></span>
                                    
                                        {{-- OTP for new email --}}
                                        <div class="ss-form-group col-7 d-flex align-items-center">
                                            <label class="me-3">OTP:</label>
                                            {{-- <input type="text" name="otp" id="otp" placeholder="Please check your email for OTP"> --}}
                                            <ul class="ss-otp-v-ul">
                                                <li><input class="otp-input" type="text" name="otp1" oninput="digitValidate(this)" onkeyup="tabChange(1)" maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp2" oninput="digitValidate(this)" onkeyup="tabChange(2)" maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp3" oninput="digitValidate(this)" onkeyup="tabChange(3)" maxlength="1"></li>
                                                <li><input class="otp-input" type="text" name="otp4" oninput="digitValidate(this)" onkeyup="tabChange(4)" maxlength="1"></li>
                                            </ul>
                                            
                                        </div>
                                        <span class="help-block-otp"></span>
                                    
                                        <div class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                            <button type="button" class="col-12 ss-prsnl-save-btn" id="SaveAccountInformation" style="display:none;">Save</button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- ---------------------------------------------------------- End Account settings  ---------------------------------------------------------- --}}
                    {{-- ----------------------------------------------------------  Bonus Area -------------------------------------------------------------------- --}}
                    <div class="col-lg-7 bodyAll bonus_transfers d-none">
                        <div class="ss-pers-info-form-mn-dv" style="width: 100% !important">
                            <div class="ss-persnl-frm-hed">
                                <p><span><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>
                                    Billing</p>
                            </div>
                            <div class="form-outer">

                                @if ($user->stripeAccountId)
                                
                                    <form method="post">
                                        @csrf
                                        <!-- slide Bonus Transfer -->
                                        <div class="page slide-page">
                                            <div class="row justify-content-center">
                                                {{-- Skip && Save --}}
                                                <div
                                                    class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                    <button type="text" class=" col-12 ss-prsnl-save-btn" id="AddStripe">
                                                        Add Stripe
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                @else 

                                    <div class="page slide-page">
                                        <div class="row justify-content-center">
                                            {{-- Skip && Save --}}
                                            <div
                                                class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <a type="text" class="btn col-12 ss-prsnl-save-btn" id="connectStripe" target="_blank" href="{{ Config::get('app.portal_link') }}">
                                                    Connect to Stripe 
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                            </div>

                        </div>
                    </div>
                    {{-- ----------------------------------------------------------  End Bonus Area -------------------------------------------------------------------- --}}
                    {{-- ----------------------------------------------------------  Support Area -------------------------------------------------------------------- --}}
                    <div class="col-lg-7 bodyAll support_info d-none">
                        <div class="ss-pers-info-form-mn-dv" style="width:100%">
                            <div class="ss-persnl-frm-hed">
                                <h1
                                    style="font-family: Neue Kabel; font-size: 32px; font-weight: 500; line-height: 34px; text-align: center;color:3D2C39;">
                                    Help & Support
                                </h1>
                            </div>
                            <div class="form-outer">
                                {{-- <form method="post">
                                    @csrf

                                    <div class="page slide-page">
                                        <div class="row justify-content-center">

                                            <div class="ss-form-group col-11">
                                                <label>Subject</label>
                                                <select name="support_subject" id="support_subject">
                                                    <option value="">Please select your issue</option>
                                                    <option value="login">Login</option>
                                                    <option value="payment">Payment</option>
                                                    <option value="other">Other</option>
                                                </select>

                                            </div>
                                            <span class="help-block-support_subject"></span>

                                            <div class="ss-form-group col-11">
                                                <label>Issue</label>
                                                <textarea style="width: 100%; height:40vh;" name="support_subject_issue" placeholder="Tell us how can we help."></textarea>
                                            </div>
                                            <span class="help-block-support_subject_issue"></span>

                                            <div
                                                class="ss-prsn-form-btn-sec row col-11 d-flex justify-content-center align-items-center">
                                                <button type="text" class=" col-12 ss-prsnl-save-btn"
                                                    id="SaveSupportTicket">

                                                    <span id="loading" class="d-none">
                                                        <span id="loadSpan" class="spinner-border spinner-border-sm"
                                                            role="status" aria-hidden="true"></span>
                                                        Loading...
                                                    </span>
                                                    <span id="send_ticket">Send now</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form> --}}
                                <p style="
                                margin-top: 20px;
                            ">Please contact us at <span style="font-weight: 500">support@goodwork.com</span></p>
                            </div>

                        </div>
                    </div>
                    {{-- ------------------------------------------------------- End Support Area -------------------------------------------------------------------- --}}

                    {{-- ------------------------------------------------------- Disable account area -------------------------------------------------------------------- --}}
                    <div class="col-lg-7 bodyAll disable_account d-none">
                        <div class="ss-pers-info-form-mn-dv">
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
   let tabChange = function (val) {
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

let digitValidate = function (ele) {
    ele.value = ele.value.replace(/[^0-9]/g, ""); // Allow only digits
};






</script>


    <script type="text/javascript">
        // loding states cities docs on page load

        $(document).ready(function() {

            if (@json($type == 'profile')) {
                document.getElementById('option-1').checked = true;
                ProfileIinformationDisplay();

            } else {
                document.getElementById('option-2').checked = true;
                AccountSettingDisplay();
            }

            $('#contact_number').mask('+1 (999) 999-9999');
            $('#new_contact_number').mask('+1 (999) 999-9999');


        });



        // end loding states cities docs on page load


        // inputs
        // Basic Info
        const organization_name = document.querySelector('input[name="organization_name"]');
        const first_name = document.querySelector('input[name="first_name"]');
        const last_name = document.querySelector('input[name="last_name"]');
        const mobile = document.querySelector('input[name="mobile"]');
        const about_me = document.querySelector('textarea[name="about_me"]');
        // support input
        const support_subject_issue = document.querySelector('textarea[name="support_subject_issue"]');
        const support_subject = document.querySelector('select[name="support_subject"]');

        // send amount inputs
        const amount = document.querySelector('input[name="amount"]');
        const email_payment = document.querySelector('input[name="email_payment"]');
        const AddStripe = document.getElementById("AddStripe");
        // end inputs



        var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;

        // validation basic information -ELH-
        function validateBasicInfo() {

            let isValid = true;

            if (organization_name.value === '') {
                $('.help-block-org_name').text('Please enter a organization name');
                $('.help-block-org_name').addClass('text-danger');
                isValid = false;
            }
            if (first_name.value === '') {
                $('.help-block-first_name').text('Please enter a first name');
                $('.help-block-first_name').addClass('text-danger');
                isValid = false;
            }
            if (last_name.value === '') {
                $('.help-block-last_name').text('Please enter a last name');
                $('.help-block-last_name').addClass('text-danger');
                isValid = false;
            }

            // if ( mobile.value === '' ) {

            //     // don't do anything

            // }else if ( !regexPhone.test(mobile) && mobile.value !== '' ) {

            //     $('.help-block-mobile').text('Please enter a mobile number');
            //     $('.help-block-mobile').addClass('text-danger');
            //     isValid = false;

            // }

            // if (about_me.value === '') {
            //     $('.help-block-about_me').text('Please enter a description');
            //     $('.help-block-about_me').addClass('text-danger');
            //     isValid = false;
            // }

            return isValid;
        }
        // end validation basic information


        // validation amount transfer





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
            }


            if ($('input[name="address_payment"]').val() === '') {
                $('.help-block-address_payment').text('Please enter your address');
                $('.help-block-address_payment').addClass('text-danger');
                isValid = false;
            }

            const emailRegex_payment = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (($('input[name="email_payment"]').val() === '') && (!emailRegex_payment.test(email_payment.value))) {
                $('.help-block-email_payment').text('Please enter a valid email');
                $('.help-block-email_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="bank_name_payment"]').val() === '') {
                $('.help-block-bank_name_payment').text('Please enter your bank name');
                $('.help-block-bank_name_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="routing_number_payment"]').val() === '') {
                $('.help-block-routing_number_payment').text('Please enter your routing number');
                $('.help-block-routing_number_payment').addClass('text-danger');
                isValid = false;
            }

            if ($('input[name="bank_account_payment_number"]').val() === '') {
                $('.help-block-bank_account_payment_number').text('Please enter your bank account number');
                $('.help-block-bank_account_payment_number').addClass('text-danger');
                isValid = false;
            }
            const regexPhone_payment = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            if (($('input[name="phone_number_payment"]').val() === '') && (!regexPhone_payment.test(phone_number_payment
                    .value))) {
                $('.help-block-phone_number_payment').text('Please enter a valid phone number');
                $('.help-block-phone_number_payment').addClass('text-danger');
                isValid = false;
            }

            return isValid;
        }

        // end bonus validation

        // validation

        function validateSupportForm() {
            let isValid = true;

            // Support subject validation
            if ($('select[name="support_subject"]').val() === '') {
                $('.help-block-support_subject').text('Please select your issue');
                $('.help-block-support_subject').addClass('text-danger');
                isValid = false;
            }

            // Support issue validation
            if ($('textarea[name="support_subject_issue"]').val().trim() === '') {
                $('.help-block-support_subject_issue').text('Please tell us how we can help');
                $('.help-block-support_subject_issue').addClass('text-danger');
                isValid = false;
            }

            return isValid;
        }

        // end validation

        // Save Basic Information
        const SaveBaiscInformation = document.getElementById("SaveBaiscInformation");

        SaveBaiscInformation.addEventListener("click", function(event) {

            event.preventDefault();

            if (!validateBasicInfo()) {
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let formData = new FormData();
            formData.append('organization_name', organization_name.value);
            formData.append('first_name', first_name.value);
            formData.append('last_name', last_name.value);
            formData.append('mobile', mobile.value);
            formData.append('about_me', about_me.value);
            formData.append('profile_pic', $('#file')[0].files[0]);



            $.ajax({
                url: '/organization/update-organization-profile',
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Account Information saved successfully',
                            time: 5
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }else{
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa-solid fa-xmark"></i>' + resp.message,
                            time: 5
                        });
                    }
                },
                error: function(resp) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i>' + "resp.message",
                        time: 5
                    });
                }
            });

        });
        // end Saving Basic Information


        // saving amount transfer

        AddStripe.addEventListener("click", function(event) {
            event.preventDefault();

            $('#loading').removeClass('d-none');
            $('#send_ticket').addClass('d-none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/organization/check-stripe',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true,
                }),
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Redirecting ...',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        window.location.href = resp.portal_link;
                    } else {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Client Exists',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        // window.location.href = resp.portal_link;
                    }
                }
            });
        });

        



        // saving Support ticket

        // const SaveSupportTicket = document.getElementById("SaveSupportTicket");

        // SaveSupportTicket.addEventListener("click", function(event) {
        //     event.preventDefault();
        //     if (!validateSupportForm()) {
        //         return;
        //     }
        //     $('#loading').removeClass('d-none');
        //     $('#send_ticket').addClass('d-none');
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: '/organization/send-support-ticket',
        //         type: 'POST',
        //         dataType: 'json',
        //         contentType: 'application/json',
        //         data: JSON.stringify({
        //             support_subject: support_subject.value,
        //             support_subject_issue: support_subject_issue.value,

        //         }),
        //         success: function(resp) {
        //             console.log(resp);
        //             if (resp.status) {
        //                 notie.alert({
        //                     type: 'success',
        //                     text: '<i class="fa fa-check"></i> Your ticket has been sent successfully',
        //                     time: 5
        //                 });
        //                 $('#loading').addClass('d-none');
        //                 $('#send_ticket').removeClass('d-none');
        //                 support_subject_issue.value = "";
        //             }
        //         },
        //         error: function(resp) {
        //             console.log(resp);
        //             notie.alert({
        //                 type: 'error',
        //                 text: resp,
        //                 time: 5
        //             });
        //         }
        //     });
        // });

        // end saving support ticket

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
                url: '/organization/disactivate-account',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true
                }),
                success: function(resp) {
                    console.log(resp);
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
                    console.log(resp);
                    notie.alert({
                        type: 'error',
                        text: resp,
                        time: 5
                    });
                }
            });
        });

        // end account disactivating



        // --------------------------- Account Setting Area --------------------------------- //

        // inputs account settings

        // const user_name = document.querySelector('input[name="user_name"]');
        // const password = document.querySelector('input[name="password"]');
        // const new_mobile = document.querySelector('input[name="new_mobile"]');
        // const twoFactorAuth = document.querySelector('input[name="twoFa"]:checked');
        const email = document.querySelector('input[name="email"]');
        var inputs = [];
        
        // account setting validation here

        function validateAccountSettingInformation() {
            //$('.help-block-new_mobile').text('');
            $('.help-block-validation').text('');
            $('.help-block-email').text('');
            $('.help-block-user_name').text('');
            let isValid = true;
            // Create an array of all inputs
            inputs = [email ];

            // Add the value of the selected radio button to the inputs array, if a radio button is selected
            const twoFactorAuth = document.querySelector('input[name="twoFa"]:checked');
            if (twoFactorAuth) {
                inputs.push(twoFactorAuth);
            }

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

            // User name validation
            // const userNameRegex = /^[a-zA-Z\s]{1,255}$/;
            // if (!userNameRegex.test(user_name.value)) {
            //     $('.help-block-user_name').text(
            //         'User name can only contain letters and spaces, and cannot be longer than 255 characters');
            //     $('.help-block-user_name').addClass('text-danger');
            //     isValid = false;
            // }

           

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
        //         url: '/organization/update-organization-account-setting',
        //         type: 'POST',
        //         processData: false,
        //         contentType: false,
        //         data: formData,
        //         success: function(resp) {
        //             console.log(resp);
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

                console.log();
                
                return;
            }
            let email = document.getElementById('email').value;

            //let url = "{{ route('sendOtp') }}";
            let data = {
                email: email
            };
            $.ajax({
                url: '/organization/send-otp',

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
        saveButtonForVerifyEmail.addEventListener("click", function (event) {
            event.preventDefault();
        
            if (!ValidateOTP()) {
                return;
            }
        
            let inputs = document.querySelectorAll('.otp-input');
            let otp = Array.from(inputs).map(input => input.value).join('');
            let email = document.getElementById('email').value;
        
            let data = {
                otp: otp,
                email: email
            };
        
            $.ajax({
                url: '/organization/update-email',
                type: 'POST',
                data: data,
                success: function (resp) {
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
                error: function () {
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

        var loadFile = function (event) {
  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);

  // sending image to server

    var formData = new FormData();
    formData.append('profile_pic', $('#file')[0].files[0]);

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/organization/update-organization-profile-image',
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (resp) {
            console.log(resp);
            if (resp.status) {
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> Account Information saved successfully',
                    time: 5
                });

            }
        },
        error: function (resp) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i>' + resp.message,
                time: 5
            });
        }
    });

};

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
  box-shadow: 0 0 10px 0 rgba(255,255,255,.35);
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
  background-color: rgba(0,0,0,.8);
  z-index: 10000;
  color: rgb(250,250,250);
  transition: background-color .2s ease-in-out;
  border-radius: 100px;
  margin-bottom: 0;
}

.profile-pic span {
  display: inline-flex;
  padding: .2em;
  height: 2em;
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


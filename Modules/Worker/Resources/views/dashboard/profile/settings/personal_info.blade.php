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
        {{-- <div class="ss-form-group">
            <label>Email</label>
            <input id="email" type="text" name="email" placeholder="Please enter your Email"
                value="{{ isset($user->email) ? $user->email : '' }}">
        </div> --}}
        <div class="ss-form-group">
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
        id="SaveAccountInformation" >Save</button>
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
            //saveButton.style.display = allFilled ? "block" : "none"; // Show or hide the Save button
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
@extends('organization::layouts.main')

@section('content')
    @php
        $faker = app('Faker\Generator');
    @endphp
    <main style="padding-top: 130px; margin:0 50px;" class="ss-main-body-sec">
        <div class="row">
            <div class="col-12 recruiters-manage-tl">
                <div class="ss-dash-wel-div">
                    <h5><span class="title-span">Recruiters</span> Management</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 add-recruiters-btn">

                <a href="#" data-target="input" onclick="open_modal('#input_modal')" class="ss-opr-mngr-plus-sec"><i
                        class="fas fa-plus"></i></a>
            </div>
        </div>
        <form method="post" action="{{ route('getApiKey') }}">
            @csrf
            <table style="font-size: 16px;" class="table">
                <thead>
                    <tr>
                        <th scope="col">Filds</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Last Active</th>
                        <th scope="col">Invited At</th>
                        <th scope="col"># Of Works</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recruiters as $item)
                        <tr>
                            <td>{{ $item->first_name }}</td>
                            <td>{{ $item->last_name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ isset($item->last_login_at) ?  $item->last_login_at : 'NA'}}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->assignedJobs()->count() }}</td>
                            <td style="text-align: center">
                                <button type="button" onclick="open_modal('#edit_modal'), get_recruiter_data({{$item}})" class="delete" data-id="{{ $item->id }}">Edit Rceruiter</button>
                                <button type="button" id="delete" class="delete" data-id="{{ $item->id }}">Delete Rceruiter</button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="add-recruiters-btn">
                <button type="submit" name="action" value="save" id="save_key" class="save">Save changes</button>
            </div>
        </form>

        {{-- registration modal --}}
        <div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="ss-pop-cls-vbtn">
                        <button type="button" class="btn-close" data-target="#input_modal" onclick="close_modal('#input_modal')"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ss-job-dtl-pop-form">

                            <!--------login form------->
                            <div class="ss-login-work-dv">
                                <h4><span>Recruiter </span> Registration</h4>
                                <form onsubmit="return false;" class="" method="post"
                                    action="{{ route('recruiter_registration') }}" id="signup-form-submit">
                                    <div class="ss-form-group">
                                        <input type="text" name="first_name" placeholder="First Name" required><br />
                                        <span class="help-block-first-name"></span>
                                    </div>

                                    <div class="ss-form-group">
                                        <input type="text" name="last_name" placeholder="Last Name" required /><br />
                                        <span class="help-block-last-name"></span>
                                    </div>
                                    <div class="ss-form-group">
                                        <input type="email" name="email" placeholder="Email" required><br />
                                        <span class="help-block-email"></span>
                                    </div>
                                    <div class="ss-form-group" style="text-align: center;">
                                        <input style="margin-bottom: 5px;" type="tel" id="contact_number" name="mobile"
                                            placeholder="Mobile" >
                                        <span id="passwordHelpInline" class="form-text">
                                            (Mobile not required)
                                        </span><br>
                                        <span class="help-block-mobile"></span>
                                    </div>
                                    <div>
                                        <button id="submitBtn" class="save" style="width: 100%;">
                                            <span id="loading" class="d-none">
                                                <span id="loadSpan" class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </span>
                                            <span id="sign"> Save Recruiter </span> </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         {{-- editing modal --}}
         <div class="modal fade ss-jb-dtl-pops-mn-dv" id="edit_modal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog modal-sm modal-dialog-centered">
             <div class="modal-content">
                 <div class="ss-pop-cls-vbtn">
                     <button type="button" class="btn-close" data-target="#edit_modal" onclick="close_modal('#edit_modal')"
                         aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="ss-job-dtl-pop-form">

                         <!--------login form------->
                         <div class="ss-login-work-dv">
                             <h4><span>Recruiter </span> Edit</h4>
                             <form onsubmit="return false;" class="" method="post"
                                 action="{{ route('recruiter_edit') }}" id="signup-form-submit">
                                 <div class="ss-form-group">
                                     <input id="first_name_edit" type="text" name="first_name" placeholder="First Name" required><br />
                                     <span class="help-block-first-name"></span>
                                 </div>

                                 <div class="ss-form-group">
                                     <input id="last_name_edit" type="text" name="last_name" placeholder="Last Name" required /><br />
                                     <span class="help-block-last-name"></span>
                                 </div>
                                 <div class="ss-form-group">
                                     <input id="email_edit" type="email" name="email" placeholder="Email" required><br />
                                     <span class="help-block-email"></span>
                                 </div>
                                 <div class="ss-form-group" style="text-align: center;">
                                     <input id="mobile_edit"  style="margin-bottom: 5px;" type="tel" name="mobile"
                                         placeholder="Mobile" >
                                     <span id="passwordHelpInline" class="form-text">
                                         (Mobile not required)
                                     </span><br>
                                     <span class="help-block-mobile"></span>
                                 </div>
                                 <div>
                                     <button id="edit" class="save" style="width: 100%;">
                                         <span id="loading_edit" class="d-none">
                                             <span id="loadSpan" class="spinner-border spinner-border-sm" role="status"
                                                 aria-hidden="true"></span>
                                             Loading...
                                         </span>
                                         <span id="edit_text"> Edit Recruiter </span> </button>
                                 </div>
                                 <input type="text" id="id_edit" name="recuiter_id" hidden>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

        {{-- message registration modal --}}

        <div class="modal fade ss-jb-dtl-pops-mn-dv" id="text_modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="ss-pop-cls-vbtn">
                    </div>
                    <div class="modal-body" style="padding: 15px;">
                        <div class="ss-job-dtl-pop-form">
                            <!--------login form------->
                            <div class="ss-login-work-dv">
                                <h4><span>Invitation</span> sent !</h4>
                                <p style="text-align: center">
                                    An invitation to join your organization has been sen to <span id="recruiter_email">mohammedamineelharchi@gmail.com</span> , please ask
                                    them to check their spams if they do not receive it in 10 minutes.
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>
        .title-span {
            color: #b5649e;
        }
        .ss-main-body-sec table td
        {
            text-align: center;
        }
        .ss-main-body-sec table th
        {
            text-align: center;
        }
        .add {
            border: 1px solid #3D2C39 !important;
            color: #fff !important;
            border-radius: 100px;
            padding: 10px;
            text-align: center;
            width: 100px;
            margin-top: 25px;
            background: #3D2C39 !important;
            margin-right: 6px;
        }

        .save {
            border: 1px solid #3D2C39 !important;
            color: #fff !important;
            border-radius: 100px;
            padding: 10px;
            text-align: center;
            width: 'fit-content';
            margin-top: 25px;
            background: #3D2C39 !important;
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

        .add-recruiters-btn {
            display: flex;
            justify-content: end;
            align-items: center;
        }

        .add-recruiters-btn a,
        .add-recruiters-btn button {
            margin-right: 50px;
        }

        .recruiters-manage-tl {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ss-form-group input {
            margin-bottom: 20px;
        }

        #loading,
        #loading_edit,
        #sign,
        #edit_text,
        #edit,
        #loadSpan {
            color: #fff;
        }

        .ss-login-work-dv span {
            color: #b5649e;
        }
    </style>
    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#delete').click(function() {
                const keyId = $(this).data('id');
                $.ajax({
                    url: '/organization/delete-recruiter',
                    type: 'POST',
                    data: {
                        recruiter_id: keyId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Deleted Successfully',
                            time: 2
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    }
                });
            });
            $('#edit').click(function() {
            let id = document.getElementById('id_edit').value;
            let first_name = document.getElementById('first_name_edit').value;
            let last_name = document.getElementById('last_name_edit').value;
            let email = document.getElementById('email_edit').value;
            let mobile = document.getElementById('mobile_edit').value; 
            let access = true;
            let regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (first_name.trim() === '') {
                $('.help-block-first-name').text('Please enter your first name');
                $('.help-block-first-name').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-first-name').text('');
            }

            if (last_name.trim() === '') {
                $('.help-block-last-name').text('Please enter your last name');
                $('.help-block-last-name').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-last-name').text('');
            }

            if (!regexEmail.test(email)) {
                $('.help-block-email').text('Please enter a valid email address');
                $('.help-block-email').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-email').text('');
            }

            if ((!regexPhone.test(mobile)) && (mobile.trim() !== '')) {
                $('.help-block-mobile').text('Please enter a valid phone number in the format: +1 (xxx) xxx-xxxx');
                $('.help-block-mobile').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-mobile').text('');
            }
            if (access) {
                $('#loading_edit').removeClass('d-none');
                $('#edit_text').addClass('d-none');
                $.ajax({
                    url: '/organization/recruiter-edit',
                    type: 'POST',
                    data: {
                        recruiter_id: id,
                        first_name : first_name,
                        last_name : last_name,
                        email : email,
                        mobile: mobile,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#loading_edit').addClass('d-none');
                        $('#edit_text').removeClass('d-none');
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Edited Successfully',
                            time: 2
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    }
                });
            }
            });
        });

        function open_modal(id) {
            $(id).modal('show');
        }

        function close_modal(id) {
            $(id).modal('hide');
        }

        $('#contact_number').mask('+1 (999) 999-9999');
        $('#mobile_edit').mask('+1 (999) 999-9999');



        $('#submitBtn').click(function(event) {
            event.preventDefault();
            var access = true;
            $(this).find('input, button').prop('disabled', true);
            var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            var firstName = $('input[name="first_name"]').val();
            var lastName = $('input[name="last_name"]').val();
            var email = $('input[name="email"]').val();
            var mobile = $('#contact_number').val();

            if (firstName.trim() === '') {
                $('.help-block-first-name').text('Please enter your first name');
                $('.help-block-first-name').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-first-name').text('');
            }

            if (lastName.trim() === '') {
                $('.help-block-last-name').text('Please enter your last name');
                $('.help-block-last-name').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-last-name').text('');
            }

            if (!regexEmail.test(email)) {
                $('.help-block-email').text('Please enter a valid email address');
                $('.help-block-email').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-email').text('');
            }

            if ((!regexPhone.test(mobile)) && (mobile.trim() !== '')) {
                $('.help-block-mobile').text('Please enter a valid phone number in the format: +1 (xxx) xxx-xxxx');
                $('.help-block-mobile').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-mobile').text('');
            }
            if (access) {

                $('#loading').removeClass('d-none');
                $('#sign').addClass('d-none');


                $.ajax({
                    url: '/organization/recruiter-registration',
                    type: 'POST',
                    data: {
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        mobile: mobile,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success == true){
                            $('#loading').addClass('d-none');
                            $('#sign').removeClass('d-none');
                            $('#submitBtn').find('input, button').prop('disabled', false);
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> '+response.msg,
                                time: 2
                            });

                            close_modal('#input_modal');
                            document.getElementById('recruiter_email').innerHTML = email;
                            open_modal('#text_modal');
                            
                            return;
                        }else if(response.success == false){
                            $('#loading').addClass('d-none');
                            $('#sign').removeClass('d-none');
                            $('#submitBtn').find('input, button').prop('disabled', false);
                            notie.alert({
                                type: 'error',
                                text: '<i class="fa fa-times"></i> '+response.msg,
                                time: 2
                            });
                            return;
                        }

                    },
                    error: function() {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-times"></i> Something went wrong',
                            time: 2
                        });
                        $('#loading').addClass('d-none');
                        $('#sign').removeClass('d-none');
                        $('#submitBtn').find('input, button').prop('disabled', false);
                    }
                });
            }

        });

        

        function get_recruiter_data(recruiter){
            console.log(recruiter);        
            document.getElementById('first_name_edit').value = recruiter.first_name;
            document.getElementById('last_name_edit').value = recruiter.last_name;
            document.getElementById('email_edit').value = recruiter.email;
            document.getElementById('mobile_edit').value = recruiter.mobile;
            document.getElementById('id_edit').value = recruiter.id;
        }
    </script>
@endsection

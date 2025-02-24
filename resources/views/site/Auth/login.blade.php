@extends('layouts.main')
@section('content')
    <section class="ss-login-work-sec ss-admin-login-sed">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ss-login-work-logo-div">
                        <div class="ss-login-logo-dv">
                            <img src="{{ URL::asset('landing/img/admin-login-logo.png') }}" />
                        </div>
                        <p>Building a better way for healthcare workers & organizations to find each other</p>

                        <a href="{{ route('/') }}"><img src="{{ URL::asset('landing/img/logo.png') }}" /></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!--------login form------->
                    <div class="ss-login-work-dv">
                        <h4>
                            <span>Login</span>
                            
                        </h4>
                        <form method="post" action="{{ route('organization-login') }}" id="login-form" class="">
                            <div class="ss-form-group">
                                <select onchange="roleChange()"
                                 name="role" required>
                                    <option value="" disabled selected hidden
                                    >What’s your role?</option>

                                    @if(Auth::guard('organization')->check() && !Auth::guard('frontend')->check() && !Auth::guard('recruiter')->check())
                                        <option value="worker">Worker</option>
                                        <option value="recruiter">Recruiter</option>
                                    @elseif(Auth::guard('frontend')->check() && !Auth::guard('organization')->check() && !Auth::guard('recruiter')->check())
                                        <option value="organization">Organization</option>
                                        <option value="recruiter">Recruiter</option>
                                    @elseif(Auth::guard('recruiter')->check() && !Auth::guard('frontend')->check() && !Auth::guard('organization')->check())
                                        <option value="organization">Organization</option>
                                        <option value="worker">Worker</option>
                                    @else
                                        @if(!Auth::guard('organization')->check())
                                            <option value="organization">Organization</option>
                                        @endif
                                        @if(!Auth::guard('frontend')->check())
                                            <option value="worker">Worker</option>
                                        @endif
                                        @if(!Auth::guard('recruiter')->check())
                                            <option value="recruiter">Recruiter</option>
                                        @endif
                                    @endif
                                    
                                </select><br/>
                                <span class="help-block-role"></span>
                            </div>
                            <div class="ss-form-group">
                                <input type="text" name="id" placeholder="Enter phone number or email"><br>
                                <span class="help-block-email"></span>
                            </div>
                            <div>
                                <button id="submitBtn">
                                    <span id="loading" class="d-none">
                                        <span id="loadSpan" class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                    <span id="login"> Log in </span> </button>
                                <p id="signup_link">Don’t have an account? <a href="{{ route('signup') }}">Sign up</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js')
    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
    <script>

        function roleChange(){
          var role = document.querySelector('select[name=role]').value;
          if(role == 'organization' || role == 'worker'){
              $('#signup_link').show();
          }else{
              $('#signup_link').hide();
          }
        }

        $('#submitBtn').click(function(event) {
            event.preventDefault();
            var email = $('input[name="id"]').val();
            var role = $('select[name="role"]').val();

            $(this).find('input, button').prop('disabled', true);
            // var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
            var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            

            if (!regexEmail.test(email)) {
                $('.help-block-email').text('Please enter a valid email address');
                $('.help-block-email').addClass('text-danger');
                return false;
            } else {
                $('.help-block-email').text('');
            }

            if (role === null) {
                $('.help-block-role').text('Please select your role');
                $('.help-block-role').addClass('text-danger');
                return false;
            } else {
                $('.help-block-role').text('');
            }

            if (role === 'organization') {
                $('#login-form').attr('action', '{{ route('organization-login') }}');
            } else if (role === 'worker') {
                $('#login-form').attr('action', '{{ route('worker-login') }}');
            } else if (role === 'recruiter') {
                $('#login-form').attr('action', '{{ route('recruiter-login') }}');
            }


            $('#loading').removeClass('d-none');
            $('#login').addClass('d-none');
            $('#login-form').submit();


        });
    </script>

@stop

@section('css')
    <style>
        .ss-form-group input {
            margin-bottom: 3px;
        }

        .ss-form-group select {
            border: 2px solid #3D2C39 !important;
            padding: 10px !important;
            box-shadow: 10px 10px 0px 0px #3D2C39;
            border-radius: 12px;
            background: #fff !important;
            font-size: 17px;
            margin-bottom: 2px;
            width: 60%;
        }

        #loading,
        #login,
        #loadSpan {
            color: #fff;
        }
    </style>
@stop

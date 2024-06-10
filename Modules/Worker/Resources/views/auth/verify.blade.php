@extends('worker::layouts.auth')
@section('css')
<style>
/* OTP page css  */
.inputOtp input {
    display: inline-block !important;
    width: 50px !important;
    height: 50px !important;
    text-align: center !important;
    border: 1px solid #DADADA !important;
    border-radius: 4px !important;
    font-size: 28px !important;
    margin: 15px 7px !important;
}
.inputOtp input:focus {
    outline: none;
}
</style>
@stop
@section('content')
<section class="ss-login-work-sec ss-admin-login-sed">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-login-work-logo-div">
            <div class="ss-login-logo-dv">
              <img src="{{URL::asset('landing/img/admin-login-logo.png')}}" />
            </div>
            <p>Building a better way for healthcare workers & employers to find each other</p>

            <a href="{{route('/')}}"><img src="{{URL::asset('landing/img/logo.png')}}" /></a>
          </div>
        </div>

       <div class="col-lg-6">
          <!--------login form------->
          <div class="ss-verification-mn-div">
            <h4>Verification</h4>
            <p>We sent a verification code to<br>
            your registered email address and mobile number</p>
            <form method="post" action="{{route('worker.otp')}}" id="otp-form" class="">
                <ul class="ss-otp-v-ul">
                    <li><input type="text" name="otp1" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1></li>
                    <li><input type="text" name="otp2" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1></li>
                    <li><input type="text" name="otp3" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 ></li>
                    <li><input type="text" name="otp4" oninput='digitValidate(this)'onkeyup='tabChange(4)' maxlength=1></li>
                </ul>
                <!-- added -->
                <button type="submit"><span id="loadingVerify" class="d-none" >
                <span id="loadSpanVerify" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
            </span>
        <span id="verify">Continue </span></button>
                <!-- end added  -->
            </form>
            <ul class="ss-otp-re-send">
                <li><a href="javascript:void(0)" id="resendotp" disabled>Resend OTP in</a></li>
                <li><p class="countdown">01:26</p></li>
            </ul>
        </div>

        </div>
      </div>F
    </div>
</section>
<input type="hidden" name="otp" id="otp-timer" value="{{$expiry}}">
@stop

{{-- <div class="ss-verification-mn-div">
    <h4>Verification</h4>
    <p>We sent a verification code to<br>
    (419) 405-7399 </p>
    <form method="post" action="{{route('worker.otp')}}" id="otp-form" class="">
        <ul class="ss-otp-v-ul">
            <li><input type="text" name="otp1" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1></li>
            <li><input type="text" name="otp2" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1></li>
            <li><input type="text" name="otp3" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 ></li>
            <li><input type="text" name="otp4" oninput='digitValidate(this)'onkeyup='tabChange(4)' maxlength=1></li>
        </ul>
        <button type="submit">Continue</button>
    </form>
    <ul class="ss-otp-re-send">
        <li><a href="#">Resend OTP in</a></li>
        <li><p>01:26</p></li>
    </ul>
</div> --}}

@section('js')
<script>
    let digitValidate = function (ele) {
        // console.log(ele.value);
        ele.value = ele.value.replace(/[^0-9]/g, '');
    };

    let tabChange = function (val) {
        let ele = document.querySelectorAll("input");
        if (ele[val - 1].value != "") {
            if (val < 4) {
                ele[val].focus();
            }
            // else{
            //     auto_submit();
            // }
        } else if (ele[val - 1].value == "") {
            ele[val - 2].focus();
        }
        auto_submit();
    };

    let auto_submit = function  () {
        if (
            $("input").filter(function () {
                return $.trim($(this).val()).length == 0
            }).length == 0
        ) {
            submit_form('otp-form');
            $('#loadingVerify').removeClass('d-none');
    $('#verify').addClass('d-none');
        }
    }

    var timer2 = $('#otp-timer').val();
    var interval = setInterval(function() {

        console.log(timer2, 'timer2');
        var timer = timer2.split(':');
        //by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer[0], 10);
        console.log(minutes, 'minutes');
        var seconds = parseInt(timer[1], 10);
        console.log(seconds, 'seconds');
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if (minutes < 0) clearInterval(interval);
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        //minutes = (minutes < 10) ?  minutes : minutes;
        $('.countdown').html(minutes + ':' + seconds);
        timer2 = minutes + ':' + seconds;
        if (minutes == 0 && seconds == 0) {
            $('.countdown').html('Reloading...');
            resend_otp();
        }
        if(minutes < 4){
            document.getElementById("resendotp").removeAttribute("disabled");
            document.getElementById('resendotp').setAttribute('onclick', 'resend_otp()');
        }
    }, 1000);
</script>

<script>
    function resend_otp()
    {
        ajaxindicatorstart();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: full_path+"resend-otp",
            type: 'GET',
            dataType: 'json',
            // processData: false,
            // contentType: false,
            success: function (data) {
                ajaxindicatorstop();
                if (data.success) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> '+data.msg,
                        time: 5
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    location.reload();
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Something went wrong, try later.',
                    time: 5
                });
            }
        });
    }
</script>
@section('css')
<style>
    #loadingVerify,#verify,#loadSpanVerify{
        color:#fff;
    }
</style>
@stop
@endsection

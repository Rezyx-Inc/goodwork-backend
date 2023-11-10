@extends('layouts.main') 
@section('mytitle', ' Otp page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('public/frontend/assets/images/images/page_point.png')}}" alt="">
</section>

<section class="login_section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login_box">
                    <form action="{{route('submit-otp')}}" method="post" id="otp-form">
                    <div class="login_title">
                        <h3 class="mb-0">Check your email</h3>
                        <p><small class="text-muted">We have sent the code to the email</small></p>
                    </div>
                    <div class="mb-3">
                        <div class="inputOtp text-center">
                            <input class="otp" type="text" name="otp1" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1 >
                            <input class="otp" type="text" name="otp2" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1 >
                            <input class="otp" type="text" name="otp3" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 >
                            <input class="otp" type="text" name="otp4" oninput='digitValidate(this)'onkeyup='tabChange(4)' maxlength=1 >
                        </div>
                    </div>

                    <div class="dont_ac">
                        <p class="text-muted mb-3 countdown"></p>
                        <p class="mb-0">Did not receive code? <a class="login_link" href="javascript:void(0)" onclick="resend_otp()">Resend Code</a></h5>
                    </div>

                    

                    <a href="javascript:void(0)" onclick="submit_form('otp-form')" class="btn_sign_in">Verify</a>
                    </form>
                    

                </div>
            </div>
        </div>
    </div>
    </section>
    <input type="hidden" value="{{$expiry}}" id="otp-timer">
@stop

@section('js')
<script>
    let digitValidate = function (ele) {
        console.log(ele.value);
        ele.value = ele.value.replace(/[^0-9]/g, "");
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
        }
    }


    var timer2 = $('#otp-timer').val();
    var interval = setInterval(function() {


    var timer = timer2.split(':');
    //by parsing integer, I avoid all extra string processing
    var minutes = parseInt(timer[0], 10);
    var seconds = parseInt(timer[1], 10);
    --seconds;
    minutes = (seconds < 0) ? --minutes : minutes;
    if (minutes < 0) clearInterval(interval);
    seconds = (seconds < 0) ? 59 : seconds;
    seconds = (seconds < 10) ? '0' + seconds : seconds;
    //minutes = (minutes < 10) ?  minutes : minutes;
    $('.countdown').html('Code expires in '+minutes + ':' + seconds);
    timer2 = minutes + ':' + seconds;
    if (minutes == 0 && seconds == 0) {
        resend_otp();
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
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> '+data.msg,
                    time: 10
                });
                ajaxindicatorstop();
                setTimeout(function () {
                    location.reload();
                }, 3000)
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Something went wrong, try later.',
                    time: 10
                });
            }
        });
    }
</script>
@endsection
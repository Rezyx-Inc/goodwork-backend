@extends('worker::layouts.dashboard')
@section('mytitle', 'My Profile')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">

    <div class="ss-message-pg-mn-div">
      <div class="row">
        <div class="col-lg-5 ss-displ-flex">
          <div class="ss-messg-left-box">
            <h2>Messages</h2>

            <div class="ss-mesg-sml-div">
              <ul class="ss-msg-user-ul-dv">
                <li><img src="{{URL::asset('frontend/img/message-img1.png')}}" /></li>
                <li>
                  <h5>Recruiter #01</h5>
                  <p>Check the prescription</p>
                </li>
              </ul>

              <ul class="ss-msg-notifi-sec">
                <li><p>2 min ago</p></li>
                <li><span>3</span></li>
              </ul>
            </div>

             <div class="ss-mesg-sml-div">
              <ul class="ss-msg-user-ul-dv">
                <li><img src="{{URL::asset('frontend/img/message-img2.png')}}" /></li>
                <li>
                  <h5>Recruiter #02</h5>
                  <p>Check the prescription</p>
                </li>
              </ul>

              <ul class="ss-msg-notifi-sec">
                <li><p>2 min ago</p></li>
                <li><span>3</span></li>
              </ul>
            </div>


             <div class="ss-mesg-sml-div">
              <ul class="ss-msg-user-ul-dv">
                <li><img src="{{URL::asset('frontend/img/message-img3.png')}}" /></li>
                <li>
                  <h5>Recruiter #03</h5>
                  <p>Check the prescription</p>
                </li>
              </ul>

              <ul class="ss-msg-notifi-sec">
                <li><p>2d ago</p></li>

              </ul>
            </div>

             <div class="ss-mesg-sml-div">
              <ul class="ss-msg-user-ul-dv">
                <li><img src="{{URL::asset('frontend/img/message-img4.png')}}" /></li>
                <li>
                  <h5>Recruiter #04</h5>
                  <p>Check the prescription</p>
                </li>
              </ul>

              <ul class="ss-msg-notifi-sec">
                <li><p>3d ago</p></li>

              </ul>
            </div>

          </div>
        </div>

        <div class="col-lg-7">
          <div class="ss-msg-rply-mn-div">
            <div class="ss-msg-rply-profile-sec">
              <ul>
                <li><img src="{{URL::asset('frontend/img/msg-rply-box-img.png')}}" /></li>
                <li>
                  <h6>Recruiter #01</h6>
                  <p>Travel Worker CRNA/.....</p>
                </li>
              </ul>
            </div>

            <div class="ss-msgrply-tody">
              <p>Today</p>
            </div>
            <div class="ss-msg-rply-blue-dv">
              <h6>Jhon Abraham</h6>
              <p>Hello! Jhon abraham</p>
              <span>09:25 AM</span>
            </div>

            <div class="ss-msg-rply-recrut-dv">
              <h6>Recruiter #01</h6>
              <p>Have a great working week!!</p>
              <p>Hope you like it</p>
              <span>09:25 AM</span>
            </div>

            <div class="ss-rply-msg-input">
              <input type="text" id="fname" name="fname" placeholder="Express yourself here!">
              <button type="text"><img src="{{URL::asset('frontend/img/msg-rply-btn.png')}}" /></button>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>

</main>
@stop

@section('js')

@stop

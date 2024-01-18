@extends('employer::layouts.main')

@section('content')
@php
       $faker = app('Faker\Generator');
@endphp
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container" id="workers_messages">

        <div class="ss-message-pg-mn-div">
            <div class="row">
                <div class="col-lg-5 ss-displ-flex">
                    <div class="ss-messg-left-box">
                        <h2>Messages</h2>
                        <div class="ss-opport-mngr-hedr">
                            <ul style="float:left;">
                                <li style="margin-left:0px;"><button id="workers_btn" onclick="messageType('workers')"
                                        class="ss-darfts-sec-draft-btn">Workers</button></li>
                                <li style="margin-left:0px;"><button id="recruiters_btn"
                                        onclick="messageType('recruiters')"
                                        class="ss-darfts-sec-publsh-btn">Recruiters</button></li>



                            </ul>
                        </div>
                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('employer/assets/images/message-img1.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2 min ago</p>
                                </li>
                                <li><span>3</span></li>
                            </ul>
                        </div>

                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('employer/assets/images/message-img2.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2 min ago</p>
                                </li>
                                <li><span>3</span></li>
                            </ul>
                        </div>


                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('employer/assets/images/message-img3.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2d ago</p>
                                </li>

                            </ul>
                        </div>

                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('employer/assets/images/message-img4.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>3d ago</p>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ss-msg-rply-mn-div">
                        <div class="ss-msg-rply-profile-sec">
                            <ul>
                                <li><img src="{{URL::asset('employer/assets/images/msg-rply-box-img.png')}}" /></li>
                                <li>
                                    <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
                                    <p>Associate Degree in Nursing</p>
                                </li>
                            </ul>
                        </div>

                        <div class="ss-msgrply-tody">
                            <p>Today</p>
                        </div>
                        <div class="ss-msg-rply-blue-dv">
                            <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
                            <p>Hello! Jhon abraham</p>
                            <span>09:25 AM</span>
                        </div>

                        <div class="ss-msg-rply-recrut-dv">
                            <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
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

    <div class="container d-none" id="recruiters_messages">

        <div class="ss-message-pg-mn-div">
            <div class="row">
                <div class="col-lg-5 ss-displ-flex">
                    <div class="ss-messg-left-box">
                        <h2>Messages</h2>
                        <div class="ss-opport-mngr-hedr">
                            <ul style="float:left;">
                                <li style="margin-left:0px;"><button id="drafts" onclick="messageType('workers')"
                                        class="ss-darfts-sec-draft-btn">Workers</button></li>
                                <li style="margin-left:0px;"><button id="published" onclick="messageType('recruiters')"
                                        class="ss-darfts-sec-publsh-btn active">Recruiters</button></li>



                            </ul>
                        </div>
                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('frontend/img/message-img1.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2 min ago</p>
                                </li>
                                <li><span>3</span></li>
                            </ul>
                        </div>

                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('frontend/img/message-img2.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2 min ago</p>
                                </li>
                                <li><span>3</span></li>
                            </ul>
                        </div>


                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('frontend/img/message-img3.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>2d ago</p>
                                </li>

                            </ul>
                        </div>

                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('frontend/img/message-img4.png')}}" /></li>
                                <li>
                                    <h5>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h5>
                                    <p>Check the prescription</p>
                                </li>
                            </ul>

                            <ul class="ss-msg-notifi-sec">
                                <li>
                                    <p>3d ago</p>
                                </li>

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
                                    <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
                                    <p>Travel Worker CRNA/.....</p>
                                </li>
                            </ul>
                        </div>

                        <div class="ss-msgrply-tody">
                            <p>Today</p>
                        </div>
                        <div class="ss-msg-rply-blue-dv">
                            <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
                            <p>Hello! {{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</p>
                            <span>09:25 AM</span>
                        </div>

                        <div class="ss-msg-rply-recrut-dv">
                            <h6>{{ $faker->fantasyName('first_name')}} {{ $faker->fantasyName('last_name')}}</h6>
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
<script>

    function messageType(type) {
        if (type == "workers") {
            document.getElementById('recruiters_messages').classList.add('d-none');
            document.getElementById('workers_messages').classList.remove('d-none');
            document.getElementById('workers_btn').classList.add("active");
        } else {
            document.getElementById('recruiters_messages').classList.remove('d-none');
            document.getElementById('workers_messages').classList.add('d-none');
            document.getElementById('recruiters_btn').classList.add("active");
        }
    }

    $(document).ready(function () {
        messageType('workers');
        document.getElementById('workers_btn').classList.add("active");

    });
</script>
@endsection

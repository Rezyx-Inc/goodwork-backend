@extends('employer::layouts.main')

@section('content')
@php
       $faker = app('Faker\Generator');
@endphp
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('js/app.js') }}"></script>
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

            // it adjust the scroll bar to the bottom of the messages container : il faut le mettre dans les chats des recruteurs !!
     var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));
        }
    }

    $(document).ready(function () {
        messageType('recruiters');
        console.log('ready');
        
        $.get('/employer/getMessages?page=1&workerId=GWU000005', function(data) {
                // Parse the returned data
                var messages = data.messages;
            
                console.log(data.messages);

                // Function to create the HTML for a message
                function createMessageHTML(message) {
                    var senderClass = message.sender == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
                    var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;

                    return `
                        <div class="${senderClass}">
                            <h6>${message.sender}</h6>
                            <p>${message.content}</p>
                            <span>${time}</span>
                        </div>
                    `;
                }

                // Create the HTML for each message and prepend it to the messages area
                messages.forEach(function(message) {
                    var messageHTML = createMessageHTML(message);
                    $('.private-messages').append(messageHTML);
                });
                var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));
                
            });
            var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));
        //document.getElementById('workers_btn').classList.add("active");
        var page = 1; // Initialize the page number
    var workerId = 'GWU000005'; // Replace with the actual worker ID

    $('.messages-area').scroll(function() {
        if($(this).scrollTop() == 0) { // If the scrollbar is at the top
            page++; // Increment the page number

            // Make an AJAX request to the API
            $.get('/employer/getMessages?page=' + page + '&workerId=GWU000005', function(data) {
                // Parse the returned data
                var messages = data.messages;

                console.log(data.messages);

                // Function to create the HTML for a message
                function createMessageHTML(message) {
                    var senderClass = message.sender == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
                    var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;

                    return `
                        <div class="${senderClass}">
                            <h6>${message.sender}</h6>
                            <p>${message.content}</p>
                            <span>${time}</span>
                        </div>
                    `;
                }

                // Create the HTML for each message and prepend it to the messages area
                messages.forEach(function(message) {
                    var messageHTML = createMessageHTML(message);
                    $('.private-messages').prepend(messageHTML);
                });
            });
        }
    });
    });

    window.onload = function() {
    let id = @json($id);
    let PrivateChannel = 'private-chat.'+id+".GWU000005";

    console.log(PrivateChannel);

    let messageText = document.getElementById('message');
    console.log(messageText);

    function createRealMessageHTML(message) {
                    var senderClass = message.senderRole == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
                    var time = Array.isArray(message.time) ? message.messageTime.join(', ') : message.messageTime;

                    return `
                        <div class="${senderClass}">
                            <h6>${message.senderRole}</h6>
                            <p>${message.message}</p>
                            <span>${message.messageTime}</span>
                        </div>
                    `;
                }

    // Listen for NewMessage event on the goodwork_database_messages channel : PUBLIC MESSAGES
    window.Echo.channel('goodwork_database_messages')
        .listen('NewMessage', (event) => {
            console.log('New message:', event.message);
        });

    // Listen for NewPrivateMessage event on the goodwork_database_private-private-chat.UserId channel

    window.Echo.private(PrivateChannel)
        .listen('NewPrivateMessage', (event) => {
           // console.log('event:', event);
            // messageText.innerHTML = "message : " + event.message + " role : " + event.senderRole
            // + " employer ID : " + event.EmployerId + " worker ID : " + event.WorkerId + "time : " + event.messageTime ;

            var messageHTML = createRealMessageHTML(event);
                    $('.private-messages').append(messageHTML);
        });
    
  }
    
</script>


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
                    <div class="ss-msg-rply-mn-div messages-area">
                        <div class="ss-msg-rply-profile-sec">
                            <ul>
                                <li><img src="{{URL::asset('frontend/img/msg-rply-box-img.png')}}" /></li>
                                <li>
                                
                                    <p>Travel Nurse CRNA/.....</p>
                                </li>
                            </ul>
                        </div>

                        <div class="ss-msgrply-tody">
                            <p>Today</p>
                        </div>

                        <div class="private-messages">
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

<style>
    .messages-area {
    height: 80vh; 
    overflow-y: auto;
}
.ss-rply-msg-input{
    margin-top: 10px;
    position: relative;
}

</style>
@endsection

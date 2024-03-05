@extends('worker::layouts.main')

@section('content')
@php
       $faker = app('Faker\Generator');
@endphp

<script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('js/app.js') }}"></script>
<script>

var idEmployer_Global = '';
var PrivateChannel = '';
var page = 1; // Initialize the page number (the number of the 10 messages to be loaded next)
function getPrivateMessages(idEmployer,fullName) {

    document.getElementById('fullName').innerHTML = fullName;

    page=1;

    document.getElementById('empty_room').classList.add("d-none");
    document.getElementById('body_room').classList.remove("d-none");
    idEmployer_Global = idEmployer;

    let id = @json($id);
    
     PrivateChannel = 'private-chat.' + id + '.' + idEmployer_Global;
        
    let messageText = document.getElementById('message');
    console.log(messageText, "TEXT");

    function createRealMessageHTML(message) {
        var senderClass = message.senderRole == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
        var time = Array.isArray(message.time) ? message.messageTime.join(', ') : message.messageTime;

        return `
            <div class="${senderClass}">
                
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
            var messageHTML = createRealMessageHTML(event);
                    $('.private-messages').append(messageHTML);
        });

    $('.private-messages').html('');
    $.get('/worker/getMessages?page=1&employerId='+idEmployer, function(data) {
                // Parse the returned data
                var messages = data.messages;
            
                console.log(data.messages);

                // Function to create the HTML for a message
                function createMessageHTML(message) {
                    var senderClass = message.sender == 'WORKER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
                    var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;
                    return `
                        <div class="${senderClass}">
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
                var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));
                
            });
}
    $(document).ready(function () {

        var messagesArea = $('.messages-area');
        messagesArea.scrollTop(messagesArea.prop('scrollHeight'));
       

    $('.messages-area').scroll(function() {
        if($(this).scrollTop() == 0) { // If the scrollbar is at the top
            page++; // Increment the page number

            $('#loading').removeClass('d-none');
    $('#login').addClass('d-none');
            // Make an AJAX request to the API
            $.get('/worker/getMessages?page=' + page + '&employerId='+idEmployer_Global , function(data) {
                // Parse the returned data
                var messages = data.messages;

                console.log(idEmployer_Global);
                // Function to create the HTML for a message
                function createMessageHTML(message) {
                    var senderClass = message.sender == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';
                    var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;

                    return `
                        <div class="${senderClass}">
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

                $('#login').removeClass('d-none');
                $('#loading').addClass('d-none');
            });
        }
    });
    });

    window.onload = function() {
    // Listen for NewMessage event on the goodwork_database_messages channel : PUBLIC MESSAGES
    window.Echo.channel('goodwork_database_messages')
        .listen('NewMessage', (event) => {
            console.log('New message:', event.message);
        });
  }

  function sendMessage(){

    let id = @json($id);
    PrivateChannel = 'private-chat.'+id + '.' + idEmployer_Global;
    console.log(PrivateChannel);
    let messageInput = document.getElementById('messageEnvoye');
    let message = messageInput.value;
if(message != ""){

    $.ajax({
                url: 'send-message',
                type: 'POST',
                data: {
                    idEmployer : idEmployer_Global,
                    message_data : message,
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    messageInput.value = "";
        console.log('message envoyé');
                }
            });
        }
  }

  $(document).ready(function(){
    $('#messageEnvoye').keypress(function(e){
        if(e.which == 13){ // 13 is the key code for the enter key
            e.preventDefault(); // Prevent the default action (form submission)
            sendMessage(); // Call your function
        }
    });
});
    
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<main style="padding-top: 130px" class="ss-main-body-sec">
    

    <div class="container" id="recruiters_messages">
        <div class="ss-message-pg-mn-div">
            <div class="row">
                <div class="col-lg-5 ss-displ-flex">
                    <div class="ss-messg-left-box">
                        <h2>Messages</h2>
                        <div class="ss-opport-mngr-hedr">
                            <ul style="float:left;">
                               
                                <li style="margin-left:0px;"><button id="published" 
                                        class="ss-darfts-sec-publsh-btn active">Recruiters</button></li>
                            </ul>
                        </div>
                        
                    <!-- rooms  -->
                    @if($data)
                    @foreach($data as $room)
                   
                    <div onclick="getPrivateMessages('{{$room['employerId']}}','{{$room['fullName']}}')" class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('frontend/img/message-img1.png')}}" /></li>
                                <li>
                                    <h5>{{$room['fullName']}}</h5>
                                    <p>{{$room['messages'][0]['content']}}</p>
                                </li>
                            </ul>

                            <ul style="width:100%"  class="ss-msg-notifi-sec">
                                <li>
                                    <p>{{$room['lastMessage']}}</p>
                                </li><br>
                                <li><span>3</span></li>
                            </ul>
                        </div>
                        @endforeach
                        @else
                        <div class="ss-mesg-sml-div">
                        <h5>No chats founded</h5>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="col-lg-7 ">
                <div id="empty_room" class="ss-msg-rply-mn-div ">
                        <h5 class="empty_room">no chat was choosen</h5>
                    </div>
                    <div id="body_room" class="d-none ss-msg-rply-mn-div messages-area parentMessages">
                        <div class="ss-msg-rply-profile-sec">
                            <ul>
                                <li><img src="{{URL::asset('frontend/img/msg-rply-box-img.png')}}" /></li>
                                <li>   
                                    <p id="fullName"></p>
                                </li>
                            </ul>
                        </div>
                        <div class="ss-msgrply-tody">
                            
                            <span id="loading" class="d-none" >
                          <span id="loadSpan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                    </span>
                    <span id="login"> <p>Today</p>  </span>
                        </div>
                        <div class="private-messages">
                        </div>
                        <div class="ss-rply-msg-input">
                         <input type="text" id="messageEnvoye" name="fname" placeholder="Express yourself here!">
                            <button onclick="sendMessage()" type="text"><img src="{{URL::asset('frontend/img/msg-rply-btn.png')}}" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <<?php print_r($room); ?>

</main>

<style>
    .messages-area {
    height: 80vh; 
    overflow-y: auto;
}

.parentMessages {
    position: relative; 
}


.ss-msg-rply-mn-div {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.ss-rply-msg-input {
    margin-top: auto;
}

.ss-rply-msg-input {
    position: sticky;
    bottom: 0;
    left: 0;
    width: 100%;
    /* other styles */
}
.empty_room{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

#loading,#login,#loadSpan{
        color:black;
    }

</style>
@endsection
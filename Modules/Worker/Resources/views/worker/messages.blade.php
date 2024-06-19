@extends('worker::layouts.main')

@section('content')
    @php
        $faker = app('Faker\Generator');
    @endphp

    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        var idEmployer_Global = '';
        var idRecruiter_Global = '';
        var PrivateChannel = '';
        var page = 1; // Initialize the page number (the number of the 10 messages to be loaded next)

        function getPrivateMessages(idRecruiter, idEmployer, fullName) {

            // Get Full Name and set some DOM
            document.getElementById('fullName').innerHTML = fullName;
            document.getElementById('empty_room').classList.add("d-none");
            document.getElementById('body_room').classList.remove("d-none");

            // Set the global employer id
            idEmployer_Global = idEmployer;
            idRecruiter_Global = idRecruiter;

            // Set the user Id
            let id = @json($id);

            // Set Private Channel
            PrivateChannel = 'private-chat.' + idEmployer_Global + '.' + idRecruiter_Global + '.' + id;

            let messageText = document.getElementById('message');

            function createRealMessageHTML(message) {
                var senderClass;
                if (message.senderRole == 'WORKER') {
                    senderClass = 'ss-msg-rply-blue-dv';
                } else if (message.senderRole == 'EMPLOYER') {
                    senderClass = 'ss-msg-rply-black-dv';
                } else {
                    senderClass = 'ss-msg-rply-recrut-dv';
                }

                var time = Array.isArray(message.time) ? message.messageTime.join(', ') : message.messageTime;

                var messageContent;
                if (message.type === 'file') {
                    // If the message is a file, create a link to download the file
                    // The file name is extracted from the base64 data URL
                    var fileName = message.message.split(';')[1].split('=')[1];
                    messageContent =
                        `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.message}" download="${message.fileName}">${message.fileName}</a></p>`;
                } else {
                    // If the message is not a file, display the message text
                    messageContent = `<p>${message.message}</p>`;
                }

                return `
            <div class="${senderClass}">
                ${messageContent}
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
                    console.log('New private message Worker:', event);
                    var messageHTML = createRealMessageHTML(event);
                    $('.private-messages').append(messageHTML);
                    var element = document.getElementById('body_room');
                    element.scrollTop = element.scrollHeight;
                });

            $('.private-messages').html('');

            // Get the private messages
            $.get('/worker/getMessages?page=1&employerId=' + idEmployer_Global + '&recruiterId=' + idRecruiter_Global, function(data) {

                console.log("Receiving data", data)
                // Parse the returned data
                var messages = data.messages;

                // Function to create the HTML for a message
                function createMessageHTML(message) {
                    var senderClass;
                    if (message.sender == 'WORKER') {
                        senderClass = 'ss-msg-rply-blue-dv';
                    } else if (message.sender == 'EMPLOYER') {
                        senderClass = 'ss-msg-rply-black-dv';
                    } else {
                        senderClass = 'ss-msg-rply-recrut-dv';
                    }

                    var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;

                    var messageContent;
                    if (message.type === 'file') {
                        // If the message is a file, create a link to download the file
                        // The file name is extracted from the base64 data URL
                        var fileName = message.fileName; // assuming 'fileName' is the key in the message data
                        messageContent =
                            `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.content}" download="${message.fileName}">${message.fileName}</a></p>`;
                    } else {
                        // If the message is not a file, display the message text
                        messageContent = `<p>${message.content}</p>`;
                    }

                    return `
            <div class="${senderClass}">
                ${messageContent}
                <span>${time}</span>
            </div>
        `;
                }

                // Create the HTML for each message and prepend it to the messages area
                messages.forEach(function(message) {
                    var messageHTML = createMessageHTML(message);
                    $('.private-messages').prepend(messageHTML);
                });

                let messagesArea = $('.messages-area');
                messagesArea.scrollTop(messagesArea.prop('scrollHeight'));

            });
        }

        function createRealMessageHTML(message) {
            var senderClass;
            if (message.senderRole == 'WORKER') {
                senderClass = 'ss-msg-rply-blue-dv';
                console.log(senderClass);
            } else if (message.senderRole == 'EMPLOYER') {
                senderClass = 'ss-msg-rply-black-dv';
            } else {
                senderClass = 'ss-msg-rply-recrut-dv';
            }
            var time = Array.isArray(message.time) ? message.messageTime.join(', ') : message.messageTime;

            var messageContent;
            if (message.type === 'file') {
                // If the message is a file, create a link to download the file
                // The file name is extracted from the base64 data URL
                var fileName = message.message.split(';')[1].split('=')[1];
                messageContent =
                    `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.message}" download="${message.fileName}">${message.fileName}</a></p>`;
            } else {
                // If the message is not a file, display the message text
                messageContent = `<p>${message.message}</p>`;
            }
            return `
            <div class="${senderClass}">
                
                ${messageContent}
                <span>${message.messageTime}</span>
            </div>
        `;
        }

        // Function to create the HTML for a message
        function createMessageHTML(message) {
            var senderClass;
            if (message.sender == 'WORKER') {
                senderClass = 'ss-msg-rply-blue-dv';
            } else if (message.sender == 'EMPLOYER') {
                senderClass = 'ss-msg-rply-black-dv';
            } else {
                senderClass = 'ss-msg-rply-recrut-dv';
            }
            var time = Array.isArray(message.time) ? message.time.join(', ') : message.time;
            var messageContent;
            if (message.type === 'file') {
                // If the message is a file, create a link to download the file
                // The file name is extracted from the base64 data URL
                var fileName = message.content.split(';')[1].split('=')[1];
                messageContent =
                    `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.message}" download="${message.fileName}">${message.fileName}</a></p>`;
            } else {
                // If the message is not a file, display the message text
                messageContent = `<p>${message.content}</p>`;
            }
            return `
            <div class="${senderClass}">
                ${messageContent}
                <span>${time}</span>
            </div>
        `;
        }

        $(document).ready(function() {

            $('#messageEnvoye').keypress(function(e) {
                if (e.which == 13) { // 13 is the key code for the enter key
                    e.preventDefault(); // Prevent the default action (form submission)
                    let messageInput = document.getElementById('messageEnvoye');
                    if (messageInput.value.trim() === '') {
                        return;
                    }else{
                        sendMessage('text'); // Call your function
                    }
                   
                }
            });

            var messagesArea = $('.messages-area');
            messagesArea.scrollTop(messagesArea.prop('scrollHeight'));

            $('.messages-area').scroll(function() {

                if ($(this).scrollTop() == 0) { // If the scrollbar is at the top

                    page++; // Increment the page number
                    $('#loading').removeClass('d-none');
                    $('#login').addClass('d-none');

                    // Make an AJAX request to the API
                    $.get('/worker/getMessages?page=' + page + '&employerId=' + idEmployer_Global +
                        '&recruiterId=GWU000005',
                        function(data) {

                            // Parse the returned data
                            var messages = data.messages;

                            function createMessageHTML(message) {
                                //  var senderClass = message.sender == 'EMPLOYER' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';

                                console.log('messageeeee :' + message.sender);
                                var senderClass;
                                if (message.sender == 'WORKER') {
                                    senderClass = 'ss-msg-rply-blue-dv';
                                } else if (message.sender == 'EMPLOYER') {
                                    senderClass = 'ss-msg-rply-black-dv';
                                } else {
                                    senderClass = 'ss-msg-rply-recrut-dv';
                                }



                                var time = Array.isArray(message.time) ? message.time.join(', ') :
                                    message.time;

                                var messageContent;
                                if (message.type === 'file') {
                                    // If the message is a file, create a link to download the file
                                    // The file name is extracted from the base64 data URL
                                    var fileName = message
                                    .fileName; // assuming 'fileName' is the key in the message data
                                    messageContent =
                                        `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.content}" download="${message.fileName}">${message.fileName}</a></p>`;
                                } else {
                                    // If the message is not a file, display the message text
                                    messageContent = `<p>${message.content}</p>`;
                                }

                                return `
                        <div class="${senderClass}">
                            ${messageContent}
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



        function sendMessage(type) {
            console.log(type);
            let id = @json($id);
            PrivateChannel = 'private-chat.' + idEmployer_Global + '.' + idRecruiter_Global + '.' + id;
            console.log(PrivateChannel);
            let messageInput = document.getElementById('messageEnvoye');
            let message = messageInput.value;

            let formData = new FormData();
            formData.append('idEmployer', idEmployer_Global);
            formData.append('idRecruiter', idRecruiter_Global);
            formData.append('type', type);
            formData.append('_token', '{{ csrf_token() }}');

            if (type === 'text') {
                formData.append('message_data', message);
            } else if (type === 'file') {
                let fileInput = document.getElementById('fileInput');
                let file = fileInput.files[0];

                // Append the file name to the FormData object
                formData.append('fileName', file.name);

                let reader = new FileReader();
                reader.onloadend = function() {
                    let base64data = reader.result;
                    formData.append('message_data', base64data);
                    sendAjaxRequest(formData);
                }
                reader.readAsDataURL(file);
                return; // Return early because we'll send the AJAX request after the file is read
            }

            sendAjaxRequest(formData);
        }

        function sendAjaxRequest(formData) {
            $.ajax({
                url: 'send-message',
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function() {
                    document.getElementById('messageEnvoye').value = "";
                    console.log('message envoyé');
                }
            });
        }

        window.onload = function() {
            let fileUpload = document.getElementById('fileUpload');
            let fileInput = document.getElementById('fileInput');

            if (fileUpload) {
                fileUpload.addEventListener('click', function() {
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            }

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        document.getElementById('fileName').textContent = this.files[0].name;
                        document.getElementById('fileInfo').style.display = 'flex';
                        var element = document.getElementById('body_room');
                        element.scrollTop = element.scrollHeight;
                    }
                });
            }

            let sendFile = document.getElementById('sendFile');
            let deleteFile = document.getElementById('deleteFile');

            if (deleteFile) {
                deleteFile.addEventListener('click', function() {
                    document.getElementById('fileInfo').style.display = 'none';
                    document.getElementById('fileInput').value = '';
                });
            }
            if (sendFile) {
                sendFile.addEventListener('click', function() {
                    sendMessage('file');
                    document.getElementById('fileInfo').style.display = 'none';
                });
            }
        }
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
                            @if ($data)
                                @foreach ($data as $room)
                                    <div onclick="getPrivateMessages('{{ $room['recruiterId'] }}','{{ $room['employerId'] }}','{{ $room['fullName'] }}')"
                                        class="ss-mesg-sml-div">
                                        <ul class="ss-msg-user-ul-dv">
                                            <li><img src="{{ URL::asset('frontend/img/message-img1.png') }}" /></li>
                                            <li>
                                                <h5>{{ $room['fullName'] }}</h5>
<p>
    @if(isset($room['messages'][0]))
        {{ $room['messages'][0]['type'] === 'file' ? $room['messages'][0]['fileName'] : $room['messages'][0]['content'] }}
    @else
        No messages yet.
    @endif
</p>
                                            </li>
                                        </ul>

                                        <ul style="width:100%" class="ss-msg-notifi-sec">
                                            <li>
                                                <p>{{ $room['lastMessage'] }}</p>
                                            </li><br>
                                            <li><span>3</span></li>
                                        </ul>
                                    </div>
                                @endforeach
                            @else
                                <div class="ss-mesg-sml-div">
                                    <h5>No chats for now ...</h5>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-lg-7 ">
                        <div id="empty_room" class="ss-msg-rply-mn-div ">
                            <h5 class="empty_room">no chat was chosen</h5>
                        </div>
                        <div id="body_room" class="d-none ss-msg-rply-mn-div messages-area parentMessages">
                            <div class="ss-msg-rply-profile-sec">
                                <ul>
                                    <li><img src="{{ URL::asset('frontend/img/msg-rply-box-img.png') }}" /></li>
                                    <li>
                                        <p id="fullName"></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-msgrply-tody">

                                <span id="loading" class="d-none">
                                    <span id="loadSpan" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Loading...
                                </span>
                                <span id="login">
                                    <p>Today</p>
                                </span>
                            </div>
                            <div class="private-messages">
                            </div>
                            <div class="ss-rply-msg-input">
                                <input type="text" id="messageEnvoye" name="fname"
                                    placeholder="Express yourself here!">
                                <input type="file" id="fileInput" style="display: none;">
                                <button id="fileUpload" type="button"><img
                                        src="{{ URL::asset('frontend/img/msg-rply-btn.png') }}" /></button>

                            </div>
                            <div class="row" style="margin-top: 25px;">
                                <div class="row justify-content-end" id="fileInfo" style="display: none;">
                                    <div class="col-6">
                                        <span style=" margin-left: 16px;" id="fileName"></span>
                                    </div>
                                    <div style=" display: flex;
                                justify-content: end;
                               
                                "
                                        class="col-6">
                                        <button
                                            style="width: 30%;
                                    border-radius: 100px;
                                "
                                            class="ss-prsnl-save-btn button" id="deleteFile">Delete</button>
                                        <button
                                            style="width: 30%;
                                    border-radius: 100px;
                                    margin-left: 10px;
                                "
                                            class="ss-prsnl-save-btn button" id="sendFile">Send</button>
                                    </div>
                                </div>
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

        .empty_room {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #loading,
        #login,
        #loadSpan {
            color: black;
        }
    </style>
@endsection

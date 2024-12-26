@extends('organization::layouts.main')

@section('content')

    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // global variables :
        var idWorker_Global = '';
        var idRecruiter_Global = '';
        var PrivateChannel = '';
        var page = 1;

        // functions : getPrivateMessages, createMessageHTML, getSenderClass, getMessageContent, updateRoomLastMessage, updateLastMessageTime, incrementMessageCount, resetMessageCount, updateChatUI, scrollToBottom, focusOnInput, sendMessage, sendAjaxRequest

        function getPrivateMessages(idWorker, fullName, idRecruiter) {
            window.Echo.leave(PrivateChannel);

            const data = {
                sender: idWorker,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: 'read-organization-message-notification',
                type: 'POST',
                data: data,
                success: function(response) {
                    console.log('Success:', response);
                    resetMessageCount(idWorker);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            updateChatUI(fullName);
            idWorker_Global = idWorker;
            idRecruiter_Global = idRecruiter;
            let id = @json($id);
            PrivateChannel = 'private-chat.' + id + '.' + idRecruiter + '.' + idWorker_Global;

            window.Echo.channel('goodwork_database_messages')
                .listen('NewMessage', (event) => {
                    console.log('New message:', event.message);
                });

            window.Echo.private(PrivateChannel)
                .listen('NewPrivateMessage', (event) => {
                    console.log('New private message Organization:', event);
                    var messageHTML = createMessageHTML(event);
                    $('.private-messages').append(messageHTML);
                    scrollToBottom('body_room');
                });

            $('.private-messages').html('');

            $.get('/organization/getMessages?page=1&workerId=' + idWorker_Global + '&recruiterId=' + idRecruiter_Global,
                function(data) {
                    console.log("Receiving data", data);
                    var messages = data.messages;
                    messages.forEach(function(message) {
                        var messageHTML = createMessageHTML(message);
                        $('.private-messages').prepend(messageHTML);
                    });
                    focusOnInput();
                });
        }

        function createMessageHTML(message) {
            var senderClass = getSenderClass(message.senderRole || message.sender);
            var messageContent = getMessageContent(message);
            return `
                <div class="${senderClass}">
                    ${messageContent}
                    <span>${message.messageTime || message.time}</span>
                </div>
            `;
        }

        function getSenderClass(senderRole) {
            if (senderRole == 'RECRUITER') {
                return 'ss-msg-rply-recrut-dv';
            } else if (senderRole == 'ORGANIZATION') {
                return 'ss-msg-rply-blue-dv';
            } else if (senderRole == 'WORKER') {
                return 'ss-msg-rply-worker-dv';
            } else if (senderRole == 'ADMIN') {
                return 'ss-msg-rply-admin-dv';
            }
        }

        function getMessageContent(message) {
            if (message.type === 'file') {
                var fileName = message.fileName || message.message.split(';')[1].split('=')[1];
                return `<p><a style="color:white;text-decoration:underline;font-size:20px;" href="${message.message}" download="${fileName}">${fileName}</a></p>`;
            } else {
                return `<p>${message.message || message.content}</p>`;
            }
        }

        function updateRoomLastMessage(sender, content) {
            let room_last_messages = document.getElementById('room_' + sender);
            if (room_last_messages) {
                room_last_messages.innerHTML = content.length > 16 ? content.substring(0, 16) + ' ...' : content;
            }
        }

        function updateLastMessageTime(sender, time) {
            let lastMessage = document.getElementById('lastMessage_' + sender);
            if (lastMessage) {
                lastMessage.innerHTML = time;
            }
        }

        function incrementMessageCount(sender) {
            let messagesN = document.getElementById('messagesN_' + sender);
            if (messagesN) {
                messagesN.classList.remove("d-none");
                messagesN.innerHTML = parseInt(messagesN.innerHTML) + 1;
            }
        }

        function resetMessageCount(idWorker) {
            let messagesN = document.getElementById('messagesN_' + idWorker);
            if (messagesN) {
                messagesN.classList.add("d-none");
                messagesN.innerHTML = 0;
            }
        }

        function updateChatUI(fullName) {
            document.getElementById('fullName').innerHTML = fullName;
            document.getElementById('empty_room').classList.add("d-none");
            document.getElementById('body_room').classList.remove("d-none");
            document.getElementsByClassName('ss-rply-msg-input')[0].classList.remove("d-none");
        }

        function scrollToBottom(elementId) {
            var element = document.getElementById(elementId);
            element.scrollTop = element.scrollHeight;
        }

        function focusOnInput() {
            var inputField = document.getElementById('messageEnvoye');
            if (inputField) {
                inputField.focus();
            }
        }

        function sendMessage(type) {
            let id = @json($id);
            PrivateChannel = 'private-chat.' + id + '.' + idRecruiter_Global + '.' + idWorker_Global;
            let messageInput = document.getElementById('messageEnvoye');
            let message = messageInput.value;

            let formData = new FormData();
            formData.append('idRecruiter', idRecruiter_Global);
            formData.append('idWorker', idWorker_Global);
            formData.append('type', type);
            formData.append('_token', '{{ csrf_token() }}');

            if (type === 'text') {
                formData.append('message_data', message);
            } else if (type === 'file') {
                let fileInput = document.getElementById('fileInput');
                let file = fileInput.files[0];
                formData.append('fileName', file.name);

                let reader = new FileReader();
                reader.onloadend = function() {
                    let base64data = reader.result;
                    formData.append('message_data', base64data);
                    sendAjaxRequest(formData);
                }
                reader.readAsDataURL(file);
                return;
            }

            sendAjaxRequest(formData);
        }

        function sendAjaxRequest(formData) {
            $.ajax({
                url: 'send-message',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    document.getElementById('messageEnvoye').value = "";
                    console.log('message envoyé');
                }
            });
        }

        // DOM Manipulation

        $(document).ready(function() {

            let id = @json($id);
            var PrivateChannelNotification = 'private-notification.' + id;

            window.Echo.private(PrivateChannelNotification)
                .listen('NotificationMessage', (event) => {
                    console.log('from the messages blade:', event);
                    updateRoomLastMessage(event.sender, event.content);
                    updateLastMessageTime(event.sender, 'Just now');
                    incrementMessageCount(event.sender);
            });

            $('#messageEnvoye').keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    let messageInput = document.getElementById('messageEnvoye');
                    if (messageInput.value.trim() !== '') {
                        sendMessage('text');
                    }
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const idWorker = urlParams.get('worker_id');
            const nameworker = urlParams.get('name');
            const idRecruiter = urlParams.get('recruiter_id');
            if (idWorker && nameworker && idRecruiter) {
                getPrivateMessages(idWorker, nameworker, idRecruiter);
            }

            $('.messages-area').scroll(function() {
                if ($(this).scrollTop() == 0) {
                    page++;
                    $('#loading').removeClass('d-none');
                    $('#login').addClass('d-none');

                    $.get('/organization/getMessages?page=' + page + '&workerId=' + idWorker_Global + '&recruiterId=' + idRecruiter_Global,
                        function(data) {
                            var messages = data.messages;
                            messages.forEach(function(message) {
                                var messageHTML = createMessageHTML(message);
                                $('.private-messages').prepend(messageHTML);
                            });

                            $('#login').removeClass('d-none');
                            $('#loading').addClass('d-none');
                        });
                }
            });

            window.onload = function() {
                window.Echo.channel('goodwork_database_messages')
                    .listen('NewMessage', (event) => {
                        console.log('New message:', event.message);
                    });
            }

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
                        scrollToBottom('body_room');
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
        });

        document.addEventListener('DOMContentLoaded', function() {
            focusOnInput();
        });

    </script>

    <main style="padding-top: 100px" class="ss-main-body-sec">
        <div class="container" id="recruiters_messages">
            <div class="ss-message-pg-mn-div">
                <div class="row">
                    <div class="col-lg-5 ss-displ-flex">
                        <div class="ss-messg-left-box">
                            <h2>Messages</h2>
                            <div class="ss-opport-mngr-hedr">
                                <ul style="float:left;">
                                    <li style="margin-left:0px;"><button id="published" class="ss-darfts-sec-publsh-btn active">Workers</button></li>
                                </ul>
                            </div>

                            @if ($data)
                                @foreach ($data as $room)
                                    <div onclick="getPrivateMessages('{{ $room['workerId'] }}','{{ $room['fullName'] }}','{{ $room['recruiterId'] }}')" class="ss-mesg-sml-div">
                                        <ul class="ss-msg-user-ul-dv">
                                            @php
                                                $worker = App\Models\User::find($room['workerId']);  
                                            @endphp
                                            @if(isset($worker))       
                                                <img src="{{ isset($worker->image) ? URL::asset('uploads/' . $worker->image) : URL::asset('/frontend/img/profile-pic-big.png') }}"
                                                class="rounded-3" id="preview" style="object-fit: cover;" height="40" width="40" alt="Profile Image" loading="lazy" />
                                            @endif
                                            <li>
                                                <h5>{{ $room['fullName'] }}</h5>
                                                <p id="room_{{ $room['workerId'] }}">
                                                    @if (isset($room['messages'][0]))
                                                        {{ $room['messages'][0]['type'] === 'file' ? (strlen($room['messages'][0]['fileName']) < 16 ? $room['messages'][0]['fileName'] : substr($room['messages'][0]['fileName'], 0, 16) . ' ...') : (strlen($room['messages'][0]['content']) < 16 ? $room['messages'][0]['content'] : substr($room['messages'][0]['content'], 0, 16) . ' ...') }}
                                                    @else
                                                        No messages yet.
                                                    @endif
                                                </p>
                                            </li>
                                        </ul>

                                        <ul style="width:100%" class="ss-msg-notifi-sec">
                                            <li>
                                                <p id="lastMessage_{{ $room['workerId'] }}">{{ $room['lastMessage'] }}</p>
                                            </li><br>
                                            @if ($notificationMessages)
                                                @foreach ($notificationMessages as $notificationMessage)
                                                    @if ($notificationMessage['sender'] == $room['workerId'])
                                                        <li><span id="messagesN_{{ $room['workerId'] }}">{{ $notificationMessage['numOfMessagesStr'] }}</span></li>
                                                    @endif
                                                @endforeach
                                            @else
                                                <li><span class="d-none" id="messagesN_{{ $room['workerId'] }}">0</span></li>
                                            @endif
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

                    <div class="col-lg-7 ss-msg-rply-mn-div">
                        <div id="empty_room">
                            <h5 class="empty_room">no chat was chosen</h5>
                        </div>
                        <div id="body_room" class="d-none prv-msg-rply-mn-div messages-area parentMessages">
                            <div class="ss-msg-rply-profile-sec">
                                <ul>
                                    <li>
                                        <img src="{{ isset($worker->image) ? URL::asset('uploads/' . $worker->image) : URL::asset('/frontend/img/profile-pic-big.png') }}"
                                        class="rounded-3" id="preview" style="object-fit: cover;" height="40" width="40" alt="Profile Image" loading="lazy" />
                                    </li>
                                    <li>
                                        <p id="fullName"></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-msgrply-tody">
                                <span id="loading" class="d-none">
                                    <span id="loadSpan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...
                                </span>
                                <span id="login">
                                    <p>Today</p>
                                </span>
                            </div>
                            <div class="private-messages"></div>
                        </div>
                        <div class="ss-rply-msg-input d-none">
                            <input type="text" id="messageEnvoye" name="fname" placeholder="Express yourself here!">
                            <input type="file" id="fileInput" style="display: none;">
                            <button class="upload-file" id="fileUpload" type="button"><img src="{{ URL::asset('frontend/img/msg-rply-btn.png') }}" /></button>
                        </div>
                        <div class="row" style="margin: 15px 0;">
                            <div class="row justify-content-end" id="fileInfo" style="display: none;">
                                <div class="col-6">
                                    <span style=" margin-left: 16px;" id="fileName"></span>
                                </div>
                                <div style=" display: flex; justify-content: end;" class="col-6">
                                    <button style="width: 30%; border-radius: 100px;" class="ss-prsnl-save-btn button" id="deleteFile">Delete</button>
                                    <button style="width: 30%; border-radius: 100px; margin-left: 10px;" class="ss-prsnl-save-btn button" id="sendFile">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .img_msg{
            object-fit: cover;
            width: 50px;
            height: 50px;
            box-shadow: 0 0 10px 0 rgba(255, 255, 255, .35);
            border-radius: 50% !important;
        }

        .messages-area {
            height: 80vh;
            overflow-y: auto;
        }

        .parentMessages {
            position: relative;
        }

        .ss-msg-rply-mn-div {
            padding: 0px;
        }

        .prv-msg-rply-mn-div {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 30px;
        }
        
        .prv-msg-rply-mn-div::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .prv-msg-rply-mn-div::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.2);
        }

        .prv-msg-rply-mn-div::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.4);
        }

        .prv-msg-rply-mn-div::-webkit-scrollbar-thumb:active {
            background: rgba(0, 0, 0, 0.9);
        }

        .prv-msg-rply-mn-div::-webkit-scrollbar-track-piece:start {
            background: transparent;
            margin-top: 80px;
        }

        .prv-msg-rply-mn-div::-webkit-scrollbar-track-piece:end {
            background: transparent;
            margin-bottom: 10px;
        }  

        .ss-rply-msg-input {
            margin-top: auto;
        }

        .ss-rply-msg-input {
            position: sticky;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 0 20px;
        }

        .upload-file{
            padding-right: 30px;
        }

        .empty_room {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        #loading,
        #login,
        #loadSpan {
            color: black;
        }
    </style>
@endsection

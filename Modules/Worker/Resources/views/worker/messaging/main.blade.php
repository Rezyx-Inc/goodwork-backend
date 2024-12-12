@extends('worker::layouts.main')

@section('content')
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
                                <div onclick="getPrivateMessages('{{ $room['recruiterId'] }}','{{ $room['organizationId'] }}','{{ $room['fullName'] }}')"
                                    class="ss-mesg-sml-div">
                                    <ul class="ss-msg-user-ul-dv">
                                        <li><img class="img_msg" src="{{ URL::asset($room['recruiterImage']) }}" /></li>
                                        <li>
                                            <h5>{{ $room['fullName'] }}</h5>
                                            <p id="room_{{ $room['recruiterId'] }}">
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
                                            <p id="lastMessage_{{ $room['recruiterId'] }}">{{ $room['lastMessage'] }}
                                            </p>
                                        </li><br>
                                        @if ($notificationMessages)
                                            @foreach ($notificationMessages as $notificationMessage)
                                                @if ($notificationMessage['sender'] == $room['recruiterId'])
                                                    <li><span
                                                            id="messagesN_{{ $room['recruiterId'] }}">{{ $notificationMessage['numOfMessagesStr'] }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @else
                                            <li><span class="d-none" id="messagesN_{{ $room['recruiterId'] }}">0</span>
                                            </li>
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

@include('worker::worker.messaging.messaging_scripts');

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

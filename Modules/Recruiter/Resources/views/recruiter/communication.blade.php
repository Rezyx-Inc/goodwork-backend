@extends('recruiter::layouts.main')

@section('content')
<main style="padding-top: 120px" class="ss-main-body-sec">
    <div class="container">
        <div class="ss-message-pg-mn-div">
            <div class="row">
                <div class="col-lg-5 ss-displ-flex">
                    <div class="ss-messg-left-box">
                        <h2>Messages</h2>

                        <div class="ss-mesg-sml-div">
                            <ul class="ss-msg-user-ul-dv">
                                <li><img src="{{URL::asset('public/frontend/img/message-img1.png')}}" /></li>
                                <li>
                                    <h5>Recruiter #01</h5>
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
                                <li><img src="{{URL::asset('public/frontend/img/message-img2.png')}}" /></li>
                                <li>
                                    <h5>Recruiter #02</h5>
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
                                <li><img src="{{URL::asset('public/frontend/img/message-img3.png')}}" /></li>
                                <li>
                                    <h5>Recruiter #03</h5>
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
                                <li><img src="{{URL::asset('public/frontend/img/message-img4.png')}}" /></li>
                                <li>
                                    <h5>Recruiter #04</h5>
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
                                <li>
                                    <img id="userprofile" src="{{ asset('frontend/img/profile-pic-big.png') }}" onerror="this.onerror=null; this.src = '{{USER_IMG}}';" id="preview" width="50px" height="50px" style="object-fit: cover;" />
                                </li>
                                <li>
                                    <h6 id="username"></h6>
                                    <p id="profession"></p>
                                    <p id="usermobile" class="d-none m-0 p-0"></p>
                                </li>
                            </ul>
                        </div>

                        <div id="message-container">
                            <!-- <div class="ss-msgrply-tody">
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
                            </div> -->
                        </div>
                        <!-- <div class="ss-rply-msg-input"> -->
                        <form class="ss-rply-msg-input" id="messageForm">
                            <input type="text" id="message" name="message" placeholder="Express yourself here!">
                            <button type="submit"><img src="{{URL::asset('public/frontend/img/msg-rply-btn.png')}}" /></button>
                        </form>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js">
</script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyCp37GvlmtpN1AQx72On_BR0RM8ccjElbo",
        authDomain: "goodwork-3a716.firebaseapp.com",
        databaseURL: "https://goodwork-3a716-default-rtdb.firebaseio.com",
        projectId: "goodwork-3a716",
        storageBucket: "goodwork-3a716.appspot.com",
        messagingSenderId: "242030976180",
        appId: "1:242030976180:web:2bf390eca793f510264be1",
        measurementId: "G-30VN0S9H12"
    };

    // message:"Hi"
    // receiver:"GWW000015"
    // sender:"GWU000032"
    // senderfind:"Nurse"
    // time:1698736561602
    // type:"text"

    firebase.initializeApp(firebaseConfig);

    var messagesRef = firebase.database()
        .ref('Message');

    document.getElementById('messageForm')
        .addEventListener('submit', submitForm);

    function submitForm(e) {
        e.preventDefault();
        var message = document.getElementById('message').value;
        saveMessage(message);
        document.getElementById('messageForm').reset();
    }

    function saveMessage(message) {
        var currentURL = window.location.href;
        var parts = currentURL.split('/');
        var id = parts[parts.length - 1];
        var senderid = '<?php echo auth()->guard('recruiter')->user()->id ?>';
        var currentDate = new Date();
        var timestamp = currentDate.getTime();
        var messagesRef = firebase.database().ref('Message');
        // if (localStorage.getItem("nurse_id")) {
        //     nurse_id = localStorage.getItem("nurse_id");
        // }
        var chatcolumn = "m_" + senderid + "_" + localStorage.getItem("nurse_id");
        var newMessageKey = messagesRef.child(chatcolumn).push().key;
        var newMessageRef = messagesRef.child(chatcolumn).child(newMessageKey);
        newMessageRef.set({
            message: message,
            receiver: localStorage.getItem("nurse_id"),
            sender: senderid,
            senderfind: "RECRUITER",
            time: timestamp,
            type: "text"
        });

        var username = document.getElementById('username');
        let nursename = "";
        let nurseprofile = "";
        let nursemobile = "";
        if (username) {
            nursename = username.textContent;
        }
        var userprofile = document.getElementById('userprofile');
        if (userprofile) {
            nurseprofile = userprofile.src;
        }
        var usermobile = document.getElementById('usermobile');
        if (usermobile) {
            nursemobile = usermobile.textContent;
        }

        var userRef = firebase.database().ref('Users');

        // var userfirebaseid = "";

        // // userRef.on('value', function(snapshot) {
        // //     snapshot.forEach(function(childSnapshot) {
        // //         var messageData = childSnapshot.val();
        // //         var messageKey = childSnapshot.key;
        // //         if(messageData.Nu_id == localStorage.getItem("nurse_id") && messageData.Sm_id == senderid){
        // //             userfirebaseid = messageKey;
        // //             console.log(messageKey);
        // //             console.log(userfirebaseid);
        // //             if(userfirebaseid != ""){
        // //                 return;
        // //             }
        // //         }else{
        // //             userfirebaseid = '0';
        // //         }
        // //     });
        // // });
        // userRef.on('value', function(snapshot) {
        //     snapshot.forEach(function(childSnapshot) {
        //         var messageData = childSnapshot.val();
        //         var messageKey = childSnapshot.key;

        //         if (messageData.Nu_id == localStorage.getItem("nurse_id") && messageData.Sm_id == senderid) {
        //             userfirebaseid = messageKey;
        //             console.log('Found user with ID: ' + userfirebaseid);
        //         }
        //     });

        //     if (userfirebaseid === '0') {
        //         console.log('User not found');
        //     }
        // });
        
        // var userRef = firebase.database().ref('Users');
        // var userData = {
        //     Nu_id: localStorage.getItem("nurse_id"),
        //     Nu_image: nurseprofile,
        //     Nu_mobile: nursemobile,
        //     Nu_name: nursename,
        //     Sm_id: senderid,
        //     Sm_image: '<?php echo auth()->guard('recruiter')->user()->image ?>',
        //     Sm_mobile: '<?php echo auth()->guard('recruiter')->user()->mobile ?>',
        //     Sm_name: '<?php echo auth()->guard('recruiter')->user()->first_name ?>' + " " + '<?php echo auth()->guard('recruiter')->user()->last_name ?>',
        //     Worker_userid: "",
        //     isOnline: "Onlineww", 
        //     isRecOnline: "Online", 
        //     message: message,
        //     time: timestamp,
        // };
        // if (userfirebaseid) {
           
        //     userRef.child(userfirebaseid).once('value').then(function(snapshot) {
        //         if (snapshot.exists()) {
        //             // User exists, update the data
        //             userRef.child(userfirebaseid).update(userData);
        //             console.log('Record updated with ID: ' + userfirebaseid);
        //         } else {
        //             // User does not exist, create a new record
        //             var newUserRef = userRef.push();
        //             newUserRef.set(userData);
        //             console.log('New record created with ID: ' + newUserRef.key);
        //         }
        //     });
        // } else {
        //     // userRef.push(userData);
        // }
        var userRef = firebase.database().ref('Users');
        var userfirebaseid = "0";

        userRef.on('value', function(snapshot) {
            snapshot.forEach(function(childSnapshot) {
                var messageData = childSnapshot.val();
                var messageKey = childSnapshot.key;

                if (messageData.Nu_id == localStorage.getItem("nurse_id") && messageData.Sm_id == senderid) {
                    userfirebaseid = messageKey;
                }
            });

            if (!userfirebaseid) {
                userfirebaseid = '0';
            }
        });

        var userData = {
            Nu_id: localStorage.getItem("nurse_id"),
            Nu_image: nurseprofile,
            Nu_mobile: nursemobile,
            Nu_name: nursename,
            Sm_id: senderid,
            Sm_image: '<?php echo auth()->guard('recruiter')->user()->image ?>',
            Sm_mobile: '<?php echo auth()->guard('recruiter')->user()->mobile ?>',
            Sm_name: '<?php echo auth()->guard('recruiter')->user()->first_name ?>' + " " + '<?php echo auth()->guard('recruiter')->user()->last_name ?>',
            Worker_userid: "",
            isOnline: "Online", 
            isRecOnline: "Online", 
            message: message,
            time: timestamp,
        };

        console.log(userfirebaseid);

        if (userfirebaseid) {
            userRef.child(userfirebaseid).once('value').then(function(snapshot) {
                if (snapshot.exists()) {
                    userRef.child(userfirebaseid).update(userData);
                    console.log('Record updated with ID: ' + userfirebaseid);
                } else {
                    var newUserRef = userRef.push();
                    newUserRef.set(userData);
                    console.log('New record created with ID: ' + newUserRef.key);
                }
            });
        } else {
            console.log('userfirebaseid is empty. Cannot perform database operation.');
        }
        
    }

    function fetchMessages() {
        var senderid = '<?php echo auth()->guard('recruiter')->user()->id ?>';
        var chatcolumn = "m_" + senderid + "_" + localStorage.getItem("nurse_id");
        var messagesRef = firebase.database().ref('Message/'+ chatcolumn);
        console.log(messagesRef, 'messageref');
        var messageContainer = document.getElementById('message-container');
        messagesRef.on('value', function(snapshot) {
            messageContainer.innerHTML = '';
            snapshot.forEach(function(childSnapshot) {
                var messageData = childSnapshot.val();
                var messageKey = childSnapshot.key;
                var messageDataArray = [];
                var messageObject = {
                    "id": messageKey,
                    "message": messageData.message,
                    "receiver": messageData.receiver,
                    "sender": messageData.sender,
                    "senderfind": messageData.senderfind,
                    "time": messageData.time,
                    "type": messageData.type
                };
                let msgtype = "";
                if (messageData.senderfind == "RECRUITER") {
                    msgtype = "blue-dv"
                } else {
                    msgtype = "recrut-dv"
                }
                messageDataArray.push(messageObject);
                var messageDiv = document.createElement('div');
                messageDiv.className = "ss-msg-rply-" + msgtype;
                var messageContent = document.createElement('p');
                messageContent.textContent = messageObject.message;
                // var senderName = document.createElement('h6');
                // senderName.textContent = messageObject.senderfind;
                var timestamp = document.createElement('span');
                // messageDiv.appendChild(senderName);
                messageDiv.appendChild(messageContent);
                messageContainer.appendChild(messageDiv);
            });
        });
    }

    fetchMessages();

    window.onload = function() {
        fetchSingleUserChat()
    }

    function fetchSingleUserChat() {
        var nurse_id = "";
        if (localStorage.getItem("nurse_id")) {
            nurse_id = localStorage.getItem("nurse_id");
        } else {

        }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            var url = `{{ url('recruiter/get-single-nurse-details') }}/${nurse_id}`;
            $.get(url, { '_token': csrfToken }, function(result) {
                console.log(result.data);
                let fullname = result.data.first_name + " " + result.data.last_name;
                if(fullname){
                    document.getElementById("username").textContent = fullname; 
                }
                if(result.data.profession){
                    document.getElementById("profession").textContent = result.data.profession; 
                }
                if(result.data.mobile){
                    document.getElementById("usermobile").textContent = result.data.mobile; 
                }
                console.log(result.data.image);
                if(result.data.image){
                    console.log('{{ asset("public/images/nurses/profile/") }}' + '/' + result.data.image);
                    // document.getElementById("userprofile").src = "URL::asset('public/images/nurses/profile/)" + result.data.image; 
                    document.getElementById("userprofile").src = '{{ asset("public/images/nurses/profile/") }}' + '/' + result.data.image;
                }
            })
            .fail(function(error) {
                // Handle errors
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
</script>
@endsection
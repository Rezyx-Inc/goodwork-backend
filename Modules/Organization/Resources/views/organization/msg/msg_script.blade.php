<script>
    
    $(document).ready(function () {

        let id = @json($id);
    var PrivateChannelNotification = 'private-notification.' + id;
    // Listen for Message Notification messages event
    window.Echo.private(PrivateChannelNotification)
    .listen('NotificationMessage', (event) => {
        let room_last_messages = document.getElementById('room_' + event.sender);
    if (room_last_messages) {
            if (event.content.length > 16) {
        room_last_messages.innerHTML = event.content.substring(0, 16) + ' ...';
            } else {
        room_last_messages.innerHTML = event.content;
            }
        }
    let lastMessage = document.getElementById('lastMessage_' + event.sender);
    if (lastMessage) {
        lastMessage.innerHTML = 'Just now';
        }


    let messagesN = document.getElementById('messagesN_' + event.sender);
    if (messagesN) {
        messagesN.classList.remove("d-none");
    messagesN.innerHTML = parseInt(messagesN.innerHTML) + 1;
        }
    });
});
    var idWorker_Global = '';
    var idOrganization_Global = '';


    var PrivateChannel = '';
    var page = 1; // Initialize the page number (the number of the 10 messages to be loaded next)
    function getPrivateMessages(idWorker, fullName, idOrganization) {

        // Leave the current channel
        window.Echo.leave(PrivateChannel);


    const data = {
        sender: idWorker,
    _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
};

    $.ajax({
        url: 'read-recruiter-message-notification', // Replace 'read-message-notification' with the actual path to your route
    type: 'POST',
    data: data,
    success: function (response) {
        // Handle success response
        // Navigate to a new page after the AJAX call is successful
        let messagesN = document.getElementById('messagesN_' + idWorker);
    if (messagesN) {
        messagesN.classList.add("d-none");
    messagesN.innerHTML = 0;
        }
    },
    error: function (xhr, status, error) {
        console.error('Error:', error);
        // Handle errors here
    }
});

    document.getElementById('fullName').innerHTML = fullName;

    page = 1;
    document.getElementById('empty_room').classList.add("d-none");
    document.getElementById('body_room').classList.remove("d-none");
    idWorker_Global = idWorker;
    idOrganization_Global = idOrganization;

    let id = @json($id);

    PrivateChannel = 'private-chat.' + idOrganization + '.' + id + '.' + idWorker_Global;

    let messageText = document.getElementById('message');

    function createRealMessageHTML(message) {
    var senderClass;
    if (message.senderRole == 'RECRUITER') {
        senderClass = 'ss-msg-rply-blue-dv';
    } else if (message.senderRole == 'ORGANIZATION') {
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
    });

    // Listen for NewPrivateMessage event on the goodwork_database_private-private-chat.UserId channel

    window.Echo.private(PrivateChannel)
    .listen('NewPrivateMessage', (event) => {
        var messageHTML = createRealMessageHTML(event);
    $('.private-messages').append(messageHTML);
    var element = document.getElementById('body_room');
    element.scrollTop = element.scrollHeight;
    });

    $('.private-messages').html('');
    $.get('/recruiter/getMessages?page=1&workerId=' + idWorker + '&organizationId=' + idOrganization, function (
    data) {
    // Parse the returned data
    var messages = data.messages;


    // Function to create the HTML for a message
    function createMessageHTML(message) {
        var senderClass;
    if (message.sender == 'RECRUITER') {
        senderClass = 'ss-msg-rply-blue-dv';
        } else if (message.sender == 'ORGANIZATION') {
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
    messages.forEach(function (message) {
        var messageHTML = createMessageHTML(message);
    $('.private-messages').prepend(messageHTML);
    });
    var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));

});
}

    $(document).ready(function () {
const urlParams = new URLSearchParams(window.location.search);
    const idWorker = urlParams.get('worker_id');
    const nameworker = urlParams.get('name');
    const idOrganization = urlParams.get('recruiter_id');
    if (idWorker && nameworker && idOrganization) {
        getPrivateMessages(@json($idWorker), nameworker, idOrganization);
}

    var messagesArea = $('.messages-area');
    messagesArea.scrollTop(messagesArea.prop('scrollHeight'));


    $('.messages-area').scroll(function () {
    if ($(this).scrollTop() == 0) { // If the scrollbar is at the top
        page++; // Increment the page number

    $('#loading').removeClass('d-none');
    $('#login').addClass('d-none');
    // Make an AJAX request to the API
    $.get('/recruiter/getMessages?page=' + page + '&workerId=' + idWorker_Global +
    '&organizationId=' + idOrganization_Global,
    function (data) {
                // Parse the returned data
                var messages = data.messages;

    // Function to create the HTML for a message
    function createMessageHTML(message) {
                    //  var senderClass = message.sender == 'ORGANIZATION' ? 'ss-msg-rply-blue-dv' : 'ss-msg-rply-recrut-dv';


                    var senderClass;
    if (message.sender == 'RECRUITER') {
        senderClass = 'ss-msg-rply-blue-dv';
                    } else if (message.sender == 'ORGANIZATION') {
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
    messages.forEach(function (message) {
                    var messageHTML = createMessageHTML(message);
    $('.private-messages').prepend(messageHTML);
                });

    $('#login').removeClass('d-none');
    $('#loading').addClass('d-none');
            });
    }
});
});

    window.onload = function () {
        // Listen for NewMessage event on the goodwork_database_messages channel : PUBLIC MESSAGES
        window.Echo.channel('goodwork_database_messages')
            .listen('NewMessage', (event) => {
            });
}

    function sendMessage(type) {
        let id = @json($id);
    PrivateChannel = 'private-chat.' + idOrganization_Global + '.' + id + '.' + idWorker_Global;
    let messageInput = document.getElementById('messageEnvoye');
    let message = messageInput.value;

    let formData = new FormData();
    formData.append('idOrganization', idOrganization_Global);
    formData.append('idWorker', idWorker_Global);
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
    reader.onloadend = function () {
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
            success: function () {
                document.getElementById('messageEnvoye').value = "";
            }
        });
}



    $(document).ready(function () {
        $('#messageEnvoye').keypress(function (e) {
            if (e.which == 13) { // 13 is the key code for the enter key
                e.preventDefault(); // Prevent the default action (form submission)
                let messageInput = document.getElementById('messageEnvoye');
                if (messageInput.value.trim() === '') {
                    return;
                } else {
                    sendMessage('text'); // Call your function
                }


            }
        });
});



    window.onload = function () {
        let fileUpload = document.getElementById('fileUpload');
    let fileInput = document.getElementById('fileInput');

    if (fileUpload) {
        fileUpload.addEventListener('click', function () {
            if (fileInput) {
                fileInput.click();
            }
        });
}

    if (fileInput) {
        fileInput.addEventListener('change', function () {
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
        deleteFile.addEventListener('click', function () {
            document.getElementById('fileInfo').style.display = 'none';
            document.getElementById('fileInput').value = '';
        });
}

    if (sendFile) {
        sendFile.addEventListener('click', function () {
            sendMessage('file');
            document.getElementById('fileInfo').style.display = 'none';
        });
}
}

</script>
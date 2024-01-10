<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>

  <h1>Private message test</h1>
  <p>
    Try publishing an event to channel <code>private-chat.GWU000002</code>
   the arraiving message : <span id="message">my-event</span>.
  </p>

  <script>

  window.onload = function() {
    let id = @json($id);
    let PrivateChannel = 'private-chat.'+id+".GWU000005";

    let messageText = document.getElementById('message');
    console.log(messageText);

    // Listen for NewMessage event on the goodwork_database_messages channel : PUBLIC MESSAGES
    window.Echo.channel('goodwork_database_messages')
        .listen('NewMessage', (event) => {
            console.log('New message:', event.message);
        });

    // Listen for NewPrivateMessage event on the goodwork_database_private-private-chat.UserId channel

    window.Echo.private(PrivateChannel)
        .listen('NewPrivateMessage', (event) => {
           // console.log('event:', event);
            messageText.innerHTML = "message : " + event.message + " role : " + event.senderRole
            + " employer ID : " + event.EmployerId + " worker ID : " + event.WorkerId;
        });
    
  }
  </script>
</body>
</html>

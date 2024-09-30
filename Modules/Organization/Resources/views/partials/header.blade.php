@php
$user = auth()->guard('organization')->user();
@endphp

<!-- Navbar -->
<nav id="main-navbar" class="ss-hed-top-fixd navbar navbar-expand-lg navbar-light fixed-top">
  <!-- Container wrapper -->
  <div class="container-fluid ss-top-hed-cont-pd">

    <!-- Brand -->

    <div class="col-lg-4">
      <div class="ss-dash-wel-div">
        <h5>Hi, {{ $user->first_name }}!</h5>
      </div>
    </div>


    <div class="col-lg-4">
      <div class="ss-dash-hed-noti-divv">
        <!-- Right links -->
        <ul class="ssnavbar-nav">
          <!-- Search form -->

          <!-- Notification dropdown -->

          <li class="nav-item dropdown ss-bell-sec-li">
            <a class="ss-bell-sec nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell"></i>
              {{-- <span id="notifications_counter_organization" class="badge rounded-pill badge-notification bg-danger header_notif">{{$unreadNotificationsCount}}</span> --}}
              <span id="notifications_counter" class="d-none badge rounded-pill badge-notification bg-danger header_notif">
                <div class="my-btn">
                  <div class="my-btn-border"></div>
                  <i class="fas btn-bell"></i>
                </div>
                </span>
            </a>
            <ul id="list_of_notifications" class="dropdown-menu dropdown-menu-end " aria-labelledby="navbarDropdownMenuLink">
              <li><a id="none_notification" class="dropdown-item" href="#">You don't have any notifications</a></li>
              {{-- <li><a id="offer_notification" class="d-none dropdown-item" href="{{route('organization-opportunities-manager')}}">New Jobs Information</a></li>
              <li>
                <a id="offer_notification" class="d-none dropdown-item" href="{{route('organization-application')}}">You got new offer update !</a>
              </li> --}}
            </ul>
          </li>


          <!-- Icon dropdown -->

          <!-- Avatar -->
          <li class="nav-item dropdown">
            <a class="ss-hed-user-log-sec nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
              <img src="{{ URL::asset('uploads/' . $user->image) }}" onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-icon-img.png') }}'" class="rounded-circle" height="40" width="40" alt="" loading="lazy" />
              <span class="color-dark">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
              <li><a class="dropdown-item"  href="{{route('organization-profile', ['type' => 'profile']) }}" >My profile</a></li>
              <li><a class="dropdown-item" href="{{route('organization-profile', ['type' => 'setting']) }}">Settings</a></li>
              <li><a class="dropdown-item" href="{{route('organization-logout')}}">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>


    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <!-- Container wrapper -->
</nav>


<script>


var offerNotificationMessages = @json($offersNotificationMessages);

function handleOfferNotificationClick(event) {
    event.preventDefault();
    const offerId = event.target.id;
    const senderId = event.target.getAttribute('snd');

    const data = {
        offerId : offerId,
        senderId: senderId,
        _token: $('meta[name="csrf-token"]').attr('content')
    };


    $.ajax({
        url: '{{route("read-organization-offer-notification")}}',
        type: 'POST',
        data: data,
        success: function(response) {
            console.log('Success:', response);
            window.location.href = '{{ route("organization-application") }}';
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

$(document).ready (function () {
  console.log('{{ $unreadOffersNotificationsCount }}');

  console.log('Offer Notification Messages :' , offerNotificationMessages );
  console.log(offerNotificationMessages)

  if('{{ $unreadOffersNotificationsCount }}' > 0){

    document.getElementById('none_notification').classList.add('d-none');
    let count = document.getElementById('notifications_counter');
                    // count.innerHTML = parseInt(count.innerHTML) + 1;
                    count.classList.remove('d-none');
                    let list_of_notifications = document.getElementById('list_of_notifications');
    list_of_notifications.innerHTML = '';
    offerNotificationMessages.forEach(notification => {
      let status = notification.type;
    let message = notification.full_name +' ' + offer_status_messages[status] + ' "' + notification.job_name + '"';
    let li = document.createElement('li');
    let a = document.createElement('a');
    a.id = notification.offer_id;
    a.classList.add('dropdown-item');
    a.href = "{{route('organization-application')}}";
    a.innerHTML = message;

    a.setAttribute('snd', notification.sender);
    a.setAttribute('onclick', 'handleOfferNotificationClick(event)');
    li.appendChild(a);
    list_of_notifications.appendChild(li);
  });
  }
});




function handleNotificationClick(event) {
    event.preventDefault(); // Prevent the default link behavior

    // Extract the ID of the clicked <a>
    const senderId = event.target.id; // Adjusted to use event.target.id to get the clicked element's ID

    // Prepare the data to be sent
    const data = {
        sender: senderId,
        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
    };

    // Send the ID to your reading route using jQuery's $.ajax
    $.ajax({
        url: '{{ route("read-organization-message-notification") }}', // Replace 'read-message-notification' with the actual path to your route
        type: 'POST',
        data: data,
        success: function(response) {
            console.log('Success:', response);
            // Handle success response
            // Navigate to a new page after the AJAX call is successful
            window.location.href = '{{ route("organization-messages") }}';
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            // Handle errors here
        }
    });
}
function handleJobNotificationClick(event , senderId){
  event.preventDefault(); // Prevent the default link behavior

  // Extract the ID of the clicked <a>
  const jobId = event.target.id; // Adjusted to use event.target.id to get the clicked element's ID


  // Prepare the data to be sent
  const data = {
      sender: senderId,
      jobId: jobId,
      _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
  };

  // Send the ID to your reading route using jQuery's $.ajax
  $.ajax({
      url: '{{ route("read-organization-job-notification") }}', // Replace 'read-message-notification' with the actual path to your route
      type: 'POST',
      data: data,
      success: function(response) {
          console.log('Success:', response);
          // Handle success response
          // Navigate to a new page after the AJAX call is successful
           window.location.href = '{{ route("organization-application") }}';
      },
      error: function(xhr, status, error) {
          console.error('Error:', error);
          // Handle errors here
      }
  });
}



  $(document).ready(function(){
    console.log('{{ $unreadNotificationsCount }}');

    let notificationMessages = @json($notificationMessages);

  console.log('Notification Messages :' , notificationMessages );
  console.log(notificationMessages)



  if('{{ $unreadNotificationsCount }}' > 0){
    let none_notification = document.getElementById('none_notification');
    if(none_notification){
      none_notification.classList.add('d-none');
    }

    let count = document.getElementById('notifications_counter');
                    // count.innerHTML = parseInt(count.innerHTML) + 1;
                    count.classList.remove('d-none');
                    let list_of_notifications = document.getElementById('list_of_notifications');
  list_of_notifications.innerHTML = '';
  notificationMessages.forEach(notification => {
    let message = notification.numOfMessagesStr > 1 ? ' messages' : ' message';
    let li = document.createElement('li');
    let a = document.createElement('a');
    a.id = notification.sender;
    a.classList.add('dropdown-item');
    a.href = "{{route('organization-messages')}}";
    a.setAttribute('onclick', 'handleNotificationClick(event)');
    a.innerHTML = notification.full_name + ' sent you ' + notification.numOfMessagesStr + message;
    li.appendChild(a);
    list_of_notifications.appendChild(li);
  });
  }


  let notificationJobs = @json($jobsNotificationMessages);
  console.log('Notification Jobs :' , notificationJobs );
  if('{{$unreadJobsNotificationsCount}}' > 0){
    let none_notification = document.getElementById('none_notification');
    if(none_notification){
      none_notification.classList.add('d-none');
    }
    let count = document.getElementById('notifications_counter');
                    // count.innerHTML = parseInt(count.innerHTML) + 1;
                    if(count){
                      count.classList.remove('d-none');
                    }

                    let list_of_notifications = document.getElementById('list_of_notifications');

  notificationJobs.forEach(notification => {

    let li = document.createElement('li');
    let a = document.createElement('a');
    a.id = notification.jobId;
    a.classList.add('dropdown-item');
    a.href = "{{route('organization-application')}}";
    a.setAttribute('onclick', 'handleJobNotificationClick(event, "' + notification.sender + '")');
    a.innerHTML =  notification.full_name + ' applied to job ' + notification.job_name ;
    li.appendChild(a);
    list_of_notifications.appendChild(li);
  });
  }




    var user_id = '{{ $user->id }}';
    var PrivateChannelNotification = 'private-notification.' + user_id;
    window.Echo.private(PrivateChannelNotification)
                .listen('NotificationMessage', (event) => {

                  // remove none notification message if there is any
                  let none_notification = document.getElementById('none_notification');
                  if(none_notification){
                    none_notification.classList.add('d-none');
                  }

                  // preparing the new notification if there is no notification of the current sender
                  let new_notification = {
                                            "id": event.sender,
                                            "numOfMessagesStr": 1,
                                            "sender": event.sender,
                                            "full_name": event.full_name,
                                            "job_name": event.job_name,
                                            "jobId": event.jobId
                                        };

                    // this is for checking if a notification of a specefic sender is already there
                  let check_for_notification = false;
                    // if there is no notifications in the db then add the new notification
                    if(notificationMessages.length == 0){
                      notificationMessages.push(new_notification);
                    }else{
                      // if there is already a notification of the sender then increment the number of messages by 1 of that specific sender
                      notificationMessages.forEach(notification => {
                        if(notification.sender == event.sender){
                          let numOfMessages = parseInt(notification.numOfMessagesStr) + 1;
                          notification.numOfMessagesStr = numOfMessages;
                          check_for_notification = true;
                          return;
                        }
                      });
                      if(check_for_notification == false){
                        notificationMessages.push(new_notification);
                      }
                    }

                    console.log('New Notification :', event);
                    let count = document.getElementById('notifications_counter');

                    notificationMessages.forEach(notification => {

                      if(notification.sender == event.sender){
                        let notification_list = document.getElementById(notification.sender);
                        if(notification_list){
                          notification_list.innerHTML = notification.full_name + ' sent you ' + notification.numOfMessagesStr + ' messages.';
                        }else{
                          let message = notification.numOfMessagesStr > 1 ? ' messages' : ' message';
                          let li = document.createElement('li');
                          let a = document.createElement('a');
                          a.id = notification.sender;
                          a.classList.add('dropdown-item');
                          a.href = "{{route('organization-messages')}}";
                          a.setAttribute('onclick', 'handleNotificationClick(event)');
                          a.innerHTML = notification.full_name + ' sent you ' + notification.numOfMessagesStr + message;
                          li.appendChild(a);
                          list_of_notifications.appendChild(li);
                        }
                      }
                    });
                    count.classList.remove('d-none');

                    console.log(list_of_notifications);
                    console.log(count);
                });
});

window.Echo.private('private-job-notification.' + '{{ $user->id }}')
.listen('NotificationJob', (e) => {
        console.log('Notification Message :', e);
        let count = document.getElementById('notifications_counter');

                    if(count){
                      count.classList.remove('d-none');
                    }
        let none_notification = document.getElementById('none_notification');
        if(none_notification){
          none_notification.classList.add('d-none');
        }
        let li = document.createElement('li');
        let a = document.createElement('a');
        a.id = e.jobId;
        a.classList.add('dropdown-item');
        a.href = "{{route('organization-application')}}";
        a.setAttribute('onclick', 'handleJobNotificationClick(event, "' + e.sender + '")');
        a.innerHTML =  e.full_name + ' applied to job ' + e.job_name ;
        li.appendChild(a);
        list_of_notifications.appendChild(li);
    });


    var offer_status_messages = {
    "Offered": "Counter your offer for the job",
    "Rejected": "rejected your offer for the job",
    "Hold": "accept your offer for the job"
};

window.Echo.private('private-offer-notification.' + '{{ $user->id }}')
.listen('NotificationOffer', (e) => {
  // remove none notification message if there is any
  let none_notification = document.getElementById('none_notification');
                  if(none_notification){
                    none_notification.classList.add('d-none');
                  }

                  let new_offer_notification = {
                                            "id": e.sender,
                                            "type": e.type,
                                            "sender": e.sender,
                                            "full_name": e.full_name,
                                            "job_name": e.job_name,
                                            "offer_id": e.offer_id
                                        };

                    // this is for checking if a notification of a specefic sender is already there
                  offerNotificationMessages.forEach(notification => {
                    if(notification.offer_id == e.offerId){
                      // delete the notification
                      let element = document.getElementById(notification.offer_id);
                          if (element) {
                            let parent_li = element.parentElement;
                            if (parent_li) {
                              parent_li.remove();
                            }else{
                              console.log('Parent Element not found:', notification.offer_id);
                            }
                          } else {
                              console.log('Element not found:', notification.offer_id);
                          }

                    }
                  });

    console.log('Offer Notification Message :', e);
    let status = e.type;
    let message = e.full_name +' ' + offer_status_messages[status] + ' "' + e.job_name + '"';
    console.log(message);
    let count = document.getElementById('notifications_counter');
    count.classList.remove('d-none');
    let list_of_notifications = document.getElementById('list_of_notifications');
    let li = document.createElement('li');
    let a = document.createElement('a');
    a.id = e.offerId;
    a.classList.add('dropdown-item');
    a.href = "{{route('organization-application')}}";
    a.innerHTML = message;
    a.setAttribute('snd', e.sender); // Add the 'snd' attribute with the value of notification.sender
    a.setAttribute('onclick', 'handleOfferNotificationClick(event)');
    li.appendChild(a);
    list_of_notifications.appendChild(li);
    console.log(list_of_notifications);
    console.log(count);

});
</script>

<style>
  span.badge.rounded-pill.badge-notification.bg-danger{

       /* width: 7px; */
     line-height: 5px;
     height: 7px;
     background: none !important;
     border-radius: 100px !important;
     z-index: 9999;
     position: absolute;
     display: block;
     /* color: #fff !important; */
     top: 10px;
     /* right: 7px; */
     padding: 0;
     font-size: 12px;
  }


 .my-btn, .my-btn-border, .btn-bell {
   border-radius: 50%;
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
 }
 .my-btn {
   height: 9px;
   width: 9px;
   box-shadow: -1px 2px 10px #999;
   background: #ef7575;
   animation-name: col;
   animation-duration: 1s;
   animation-iteration-count: infinite;
 }
 .my-btn-border {
   height: 7px;
   width: 7px;
   border: 1px solid #ef7575 !important;
   animation-name: bord-pop;
   animation-duration: 2s;
   animation-iteration-count: infinite;
   box-shadow: 2px 2px 5px #ccc, -2px -2px 5px #ccc ;
 }
 .btn-bell {
   color: white;
   font-size: 20px;
   animation-name: bell-ring;
   animation-duration: 1s;
   animation-iteration-count: infinite;
 }
 @keyframes bord-pop {
   0% {
     transform: translate(-50%, -50%);
   }
   50% {
     transform: translate(-50%, -50%) scale(1.9);
     opacity: 0.1;
   }
   100% {
     transform: translate(-50%, -50%) scale(1.9);
     opacity: 0;
   }
 }
 @keyframes col {
   0% {
     transform: scale(1) translate(0,0);
   }
   10% {
     transform: scale(1.1) translate(0,0);
   }
   75% {
     transform: scale(1) translate(0,0);
   }
   100% {
     transform: scale(1) translate(0,0);
   }
 }
 @keyframes bell-ring {
   0% {
     transform: translate(-50%, -50%);
   }
   5%, 15% {
     transform: translate(-50%, -50%) rotate(25deg);
   }
   10%, 20% {
     transform: translate(-50%, -50%) rotate(-25deg);
   }
   25%  {
     transform: translate(-50%, -50%) rotate(0deg);
   }
   100% {
     transform: translate(-50%, -50%) rotate(0deg);
   }
 }



 </style>

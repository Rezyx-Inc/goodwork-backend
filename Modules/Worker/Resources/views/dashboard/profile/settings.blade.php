<form id="worker-profile-form" onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
    <!-- first form slide Basic Information -->

    <div class=" page slide-page">
        @include('worker::dashboard.profile.settings.professional_info')
        @include('worker::dashboard.profile.settings.personal_info')
    </div>
</form>

@include('worker::dashboard.profile.settings.upload_document_modal')


<style>
    /* for collapse */

    .btn.first-collapse,
    .btn.first-collapse:hover,
    .btn.first-collapse:focus,
    .btn.first-collapse:active {
        background-color: #fff8fd;
        color: rgb(65, 41, 57);
        font-size: 14px;
        font-family: 'Neue Kabel';
        font-style: normal;
        width: 100%;
        background: #FFEEEF;
    }
    .ss-pers-info-form-mn-dv #worker-profile-form .ss-form-group label {
        text-align: start;
        padding: 5px 13px;
    }
</style>


<script>

    // Save on page exit
    window.addEventListener('beforeunload', function() {
        // Call the saveData function to send the AJAX request
        saveInfos();
    });

    // Save change every 50 seconds
    setInterval(() => {
        saveInfos();
    }, 5000);

    function saveInfos() {
        // Perform basic validation if needed
        if (!validateBasicInfo()) {
            return; // Skip the save if validation fails
        }

        // Get the form element
        let form = document.getElementById('worker-profile-form');
        let formData = new FormData(form);

        // Send the data silently using fetch with keepalive
        fetch('/worker/update-worker-profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            keepalive: true // Ensures the request completes even if the page unloads
        }).then(response => {
            // console.log("Silent save successful");
        }).catch(error => {
            // console.error("Silent save failed", error);
        });
    }

</script>
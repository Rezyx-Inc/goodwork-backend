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


<!-- Static Auto-Save Notification -->
<div class="autoSaveBox">
    <strong>Auto-Save</strong>
    <div id="autoSaveMessage">Auto-saving in <span id="countdownTimer">20</span>s...</div>
    <u class ="manualSave" onclick="manualSave()"><strong>Manualy save!</strong></u>
</div>


<style>
    .autoSaveBox {
        position: fixed;
        bottom: 30px;
        right: 20px;
        background: rgba(0, 0, 0, 0.527);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        z-index: 1000;
        min-width: 200px;
        text-align: center;
    }

    .manualSave{
        color: #000000;
        cursor: pointer;

    }

</style>


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
    let countdown = 20; // Start countdown at 20 seconds
    let intervalId = null;
    let respErrCount = 0;
    let isTabActive = true; // Track if the tab is active

    function startSavingWithCountdown() {
        updateCountdown();
    }

    function updateCountdown() {
        if (intervalId) clearInterval(intervalId);

        const countdownElement = document.getElementById('countdownTimer');
        const autoSaveMessage = document.getElementById('autoSaveMessage');

        intervalId = setInterval(() => {
            if (!isTabActive) return; // If tab is inactive, do nothing

            countdown--;
            countdownElement.textContent = countdown; // Update countdown text

            if (countdown <= 0) {
                clearInterval(intervalId);
                intervalId = null; // Mark interval as stopped
                autoSaveMessage.innerHTML = "Saving...";
                saveInfos();

                // Restart countdown after saving
                setTimeout(() => {
                    countdown = 20;
                    autoSaveMessage.innerHTML = `Auto-saving in <span id="countdownTimer">${countdown}</span>s...`;
                    updateCountdown();
                }, 1000);
            }
        }, 1000);
    }

    function saveInfos() {
        let form = document.getElementById('worker-profile-form');
        let formData = new FormData(form);

        fetch('/worker/update-worker-profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            keepalive: true
        }).then(response => {
            respErrCount = 0;
            console.log("Silent save successful");
        }).catch(error => {
            respErrCount++;
            if (respErrCount > 4) {
                document.getElementById('autoSaveMessage').innerHTML = 
                    '<span style="color: red;">Data not saving! Refresh the page.</span>';
                respErrCount = 0;
            }
        });
    }

    // Pause and resume countdown based on tab visibility
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            isTabActive = true;
            if (!intervalId) updateCountdown(); // Resume countdown only if it's not already running
        } else {
            isTabActive = false;
            clearInterval(intervalId);
            intervalId = null; // Mark interval as stopped
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        startSavingWithCountdown();
    });

    
    function manualSave() {
        countdown = 1;
    }


</script>

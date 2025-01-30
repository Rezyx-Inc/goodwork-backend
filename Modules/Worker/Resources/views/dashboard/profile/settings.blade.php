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
    <div id="autoSaveMessage"></div>
    <div class="progress">
        <div id="progressBar" class="progress-bar" style="width: 100%;"></div>
    </div>
    <u class="manualSave" onclick="manualSave()"><strong>Manually Save!</strong></u>
</div>



<style>
    .autoSaveBox {
        position: fixed;
        bottom: 30px;
        right: 20px;
        background: rgba(0, 0, 0, 0.45);
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        font-size: 14px;
        z-index: 1000;
        min-width: 220px;
        text-align: center;
    }

    .progress {
        height: 8px;
        background: #444;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 5px;
    }

    .progress-bar {
        height: 100%;
        background: #b5649e;
        transition: width 1s linear;
    }

    .manualSave {
        color: #b5649e;
        cursor: pointer;
        display: block;
        margin-top: 5px;
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
    const baseDuration = 30;
    let countdown = baseDuration;
    let intervalId = null;
    let isTabActive = true;

    function startSavingWithProgressBar() {
        if (intervalId) return;
        updateProgressBar();
    }

    function updateProgressBar() {
        if (intervalId) clearInterval(intervalId);

        const autoSaveMessage = document.getElementById('autoSaveMessage');
        const progressBar = document.getElementById('progressBar');

        countdown = baseDuration; 
        progressBar.style.width = "100%";

        intervalId = setInterval(() => {
            if (!isTabActive) return;

            countdown--;
            let progressPercent = (countdown / baseDuration) * 100;
            progressBar.style.width = progressPercent + "%";

            if (countdown <= 0) {
                clearInterval(intervalId);
                intervalId = null;
                autoSaveMessage.innerHTML = "Saving...";
                progressBar.style.width = "0%";
                saveInfos();

                // Restart countdown after saving
                setTimeout(() => {
                    countdown = 20;
                    autoSaveMessage.innerHTML = ``;
                    // Reset progress bar
                    progressBar.style.width = "100%"; 
                    updateProgressBar();
                }, 2000);
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
            console.log("Auto-save successful");
        }).catch(error => {
            console.error("Error saving worker information:", error);
        });
    }

    // Pause and resume countdown based on tab visibility
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            isTabActive = true;
            // Resume countdown only if it's not running
            if (!intervalId) updateProgressBar();
        } else {
            isTabActive = false;
            // Stop interval
            clearInterval(intervalId);
            intervalId = null;
        }
    });

    // Start progress bar countdown when page loads
    document.addEventListener('DOMContentLoaded', () => {
        startSavingWithProgressBar();
    });

    // Manual Save Function (Immediately Saves & Resets Timer)
    function manualSave() {
        // Force countdown to reach zero
        countdown = 1;
    }
</script>

<form id="worker-profile-form" onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
    <!-- first form slide Basic Information -->

    <div class=" page slide-page">
        @include('worker::dashboard.profile.settings.professional_info')
        @include('worker::dashboard.profile.settings.personal_info')

        <div class="ss-prsn-form-btn-sec d-block">
            <button type="text" class="ss-prsnl-save-btn" id="SaveInformation"> Save
            </button>
        </div>
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
    // Save Basic Information
    const SaveInformation = document.getElementById("SaveInformation");

    SaveInformation.addEventListener("click", function(event) {
        event.preventDefault();
        // inputs validation
        if (!validateBasicInfo()) {
            return;
        }
        //console.log(first_name.value);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Get the form element
        let form = document.getElementById('worker-profile-form');

        // Create FormData object
        let formData = new FormData(form);

        console.log("log formData : ", formData);
        // alert("stop before calling api ");
        // return false;

        $.ajax({
            url: '/worker/update-worker-profile',
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(resp) {
                //console.log(resp);
                if (resp.status) {
                    notie.alert({
                        type: 'success',
                        text: '<i class="fa fa-check"></i> Basic Information saved successfully.',
                        time: 5
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 2000);

                }

            },
            error: function(resp) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i>' + resp.message,
                    time: 5
                });
            }
        });

    });
    // end Saving Basic Information
</script>

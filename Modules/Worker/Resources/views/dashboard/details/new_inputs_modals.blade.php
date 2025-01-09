<form id="nurse_form_inputs" onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
        
  {{-- Profession Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="profession_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#profession_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- Professions --}}
                    <div class="ss-form-group">
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'profession',
                            'label' => 'Professions',
                            'placeholder' => 'What kind of Professional are you?',
                            'name' => 'profession',
                            'options' => $allKeywords['Profession'],
                            'option_attribute' => 'title',
                            'selected' => old('profession', $nurse->profession),
                            'onChange' => 'multi_select_change',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    {{-- worker_job_type Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="worker_job_type_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#worker_job_type_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- worker_job_type --}}
                    <div class="ss-form-group">
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'worker_job_type',
                            'label' => 'Type',
                            'placeholder' => 'Select Type',
                            'name' => 'worker_job_type',
                            'options' => $allKeywords['Type'],
                            'option_attribute' => 'title',
                            'selected' => old('worker_job_type', $nurse->worker_job_type),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- terms Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="terms_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#terms_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- terms --}}
                    <div class="ss-form-group">
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'terms',
                            'label' => 'Terms',
                            'placeholder' => 'Select Terms',
                            'name' => 'terms',
                            'options' => $allKeywords['Terms'],
                            'option_attribute' => 'title',
                            'selected' => old('terms', $nurse->terms),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- specialty Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="specialty_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#specialty_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- specialty --}}
                    <div class="ss-form-group">

                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'specialty',
                            'label' => 'What\'s your specialty?',
                            'placeholder' => 'Select your specialty',
                            'name' => 'specialty',
                            'options' => $allKeywords['Speciality'],
                            'option_attribute' => 'title',
                            'selected' => old('specialty', $nurse->specialty),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- state Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="state_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#state_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- state --}}
                    <div class="ss-form-group">
                        
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'state',
                            'label' => 'States you\'d like to work?',
                            'placeholder' => 'Select states',
                            'name' => 'state',
                            'options' => $allKeywords['State'],
                            'option_attribute' => 'title',
                            'selected' => old('state', $nurse->state),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- city Modal --}}
    {{-- <div class="modal fade ss-jb-dtl-pops-mn-dv" id="city_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#city_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    <div class="ss-form-group">
                        
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'city',
                            'label' => 'Cities you\'d like to work?',
                            'placeholder' => 'Select cities',
                            'name' => 'city',
                            'options' => $allKeywords['City'],
                            'option_attribute' => 'title',
                            'selected' => old('city', $nurse->city),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div> --}}


    {{-- worker_shift_time_of_day Modal --}}
    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="worker_shift_time_of_day_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="ss-pop-cls-vbtn">
                    <button type="button" class="btn-close" data-target="#worker_shift_time_of_day_modal" onclick="close_modal(this)"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body mt-3">
                    {{-- worker_shift_time_of_day --}}
                    <div class="ss-form-group">
                        
                        @include('worker::components.custom_multiple_select_input', [
                            'id' => 'worker_shift_time_of_day',
                            'label' => 'Fav shift?',
                            'placeholder' => 'Select shifts',
                            'name' => 'worker_shift_time_of_day',
                            'options' => $allKeywords['PreferredShift'],
                            'option_attribute' => 'title',
                            'selected' => old('worker_shift_time_of_day', $nurse->worker_shift_time_of_day),
                            'onChange' => 'multi_select_change',
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>





</form>





{{-- style for multi-select --}}

<style>
    /* Google Fonts - Poppins*/
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');


    .container-multiselect {
        position: relative;
        max-width: 320px;
        width: 100%;
        margin: 30px auto 30px;
    }

    .select-btn {
        display: flex;
        height: 50px;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }

    .select-btn .btn-text {
        font-size: 17px;
        font-weight: 400;
        color: #333;
    }

    .select-btn .arrow-dwn {
        display: flex;
        height: 21px;
        width: 21px;
        color: #fff;
        font-size: 14px;
        border-radius: 50%;
        background: #3d2c39;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .select-btn.open .arrow-dwn {
        transform: rotate(-180deg);
    }

    .list-items {
        position: relative;
        border-radius: 8px;
        padding: 16px;
        background-color: #fff;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        display: none;
        max-height: 500px;
        scroll-behavior: auto;
        overflow: auto;

    }

    .select-btn.open~.list-items {
        display: block;
    }

    .list-items .item,
    .list-items .item-elem {
        display: flex;
        align-items: center;
        list-style: none;
        height: 50px;
        cursor: pointer;
        transition: 0.3s;
        padding: 0 15px;
        border-radius: 8px;
    }

    .list-items .item:hover,
    .list-items .item-elem:hover {
        background-color: #e7edfe;
    }

    .item .item-text {
        font-size: 16px;
        font-weight: 400;
        color: #333;
    }

    .item .checkbox {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 16px;
        width: 16px;
        border-radius: 4px;
        margin-right: 12px;
        border: 1.5px solid #c0c0c0;
        transition: all 0.3s ease-in-out;
    }

    .item.checked .checkbox {
        background-color: #3d2c39;
        border-color: #3d2c39;
    }

    .checkbox .check-icon {
        color: #fff;
        font-size: 11px;
        transform: scale(0);
        transition: all 0.2s ease-in-out;
    }

    .item.checked .check-icon {
        transform: scale(1);
    }

    .ss-job-dtl-pop-sv-btn {
        margin-top: 30px !important;
    }

    .remove-file {
        cursor: pointer;
        color: white;
        background-color: #3d2c39;
        border-radius: 8px;
        padding: 0px !important;
        font-size: 12px;
    }

    .file-name {
        margin-top: 10px;
        padding: 0px;
    }
</style>

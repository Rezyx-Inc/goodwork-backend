<form id="nurse_form_inputs" onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    @csrf

    @php
        $modals = [
            [
                'id' => 'profession',
                'label' => 'Professions',
                'placeholder' => 'What kind of Professional are you?',
                'name' => 'profession',
                'options' => $allKeywords['Profession'],
                'selected' => old('profession', $nurse->profession),
            ],
            [
                'id' => 'worker_job_type',
                'label' => 'Type',
                'placeholder' => 'Select Type',
                'name' => 'worker_job_type',
                'options' => $allKeywords['Type'],
                'selected' => old('worker_job_type', $nurse->worker_job_type),
            ],
            [
                'id' => 'terms',
                'label' => 'Terms',
                'placeholder' => 'Select Terms',
                'name' => 'terms',
                'options' => $allKeywords['Terms'],
                'selected' => old('terms', $nurse->terms),
            ],
            [
                'id' => 'specialty',
                'label' => 'What\'s your specialty?',
                'placeholder' => 'Select your specialty',
                'name' => 'specialty',
                'options' => $allKeywords['Speciality'],
                'selected' => old('specialty', $nurse->specialty),
            ],
            [
                'id' => 'state',
                'label' => 'States you\'d like to work?',
                'placeholder' => 'Select states',
                'name' => 'state',
                'options' => $allKeywords['State'],
                'selected' => old('state', $nurse->state),
            ],
            [
                'id' => 'worker_shift_time_of_day',
                'label' => 'Fav shift?',
                'placeholder' => 'Select shifts',
                'name' => 'worker_shift_time_of_day',
                'options' => $allKeywords['PreferredShift'],
                'selected' => old('worker_shift_time_of_day', $nurse->worker_shift_time_of_day),
            ],
            [
                'id' => 'worker_benefits',
                'label' => 'Benefits',
                'placeholder' => 'Select benefits',
                'name' => 'worker_benefits',
                'options' => $allKeywords['Benefits'],
                'selected' => old('worker_benefits', $nurse->worker_benefits),
            ],
            [
                'id' => 'clinical_setting_you_prefer',
                'label' => 'Clinical Setting',
                'placeholder' => 'Select setting',
                'name' => 'clinical_setting_you_prefer',
                'options' => $allKeywords['ClinicalSetting'],
                'selected' => old('clinical_setting_you_prefer', $nurse->clinical_setting_you_prefer),
            ],
            [
                'id' => 'worker_emr',
                'label' => 'EMR',
                'placeholder' => 'Select EMR',
                'name' => 'worker_emr',
                'options' => $allKeywords['EMR'],
                'selected' => old('worker_emr', $nurse->worker_emr),
            ],
            [
                'id' => 'nurse_classification',
                'label' => 'Nurse Classification',
                'placeholder' => 'Select Classification',
                'name' => 'nurse_classification',
                'options' => $allKeywords['NurseClassification'],
                'selected' => old('nurse_classification', $nurse->nurse_classification),
            ],
            [
                'id' => 'worker_job_location',
                'label' => 'Professional Licensure',
                'placeholder' => 'Select a state',
                'name' => 'worker_job_location',
                'options' => $allKeywords['StateCode'],
                'selected' => old('worker_job_location', $nurse->worker_job_location),
            ],
            // worker_pay_frequency
            [
                'id' => 'worker_pay_frequency',
                'label' => 'Pay Frequency',
                'placeholder' => 'Select Pay Frequency',
                'name' => 'worker_pay_frequency',
                'options' => $allKeywords['PayFrequency'],
                'selected' => old('worker_pay_frequency', $nurse->worker_pay_frequency),
            ],



        ];
    @endphp

    @foreach ($modals as $modal)
        @component('worker::components.modal', $modal)
        @endcomponent
    @endforeach
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

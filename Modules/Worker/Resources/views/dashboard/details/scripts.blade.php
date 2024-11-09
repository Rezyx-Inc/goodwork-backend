@section('js')
    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>

    <script>
        let all_files = [];
        let userMatch = @json($matches);
        let workerMatch = [];
        for (let key in userMatch) {
            if (userMatch.hasOwnProperty(key)) {
                workerMatch[userMatch[key].name] = {
                    match: userMatch[key].match,
                };
            }
        }


        var Emr = {};

        function addEmr(type) {
            let id;
            let idtitle;
            if (type == 'from_add') {
                id = $('#Emr');
                idtitle = "Emr";
            } else if (type == 'from_draft') {
                id = $('#EmrDraft');
                idtitle = "EmrDraft";
            } else {
                id = $('#EmrEdit');
                idtitle = "EmrEdit";
            }

            if (!id.val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the Emr please.',
                    time: 3
                });
            } else {
                if (!Emr.hasOwnProperty(id.val())) {

                    var select = document.getElementById(idtitle);
                    var selectedOption = select.options[select.selectedIndex];
                    var optionText = selectedOption.textContent;

                    Emr[id.val()] = optionText;
                    EmrStr = Object.values(Emr).join(', ');
                    id.val('');
                    list_Emr();
                }
            }
        }

        function list_Emr() {
            var str = '';

            for (const key in Emr) {

                let Emrname = "";
                @php
                    $allKeywordsJSON = json_encode($allKeywords['EMR']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                if (Emr.hasOwnProperty(key)) {
                    var data = JSON.parse(allspcldata);

                    data.forEach(function(item) {
                        if (key == item.id) {
                            Emrname = item.title;
                        }
                    });
                    const value = Emr[key];
                    str += '<ul>';
                    str += '<li>' + Emrname + '</li>';
                    str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-Emr" data-key="' + key +
                        '" onclick="remove_Emr(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.Emr-content').html(str);
        }

        function remove_Emr(obj, key) {
            if (Emr.hasOwnProperty(key)) {
                delete Emr[key]; // Remove the Emr from the object
                EmrStr = Object.values(Emr).join(', '); // Update the hidden input value
                list_Emr(); // Refresh the list to reflect the deletion
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i> Shift Time Of Day removed successfully.',
                    time: 3
                });
            } else {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-times"></i> Shift Time Of Day not found.',
                    time: 3
                });
            }
        }
    </script>
    <script>
        function daysUntilWorkStarts(dateString) {
            const workStartDate = new Date(dateString);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const differenceInMilliseconds = workStartDate - today;
            const differenceInDays = Math.ceil(differenceInMilliseconds / (1000 * 60 * 60 * 24));
            return `Work starts in ${differenceInDays} days`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.start-date').forEach(function(element) {
                const startDate = element.getAttribute('data-start-date');
                element.textContent = daysUntilWorkStarts(startDate);
            });
        });

        $('#ref_phone_number').mask('+1 (999) 999-9999');
        var worker_files_displayname_by_type = [];
        var worker_files = [];
        // certification
        var job_certification = "{!! $model->certificate !!}";
        var job_certification_displayname = job_certification.split(',').map(function(item) {
            return item.trim();
        });

        // vaccination
        var job_vaccination = "{!! $model->vaccinations !!}";
        var job_vaccination_displayname = job_vaccination.split(',').map(function(item) {
            return item.trim();
        });

        // references
        var number_of_references = "{!! $model->number_of_references !!}";

        // skills
        var job_skills = "{!! $model->skills !!}";
        var job_skills_displayname = job_skills.split(',').map(function(item) {
            return item.trim();
        });


        var worker_id = "{!! auth()->guard('frontend')->user()->nurse->id !!}";

        function get_all_files() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('list-docs') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        WorkerId: worker_id
                    }),
                    success: function(resp) {

                        let jsonResp = JSON.parse(resp);
                        all_files = jsonResp;
                        files = jsonResp;
                        resolve(
                            files
                        ); // Resolve the promise with the display names
                    },
                    error: function(resp) {
                        reject(resp); // Reject the promise with the error response
                    }
                });
            });
        }
        // file types we have : ['professional_license', 'diploma', 'references', 'vaccination', 'certification'
        // ];
        // get_all_files('certification');

        var selectedFiles = [];
        var selectedValues = [];
        var selectedCertificates = [];
        var selectedVaccinations = [];
        document.addEventListener('DOMContentLoaded', function() {

            // trim description control

            let description = document.getElementById('job_description');

            if (description) {
                let descriptionText = description.innerText;
                if (descriptionText.length > 100) {
                    description.innerText = descriptionText.substring(0, 100) + '...';
                    let readMore = document.createElement('a');
                    readMore.id = 'read-more';
                    readMore.innerText = ' Read More';
                    readMore.href = 'javascript:void(0)';
                    // add a function to onclick
                    readMore.onclick = readMoreDescreption;
                    description.appendChild(readMore);
                }
            }


            // end trim description control

            const items = document.querySelectorAll('.list-items .item');
            //store selected file values

            items.forEach((item, index) => {
                item.addEventListener('click', (event) => {
                    if (event.target.closest('.checkbox')) {
                        return;
                    }
                    const uploadInput = item.nextElementSibling;
                    if (uploadInput) {
                        // class 'checked' check
                        if (item.classList.contains('checked')) {
                            uploadInput.click();
                            uploadInput.addEventListener('change', function() {
                                if (this.files.length > 0) {
                                    // Handling file selection
                                    const file = this.files[0];
                                    selectedFiles.push(file.name);
                                }
                            }, {
                                once: true //avoid multiple registrations
                            });
                        } else {
                            const index = selectedFiles.indexOf(uploadInput.files[0].name);
                            if (index > -1) {
                                selectedFiles.splice(index, 1);
                            }

                        }
                    }
                });
            });


        });

        const selectBtn = document.querySelectorAll(".select-btn"),

            items = document.querySelectorAll(".item");


        selectBtn.forEach(selectBtn => {
            selectBtn.addEventListener("click", () => {
                selectBtn.classList.toggle("open");
            });
        });

        items.forEach(item => {
            item.addEventListener("click", () => {
                const value = item.getAttribute('value');
                item.classList.toggle("checked");

                if (item.classList.contains("checked")) {
                    // add item
                    selectedValues.push(value);
                } else {
                    // remove item
                    const index = selectedValues.indexOf(value);
                    if (index > -1) {
                        selectedValues.splice(index, 1);
                    }
                }
                let btnText = document.querySelector(".btn-text");
                if (selectedValues.length > 0) {
                    btnText.innerText = `${selectedValues.length} Selected`;
                } else {
                    btnText.innerText = "Select Language";
                }
            });
        })

        async function sendMultipleFiles(type) {
            const fileInputs = document.querySelectorAll('.files-upload');
            const fileReadPromises = [];
            var workerId = worker_id;

            if (type == 'references') {let referenceName = document.querySelector('input[name="name"]').value;
                let referencePhone = document.querySelector('input[name="phone"]').value;
                let referenceEmail = document.querySelector('input[name="reference_email"]').value;
                let referenceDate = document.querySelector('input[name="date_referred"]').value;
                let referenceMinTitle = document.querySelector('input[name="min_title_of_reference"]').value;
                let referenceRecency = document.querySelector('select[name="recency_of_reference"]').value;
                let referenceImage = document.querySelector('input[name="image"]').files[0];

                if (!referenceName) {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-exclamation-triangle"></i> Reference Name is required',
                        time: 3
                    });
                    return 404;
                } else {
                    if (!referencePhone && !referenceEmail) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Phone Number or Email is required',
                            time: 3
                        });
                        return 404;
                    }
                }

                var referenceInfo = {
                    referenceName: referenceName,
                    phoneNumber: referencePhone,
                    email: referenceEmail,
                    dateReferred: referenceDate,
                    minTitle: referenceMinTitle,
                    isLastAssignment: referenceRecency == 1 ? true : false
                };
                console.log(referenceInfo);

                var readerPromise;
                if (referenceImage) {
                    readerPromise = new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            resolve({
                                name: referenceImage.name,
                                path: "",
                                type: type,
                                content: event.target.result,
                                displayName: referenceImage.name,
                                ReferenceInformation: referenceInfo
                            });
                        };
                        reader.onerror = reject;
                        reader.readAsDataURL(referenceImage);
                    });
                } else {
                    readerPromise = Promise.resolve({
                        name: 'null',
                        path: 'null',
                        type: type,
                        content: 'data:',
                        displayName: 'null',
                        ReferenceInformation: referenceInfo
                    });
                }

                fileReadPromises.push(readerPromise);

            } else {
                fileInputs.forEach((input, index) => {
                    let displayName = input.getAttribute("displayName");
                    if (input.files[0]) {
                        const file = input.files[0];
                        const readerPromise = new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                resolve({
                                    name: file.name,
                                    path: file.name,
                                    type: type,
                                    content: event.target.result, // Base64 encoded content
                                    displayName: displayName || file.name,
                                });
                            };
                            reader.onerror = reject;
                            reader.readAsDataURL(file);
                        });
                        fileReadPromises.push(readerPromise);
                    }
                });
            }
            try {
                const files = await Promise.all(fileReadPromises);
                const response = await fetch('/worker/add-docs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        workerId: workerId,
                        files: files
                    })
                });
                const data = await response.json();
                notie.alert({
                    type: 'success',
                    text: '<i class="fa fa-check"></i>' + "Uploaded Successfully",
                    time: 3
                });
            } catch (error) {
                console.error('Error:', error); // Handle errors
            }

            // clear files inputs
            fileInputs.forEach((input) => {
                input.value = '';
            });
            selectedFiles = [];
        }

        function readMoreDescreption() {
            var description = document.getElementById('job_description');


            if (description) {
                var descriptionText = description.innerText;
                if (descriptionText.length > 100) {
                    description.innerText = '{!! $model->description !!}';
                    var readMore = document.getElementById('read-more');
                    var readLess = document.getElementById('read-less');

                    if (readLess) {
                        readMore.classList.add('d-none');
                        readLess.classList.remove('d-none');
                    } else {

                        var readLess = document.createElement('a');
                        readLess.id = 'read-less';
                        readLess.innerText = ' Read Less';
                        readLess.href = 'javascript:void(0)';
                        // add a function to onclick
                        readLess.onclick = readLessDescreption;
                        description.appendChild(readLess);
                    }
                }
            }
        }

        function readLessDescreption() {
            let description = document.getElementById('job_description');
            let readMore = document.getElementById('read-more');
            let readLess = document.getElementById('read-less');
            if (description) {
                let descriptionText = description.innerText;
                if (descriptionText.length > 100) {
                    description.innerText = descriptionText.substring(0, 100) + '...';
                    readLess.classList.add('d-none');
                    let readMore = document.createElement('a');
                    readMore.id = 'read-more';
                    readMore.innerText = ' Read More';
                    readMore.href = 'javascript:void(0)';
                    // add a function to onclick
                    readMore.onclick = readMoreDescreption;
                    description.appendChild(readMore);

                }
            }
        }
    </script>
    <script>
        var dataToSend = {};
        var EmrStr = '';
        var requiredFieldsToApply = @json($requiredFieldsToApply);

        console.log('required fields : ', requiredFieldsToApply);
        requiredFieldsToApply.forEach(function(field) {

            var element = document.getElementById(field);

            if (element) {

                var spanElement = element.querySelector('span');

                if (spanElement) {

                    spanElement.innerHTML += ' (*)';

                }
            }

        });

        function matchWithWorker(workerField, InsertedValue) {
            let match = false;
            var job = @json($model);

            switch (workerField) {
                case 'profession':
                    if (job['profession'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'specialty':
                    if (job['preferred_specialty'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'nursing_license_state':
                    let job_location = job['job_location'];
                    let states = job_location.split(", ").map(state => state.trim());
                    if (states.includes(InsertedValue)) {
                        match = true;
                    }
                    break;
                case 'worker_experience':
                    if (job['preferred_experience'] <= InsertedValue) {
                        match = true;
                    }
                    break;
                    // case 'vaccinations':
                    //     // Complete logic for vaccinations
                    //     break;
                    // case 'number_of_references':
                    //    // Complete logic for min_title_of_reference
                    //     break;
                    // case 'min_title_of_reference':
                    //     // Complete logic for min_title_of_reference
                    //     break;
                    // case 'recency_of_reference':
                    //     // Complete logic for recency_of_reference
                    //     break;
                case 'certificate':
                    // TODO ::
                    // Complete logic for certificate
                    break;
                    // case 'skills':
                    //      // Complete logic for skills
                    //     break;
                case 'worker_eligible_work_in_us':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_urgency':
                    if (job['urgency'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facility_state':
                    if (job['job_state'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facility_city':
                    if (job['job_city'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_weeks_assignment':
                    if (job['preferred_assignment_duration'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_start_date':
                    if (job['start_date'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_as_soon_as_possible':
                    if (job['as_soon_as'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'distance_from_your_home':
                    if (job['traveler_distance_from_facility'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'clinical_setting_you_prefer':
                    if (job['clinical_setting'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_scrub_color':
                    if (job['scrub_color'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_emr':
                    if (job['Emr'] == EmrStr.trim('')) {
                        match = true;
                    }
                    break;
                case 'rto':
                    if (job['rto'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_hours_per_week':
                    if (job['hours_per_week'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_guaranteed_hours':
                    if (Number(job['guaranteed_hours']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_hours_shift':
                    if (job['hours_shift'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_shifts_week':
                    if (Number(job['weeks_shift']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_referral_bonus':
                    if (Number(job['referral_bonus']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_sign_on_bonus':
                    if (Number(job['sign_on_bonus']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_completion_bonus':
                    if (Number(job['completion_bonus']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_extension_bonus':
                    if (Number(job['extension_bonus']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_other_bonus':
                    if (Number(job['other_bonus']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_four_zero_one_k':
                    if (job['four_zero_one_k'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_actual_hourly_rate':
                    if (Number(job['actual_hourly_rate']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_health_insurance':
                    if (job['health_insaurance'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_dental':
                    if (job['dental'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_vision':
                    if (job['vision'] == InsertedValue) {
                        match = true;
                    }
                    break;
                    // case 'worker_feels_like_per_hour_check':
                    //     if (InsertedValue == '1') {
                    //         match = true;
                    //     }
                    //     break;
                case 'worker_overtime_rate':
                    if (InsertedValue == job['overtime_rate']) {
                        match = true;
                    }
                    break;
                case 'worker_holiday':
                    if (job['holiday'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_on_call_check':
                    // job['on_call'] = job['on_call'] == 'Yes' ? '1' : '0';
                    if (job['on_call'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_on_call':
                    if (Number(job['on_call_rate']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_call_back_check':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_orientation_rate':
                    if (InsertedValue == Number(job['orientation_rate'])) {
                        match = true;
                    }
                    break;
                case 'worker_weekly_non_taxable_amount_check':
                    if (InsertedValue == '1') {
                        match = true;
                    }
                    break;
                case 'worker_organization_weekly_amount':
                    if (Number(job['organization_weekly_amount']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_patient_ratio':
                    if (Number(job['Patient_ratio']) == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_unit':
                    if (job['Unit'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_job_type':
                    if (job['job_type'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'MSP':
                    if (job['msp'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'VMS':
                    if (job['vms'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'block_scheduling':
                    if (job['block_scheduling'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'float_requirement':
                    if (job['float_requirement'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'facility_shift_cancelation_policy':
                    if (job['facility_shift_cancelation_policy'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'worker_facilitys_parent_system':
                    if (job['facilitys_parent_system'] == InsertedValue) {
                        match = true;
                    }
                    break;
                case 'contract_termination_policy':
                    if (job['contract_termination_policy'] == InsertedValue) {
                        match = true;
                    }
                    break;
                default:
                    match = undefined;
            }

            return match;
        }

        async function collect_data(event, type) {
            event.preventDefault();
            // targiting the input form and collectiong data

            let button = $(event.target);
            var form = button.closest('form');
            let formData = new FormData(form[0]);
            if (type !== 'file' && type !== 'multi-select') {
                let data = Object.fromEntries(formData.entries());
                dataToSend = {
                    ...dataToSend,
                    ...data
                };
            }


            var inputName = '';
            if (type == 'binary') {
                inputName = form.find('input[type="radio"]').attr('name');
            } else if (type == 'input') {
                inputName = form.find('input[type="text"]').attr('name');
            } else if (type == 'input_number') {
                inputName = form.find('input[type="number"]').attr('name');
            } else if (type == 'file') {
                inputName = form.attr('name');;
            } else if (type == 'rto') {
                inputName = form.find('input[type="radio"]').attr('name');
            } else if (type == 'dropdown') {
                inputName = form.find('select').attr('name');
            } else if (type == 'date') {
                inputName = form.find('input[type="date"]').attr('name');
            } else if (type = 'multi-select') {
                inputName = form.find('input[type="text"]').attr('id');
                if (inputName == 'EmrAllValues') {
                    inputName.value = EmrStr;
                    data = {
                        worker_emr: EmrStr
                    };
                    dataToSend = {
                        ...dataToSend,
                        ...data
                    };
                    inputName = form.find('input[type="text"]').attr('name');
                }

            }

            let job = @json($model);

            // matching job / worker infromation

            if (matchWithWorker(inputName, dataToSend[inputName]) != undefined) {
                if (matchWithWorker(inputName, dataToSend[inputName])) {
                    let areaDiv = document.getElementById(inputName);
                    areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
                    areaDiv.classList.add('ss-s-jb-apl-bg-blue');
                } else {
                    let areaDiv = document.getElementById(inputName);
                    areaDiv.classList.remove('ss-s-jb-apl-bg-blue');
                    areaDiv.classList.add('ss-s-jb-apl-bg-pink');
                }
            } else if (dataToSend[inputName] == '1') {
                let areaDiv = document.getElementById(inputName);
                areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
                areaDiv.classList.add('ss-s-jb-apl-bg-blue');
            } else if (type == 'file') {
                try {
                    let resp = await sendMultipleFiles(inputName);
                    if (resp == 404) {
                        return false;
                    }
                } catch (error) {
                    console.error('Failed to send files:', error)
                }
                worker_files = await get_all_files();
                await checkFileMatch(inputName);
            }

            let element = null;

            if (inputName == 'references') {
                element = $('#' + inputName).find('p[data-target="reference_file"]');
            } else if (inputName == 'certification') {
                element = $('#' + inputName).find('p[data-target="certification_file"]');
            } else if (inputName == 'vaccination') {
                element = $('#' + inputName).find('p[data-target="vaccination_file"]');
            } else if (inputName == 'skills') {
                element = $('#' + inputName).find('p[data-target="skills_file"]');
            } else if (inputName == 'driving_license') {
                element = $('#' + inputName).find('p[data-target="driving_license_file"]');
            }   else if (inputName == 'diploma') {
                element = $('#' + inputName).find('p[data-target="diploma_file"]');
            } else {
                element = $('#' + inputName).find('p[data-name="' + inputName + '"]');
            }
            switch (type) {
                case 'input':
                case 'input_number':
                case 'dropdown':
                case 'date':
                    // if dataToSend[inputName] have value then only update else nothing
                    if (!!dataToSend[inputName] && dataToSend[inputName] != undefined && dataToSend[inputName] != '' &&
                        dataToSend[inputName] != null) {
                        element.text(dataToSend[inputName]);
                    } else {
                        // element.text = get element data-title value
                        element.text(element.data('title'));
                    }
                    break;
                case 'binary':
                case 'rto':
                    if (!!dataToSend[inputName] && dataToSend[inputName] != undefined && dataToSend[inputName] != '' &&
                        dataToSend[inputName] != null) {
                            if (inputName == "worker_benefits") {
                                element.text(dataToSend[inputName] == '2' ? 'Preferable' : dataToSend[inputName] == '1' ? 'Yes, Please' : 'No, Thanks');
                            }else{

                                element.text(dataToSend[inputName] == '1' ? 'Yes' : 'No');
                            }
                    } else {
                        element.text(element.data('title'));
                    }
                    break;
                case 'file':
                    // if (inputName == 'references') {
                    //     display_uploaded_reference_files(element, 2);
                    // } else if (inputName == 'certification') {
                        display_uploaded_certification_files(element, inputName);
                    // }

                    break;

                case 'multi-select':
                    
                    if (!!dataToSend[inputName] && dataToSend[inputName] != undefined && dataToSend[inputName] != '' &&
                        dataToSend[inputName] != null) {
                        element.text(EmrStr);
                    } else {
                        // element.text = get element data-title value
                        element.text(element.data('title'));
                    }

                    break;

                default:
                    break;
            }
            // for (let [key, value] of formData.entries()) {
            //     console.log(key, value);
            // }
            closeModal();
        }

        // display uploaded reference files
        function display_uploaded_reference_files(element, limit = null) {
            // filter worker_files by type
            worker_files = all_files.filter(file => file.type == 'references');

            let text = element.data('title');
            if (limit == null && worker_files.length > 0) {
                text = worker_files
                    .map(file => file?.ReferenceInformation?.referenceName)
                    .join(',<br>') + '<br>';
            } else if (!!limit && worker_files.length > 0) {
                text = worker_files
                    .map(file => file?.ReferenceInformation?.referenceName)
                    .slice(0, limit)
                    .join('<br>') + (worker_files.length > limit ? '<br>+ ' + (worker_files.length - limit) + ' More' : '');
            }
            element.html(text);
        }

        // display uploaded certification files
        function display_uploaded_certification_files(element, type) {
            // filter worker_files by type
            worker_files = all_files.filter(file => file.type == 'certification');

            let text = element.data('title');
            console.log("*****************************text : ", text);
            if (worker_files.length > 0) {
                text = worker_files.length + ' Files Uploaded';
            }
            element.html(text);
        }

        async function checkFileMatch(inputName) {
            console.log('Checking files for:', inputName);
            let worker_files_displayname_by_type = [];
            try {
                worker_files_displayname_by_type = await get_all_files_displayName_by_type(inputName);
                console.log('Files:', worker_files_displayname_by_type);
            } catch (error) {
                console.error('Failed to get files:', error);
            }

            let areaDiv = document.getElementById(inputName);
            let check = false;
            if (inputName == 'certification') {
                const is_job_certif_exist_in_worker_files = job_certification_displayname.every(element =>
                    worker_files_displayname_by_type.includes(element));
                if (is_job_certif_exist_in_worker_files) {
                    check = true;
                }
            } else if (inputName == 'vaccination') {
                const is_job_vaccin_exist_in_worker_files = job_vaccination_displayname.every(element =>
                    worker_files_displayname_by_type.includes(element));

                if (is_job_vaccin_exist_in_worker_files) {
                    check = true;
                }
            } else if (inputName == 'references') {
                if (number_of_references <= worker_files_displayname_by_type.length) {
                    check = true;
                }
            } else if (inputName == 'skills') {
                const is_job_skill_exist_in_worker_files = job_skills_displayname.every(element =>
                    worker_files_displayname_by_type.includes(element));
                // console.log('job skills job name :', job_skills_displayname)
                // console.log('worker_files_displayname_by_type', worker_files_displayname_by_type)
                // console.log('is_job_skill_exist_in_worker_files', is_job_skill_exist_in_worker_files);
                if (is_job_skill_exist_in_worker_files) {
                    check = true;
                }
            } else if (inputName == 'driving_license') {
                if (worker_files_displayname_by_type.length > 0) {
                    check = true;
                }
            } else if (inputName == 'diploma') {
                if (worker_files_displayname_by_type.length > 0) {
                    check = true;
                }
            }
            if (check) {
                areaDiv.classList.remove('ss-s-jb-apl-bg-pink');
                areaDiv.classList.add('ss-s-jb-apl-bg-blue');
            } else {
                areaDiv.classList.remove('ss-s-jb-apl-bg-blue');
                areaDiv.classList.add('ss-s-jb-apl-bg-pink');
            }


        }

        function closeModal() {
            let buttons = document.querySelectorAll('.btn-close');
            buttons.forEach(button => {
                button.click();
            });
        }

        async function get_all_files_displayName_by_type(type) {
            let files = worker_files.filter(file => file.type == type);
            let displayNames = files.map(file => file.displayName);
            worker_files_displayname_by_type = displayNames;
            return displayNames;
        }


        async function check_required_files_before_sent(obj) {
            let access = true;
            for (const requiredField of requiredFieldsToApply) {
                if (requiredField == 'certification') {
                    let certificate = await get_all_files_displayName_by_type('certification');
                    if (certificate.length == 0) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
                            time: 3
                        });
                        access = false;
                        break;
                    }
                } else if (requiredField == 'vaccination') {
                    let vaccination = await get_all_files_displayName_by_type('vaccination');
                    if (vaccination.length == 0) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
                            time: 3
                        });
                        access = false;
                        break;
                    }
                } else if (requiredField == 'references') {
                    let references = await get_all_files_displayName_by_type('references');
                    if (references.length == 0) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
                            time: 3
                        });
                        access = false;
                        break;
                    }
                } else if (requiredField == 'skills') {
                    let skills = await get_all_files_displayName_by_type('skills');
                    if (skills.length == 0) {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
                            time: 3
                        });
                        access = false;
                        break;
                    }
                } else {
                    let fieldValue = dataToSend[requiredField];
                    if (fieldValue == null && workerMatch[requiredField].match == false) {

                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-exclamation-triangle"></i> Please fill the required fields',
                            time: 3
                        });
                        access = false;
                        break;
                    }

                }
            };
            if (access == false) {
                return false;
            }
            let diploma = [];
            let driving_license = [];

            let worked_bfore = dataToSend['worked_at_facility_before'];


            try {
                diploma = await get_all_files_displayName_by_type('diploma');

            } catch (error) {
                console.error('Failed to get files:', error);
            }
            try {
                driving_license = await get_all_files_displayName_by_type('driving_license');

            } catch (error) {
                console.error('Failed to get files:', error);
            }


            // check for profession match with job
            // if (matchWithWorker('profession', dataToSend['profession']) != true) {
            //     notie.alert({
            //         type: 'error',
            //         text: '<i class="fa fa-exclamation-triangle"></i> Profession not matched with job requirements',
            //         time: 3
            //     });
            //     return false;
            // }

            // if (diploma.length == 0 || driving_license.length == 0 || worked_bfore == null) {
            //     notie.alert({
            //         type: 'error',
            //         text: '<i class="fa fa-exclamation-triangle"></i> Please upload all required files',
            //         time: 3
            //     });
            //     return false;
            // }

            apply_on_jobs(obj, worked_bfore);

        }

        $(document).ready(async function() {
            worker_files = await get_all_files();
            checkFileMatch('certification');
            checkFileMatch('vaccination');
            checkFileMatch('references');
            checkFileMatch('skills');
            checkFileMatch('driving_license');
            checkFileMatch('diploma');
            let matches = @json($matches);

            let usematches = @json($userMatches);
            $('input[name="phone[]"]').mask('(999) 999-9999');
        });

        function open_file(obj) {
            $(obj).parent().find('input[type="file"]').click();
        }

        function open_modal(obj) {
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);
            // TODO :: check if there is already data selected and set it to the input
            switch (target) {
                case 'input':
                    $(form).find('input[type="text"]').attr('name', name);
                    $(form).find('input[type="text"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'input_number':
                    $(form).find('input[type="number"]').attr('name', name);
                    $(form).find('input[type="number"]').attr('placeholder', $(obj).data('placeholder'));
                    break;
                case 'binary':
                    $(form).find('input[type="radio"]').attr('name', name);
                    break;
                case 'rto':
                    $(form).find('input[type="radio"]').attr('name', name);
                    break;
                case 'dropdown':
                    $(form).find('select').attr('name', name);
                    get_dropdown(obj);
                    break;
                case 'date':
                    $(form).find('input[type="date"]').attr('name', name);
                    break;
                default:
                    break;
            }

            $(modal).modal('show');
        }

        function close_modal(obj) {
            let target = $(obj).data('target');
            $(target).modal('hide');
        }
    </script>
@stop

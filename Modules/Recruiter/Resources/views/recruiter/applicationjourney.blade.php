@extends('recruiter::layouts.main')

@section('content')
    <main style="padding-top: 170px" class="ss-main-body-sec">
        <div class="container">
            <h2>Help your <span class="ss-color-pink">applicants advance!</span></h2>
            <div class="row mt-4 applicants-header text-center">
                {{-- New Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Apply')" id="Apply">
                        <p>New</p>
                        <span>{{ $statusCounts['Apply'] }} Applicants</span>
                    </div>
                </div>
                {{-- Screening Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Screening')" id="Screening">
                        <p>Screening</p>
                        <span>{{ $statusCounts['Screening'] }} Applicants</span>
                    </div>
                </div>
                {{-- Submitted Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Submitted')" id="Submitted">
                        <p>Submitted</p>
                        <span>{{ $statusCounts['Submitted'] }} Applicants</span>
                    </div>
                </div>
                {{-- Offered Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Offered')" id="Offered">
                        <p>Offered</p>
                        <span>{{ $statusCounts['Offered'] }} Applicants</span>
                    </div>
                </div>
                {{-- Onboarding Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Onboarding')" id="Onboarding">
                        <p>Onboarding</p>
                        <span>{{ $statusCounts['Onboarding'] }} Applicants</span>
                    </div>
                </div>
                {{-- Working Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Working')" id="Working">
                        <p>Working</p>
                        <span>{{ $statusCounts['Working'] }} Applicants</span>
                    </div>
                </div>
                {{-- Done Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Done')" id="Done">
                        <p>Done</p>
                        <span>{{ $statusCounts['Done'] }} Applicants</span>
                    </div>
                </div>
                {{-- On Hold Applicants --}}
                <div style="flex: 1 1 0px;">
                    <div class="ss-job-prfle-sec" onclick="selectOfferCycleState('Hold')" id="Hold">
                        <p>Hold</p>
                        <span>{{ $statusCounts['Hold'] }} Applicants</span>
                    </div>
                </div>


            </div>
            <div class="ss-appli-done-hed-btn-dv d-none" id="ss-appli-done-hed-btn-dv">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Applicants</h2>
                    </div>
                    <div class="col-lg-6">
                        <ul>
                            <li><a href="javascript:void(0)" onclick="selectOfferCycleState('Done')" id="child_done">Done</a>
                            </li>
                            <li><a href="javascript:void(0)" onclick="selectOfferCycleState('Rejected')"
                                    id="Rejected">Rejected</a></li>
                            <li><a href="javascript:void(0)" onclick="selectOfferCycleState('Blocked')" id="Blocked">Blocked</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="ss-acount-profile">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="ss-account-form-lft-1">
                            <h5 class="mb-4 d-none" id="listingname">New applications</h5>
                            <div id="application-list">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="ss-change-appli-mn-div">
                            <div class="row" id="application-details">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- our file model --}}
        <div class="modal fade ss-jb-dtl-pops-mn-dv" id="file_modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content" style="width:100vw;">
                    <div class="ss-pop-cls-vbtn">
                        <button type="button" class="btn-close" data-target="#file_modal" onclick="close_modal(this)"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                            <form method="post" action="{{ route('worker-upload-files') }}" id="file_modal_form"
                                class="modal-form">
                                <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                                <h4></h4>
                                <div class="ss-form-group fileUploadInput">
                                    <table style="font-size: 16px;" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Document Name</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Download</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{--  add stripe account modal --}}

        <div class="modal fade ss-jb-dtl-pops-mn-dv" id="stripe_modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="ss-pop-cls-vbtn">
                        <button type="button" class="btn-close" data-target="#stripe_modal" onclick="close_modal(this)"
                            aria-label="Close"></button>
                    </div>
                    <div class="d-flex justify-content-center align-items-center" ><h3 class="col-6">Payment Method</h3></div>
                    <div class="modal-body" style="padding:0px !important;">

                        <div
                        class="ss-prsn-form-btn-sec row col-12 d-flex justify-content-center align-items-center">

                        <button type="text" class="ss-prsnl-save-btn" id="AddStripe">
                            Add Stripe
                        </button>
                    </div>
                    </div>

                </div>
            </div>
        </div>

    </main>
    <script>

        const AddStripe = document.getElementById("AddStripe");

        AddStripe.addEventListener("click", function(event) {
            event.preventDefault();

            $('#loading').removeClass('d-none');
            $('#send_ticket').addClass('d-none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/recruiter/send-amount-transfer',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true,
                }),
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Redirecting ...',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        window.location.href = resp.portal_link;
                    } else {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Client Exists',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        close_stripe_modal();
                        //window.location.href = resp.portal_link;
                    }
                }
            });
        });

        function validateFirst() {
            var access = true;
            var fields = [
                'job_name',
                'type',
                'term',
                'description',
                'profession',
                'preferred_specialty',
                'float_requirement',
                'facility_shift_cancelation_policy',
                'contract_termination_policy',
                'traveler_distance_from_facility',
                'facility',
                'clinical_setting',
                'Patient_ratio',
                'emr',
                'Unit',
                'scrub_color',
                'as_soon_as',
                'start_date',
                'rto',
                'shift-of-day',
                'hours_per_week',
                'guaranteed_hours',
                'hours_shift',
                'preferred_assignment_duration',
                'weeks_shift',
                'sign_on_bonus',
                'completion_bonus',
                'extension_bonus',
                'referral_bonus',
                'other_bonus',
                '401k',
                'health-insurance',
                'dental',
                'vision',
                'actual_hourly_rate',
                'overtime',
                'holiday',
                'on_call',
                'tax_status',
                'orientation_rate'
            ];

            fields.forEach(function(field) {
                var test = document.getElementById(field);
                console.log(test.value);
                var value = document.getElementById(field).value;

                if (value.trim() === '') {
                    $('.help-block-' + field).text('Please enter the ' + field);
                    $('.help-block-' + field).addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-' + field).text('');
                }
            });

            return access;
        }

        function applicationType(type, id = "", formtype, jobid = "", nurseId = "") {

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            if ((formtype == "joballdetails" || formtype == "createdraft") && validateFirst()) {

                event.preventDefault();
                var $form = $('#send-job-offer');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (!csrfToken) {
                    console.error('CSRF token not found.');
                    return;
                }
                // Create a FormData object from the form
                var formData = new FormData($form[0]);
                // Append the new attribute
                formData.append('funcionalityType', formtype);
                console.log(formData);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{ route('recruiter-send-job-offer') }}",
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (type == "createdraft") {
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i> Draft Created Successfully',
                                time: 5
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else if (type == "Apply") {
                            notie.alert({
                                type: data.status,
                                text: '<i class="fa fa-check"></i>' + data.message,
                                time: 5
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', error);
                    }
                });
            }

            var applyElement = document.getElementById('Apply');
            var screeningElement = document.getElementById('Screening');
            var submittedElement = document.getElementById('Submitted');
            var offeredElement = document.getElementById('Offered');
            var onboardingElement = document.getElementById('Onboarding');
            var workingElement = document.getElementById('Working');
            var doneElement = document.getElementById('Done');
            var holdElement = document.getElementById('Hold');
            var rejectedElement = document.getElementById('Rejected');
            var blockedElement = document.getElementById('Blocked');
            var childDoneElement = document.getElementById('child_done');

            if (applyElement.classList.contains("active")) {
                applyElement.classList.remove("active");
            }
            if (screeningElement.classList.contains("active")) {
                screeningElement.classList.remove("active");
            }
            if (submittedElement.classList.contains("active")) {
                submittedElement.classList.remove("active");
            }
            if (offeredElement.classList.contains("active")) {
                offeredElement.classList.remove("active");
            }
            if (onboardingElement.classList.contains("active")) {
                onboardingElement.classList.remove("active");
            }
            if (workingElement.classList.contains("active")) {
                workingElement.classList.remove("active");
            }
            if (doneElement.classList.contains("active")) {
                doneElement.classList.remove("active");
            }

            if (rejectedElement.classList.contains("active")) {
                rejectedElement.classList.remove("active");
            }
            if (blockedElement.classList.contains("active")) {
                blockedElement.classList.remove("active");
            }
            if (holdElement.classList.contains("active")) {
                holdElement.classList.remove("active");
            }
            var activeElement = document.getElementById(type);

            if (activeElement) {
                activeElement.classList.add("active");
                childDoneElement.classList.add("active");
                if (type == 'Rejected' || type == 'Blocked') {
                    childDoneElement.classList.remove("active");
                    doneElement.classList.add("active");
                }
            }
            document.getElementById('listingname').innerHTML = type + ' Applications';
            if (type == 'Done' || type == 'Rejected' || type == 'Blocked') {

                document.getElementById("ss-appli-done-hed-btn-dv").classList.remove("d-none");
            } else {
                document.getElementById("ss-appli-done-hed-btn-dv").classList.add("d-none");
            }
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                console.log(formtype);
                console.log(type);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-application-listing') }}",
                    data: {
                        'token': csrfToken,
                        'type': type,
                        'id': id,
                        'formtype': formtype,
                        'jobid': jobid,
                        'nurse_id':nurseId
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {

                        $("#application-list").html(result.applicationlisting);
                        $("#application-details").html(result.applicationdetails);
                        window.allspecialty = result.allspecialty;
                        window.allvaccinations = result.allvaccinations;
                        window.allcertificate = result.allcertificate;
                        list_specialities();
                        list_vaccinations();
                        list_certifications();
                        var files = result.files;
                        console.log(files);
                        var tbody = $('tbody');
                        tbody.empty(); // Clear the table body
                        // Add a row for each file
                        if (files) {
                        for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                var base64String = file.content;

                                const mimeType = base64String.match(/^data:(.+);base64,/)[1];
                                const base64Data = base64String.split(',')[1];
                                const byteCharacters = atob(base64Data);
                                const byteNumbers = new Array(byteCharacters.length);
                                for (let j = 0; j < byteCharacters.length; j++) {
                                    byteNumbers[j] = byteCharacters.charCodeAt(j);
                                }
                                const byteArray = new Uint8Array(byteNumbers);
                                const blob = new Blob([byteArray], { type: mimeType });
                                const blobUrl = URL.createObjectURL(blob);
                                const downloadLink = document.createElement('a');
                                downloadLink.href = blobUrl;
                                const extension = mimeType.split('/')[1];
                                downloadLink.setAttribute('download', `document.${extension}`);

                                var row = $('<tr></tr>');
                                row.append('<td>' + file.name + '</td>');
                                row.append('<td>' + file.type + '</td>');
                                row.append('<td><a href="javascript:void(0);" onclick="this.nextElementSibling.click()">Download</a><a style="display:none;" href="'+ downloadLink.href +'" download="document.' + extension + '"></a></td>');
                                tbody.append(row);
                        }
                    }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        function showDetails(id) {
            console.log(id);
        }

        function sendOffer(type, userid, jobid) {
            document.getElementById("offer-form").classList.remove("d-none");
            document.getElementById("application-details").classList.add("d-none");
            }
            

            var addmoreexperience = document.querySelector('.add-more-experience');

            if (addmoreexperience) {
            addmoreexperience.onclick = function() {
                var allExperienceDiv = document.getElementById('all-experience');
                var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
                newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
                newExperienceDiv.querySelector('input[type="text"]').value = '';
                allExperienceDiv.appendChild(newExperienceDiv);
            }
        }

        function addmoreexperience() {
            var allExperienceDiv = document.getElementById('all-experience');
            var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
            newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
            newExperienceDiv.querySelector('input[type="text"]').value = '';
            allExperienceDiv.appendChild(newExperienceDiv);
        }

        function addvacc() {
            var formfield = document.getElementById('add-more-vacc-immu');
            var newField = document.createElement('input');
            newField.setAttribute('type', 'text');
            newField.setAttribute('name', 'vaccinations');
            newField.setAttribute('onfocusout', 'editJob(this)');
            newField.setAttribute('class', 'mb-3');
            newField.setAttribute('placeholder', 'Enter Vacc. or Immu. name');
            formfield.appendChild(newField);
        }

        function addcertifications() {
            var formfieldcertificate = document.getElementById('add-more-certifications');
            var newField = document.createElement('input');
            newField.setAttribute('type', 'text');
            newField.setAttribute('onfocusout', 'editJob(this)');
            newField.setAttribute('name', 'certificate');
            newField.setAttribute('class', 'mb-3');
            newField.setAttribute('placeholder', 'Enter Certification');
            formfieldcertificate.appendChild(newField);
        }

        
        $(document).ready(function() {

            $('#send-job-offer').on('submit', function(event) {
                event.preventDefault();
                var $form = $('#send-job-offer');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (!csrfToken) {
                    console.error('CSRF token not found.');
                    return;
                }
                var formData = $form.serialize();
                print_r(formData);
                return;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{{ route('recruiter-send-job-offer') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX request error:', error);
                    }
                });
            });
        });

        function editJob(inputField) {
            var value = inputField.value;
            var name = inputField.name;
            if (value != "") {
                if (name == "vaccinations" || name == "preferred_specialty" || name == "preferred_experience" || name ==
                    "certificate") {
                    var inputFields = document.querySelectorAll(name == "vaccinations" ? 'input[name="vaccinations"]' :
                        name == "preferred_specialty" ? 'select[name="preferred_specialty"]' : name ==
                        "preferred_experience" ? 'input[name="preferred_experience"]' : 'input[name="certificate"]');
                    var data = [];
                    inputFields.forEach(function(input) {
                        data.push(input.value);
                    });
                    console.log(data.join(', '));
                    value = data.join(', ');
                }
                var formData = {};
                formData[inputField.name] = value;
                formData['job_id'] = document.getElementById('job_id').value;
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ url('recruiter/recruiter-create-opportunity') }}/update",
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                        if (document.getElementById('job_id').value.trim() == '' || document.getElementById(
                                'job_id').value.trim() == 'null' || document.getElementById('job_id').value
                            .trim() == null) {
                            document.getElementById("job_id").value = data.job_id;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        }

        function offerSend(id, jobid, type) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/send-job-offer-recruiter') }}",
                    data: {
                        'token': csrfToken,
                        'id': id,
                        'jobid': jobid,
                        'type': type,
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check">Counter Offer Sended</i>',
                            time: 5
                        });
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 3000);
                    },
                    error: function(error) {
                        // Handle errors
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        
        setInterval(function() {
            $(document).ready(function() {
                $('.application-job-slider-owl').owlCarousel({
                    items: 3,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    margin: 20,
                    nav: false,
                    dots: false,
                    navText: ['<span class="fa fa-angle-left  fa-2x"></span>',
                        '<span class="fas fa fa-angle-right fa-2x"></span>'
                    ],
                    responsive: {
                        0: {
                            items: 1
                        },
                        480: {
                            items: 2
                        },
                        768: {
                            items: 3
                        },
                        992: {
                            items: 2
                        }
                    }
                })
            })
        }, 3000)
    </script>
    <script>
        var speciality = {};

        function add_speciality(obj) {
            if (!$('#preferred_specialty').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the speciality please.',
                    time: 3
                });
            } else if (!$('#preferred_experience').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Enter total year of experience.',
                    time: 3
                });
            } else {
                if (!speciality.hasOwnProperty($('#preferred_specialty').val())) {
                    speciality[$('#preferred_specialty').val()] = $('#preferred_experience').val();
                    $('#preferred_experience').val('');
                    $('#preferred_specialty').val('');
                    list_specialities();
                }
            }
        }

        function remove_speciality(obj, key) {
            if (speciality.hasOwnProperty($(obj).data('key'))) {
                var element = document.getElementById("remove-speciality");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    if (document.getElementById('job_id').value) {
                        let formData = {
                            'job_id': document.getElementById('job_id').value,
                            'specialty': key,
                        }
                        let removetype = 'specialty';
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            url: "{{ url('recruiter/remove') }}/" + removetype,
                            data: formData,
                            dataType: 'json',
                            success: function(data) {

                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                    delete speciality[$(obj).data('key')];
                    delete window.allspecialty[$(obj).data('key')];
                } else {
                    console.error('CSRF token not found.');
                }
                list_specialities();
            }
        }

        function list_specialities() {
            var str = '';
            if (window.allspecialty) {
                speciality = Object.assign({}, speciality, window.allspecialty);
            }
            for (const key in speciality) {
                let specialityname = "";

                var select = document.getElementById("preferred_specialty");
                var allspcldata = [];
                for (var i = 0; i < select.options.length; i++) {
                    var obj = {
                        'id': select.options[i].value,
                        'title': select.options[i].textContent,
                    };
                    allspcldata.push(obj);
                }

                if (speciality.hasOwnProperty(key)) {
                    allspcldata.forEach(function(item) {
                        if (key == item.id) {
                            specialityname = item.title;
                        }
                    });
                    const value = speciality[key];
                    str += '<ul>';
                    str += '<li>' + specialityname + '</li>';
                    str += '<li>' + value + ' Years</li>';
                    str += '<li><button type="button"  id="remove-speciality" data-key="' + key +
                        '" onclick="remove_speciality(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.speciality-content').html(str);
        }
    </script>
    <script>
        var vaccinations = {};

        function addvacc() {
            if (!$('#vaccinations').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                    time: 3
                });
            } else {
                if (!vaccinations.hasOwnProperty($('#vaccinations').val())) {
                    console.log($('#vaccinations').val());

                    var select = document.getElementById("vaccinations");
                    var selectedOption = select.options[select.selectedIndex];
                    var optionText = selectedOption.textContent;

                    vaccinations[$('#vaccinations').val()] = optionText;
                    $('#vaccinations').val('');
                    list_vaccinations();
                }
            }
        }

        function list_vaccinations() {
            var str = '';
            if (window.allvaccinations) {
                vaccinations = Object.assign({}, vaccinations, window.allvaccinations);
            }
            for (const key in vaccinations) {
                let vaccinationsname = "";
                var select = document.getElementById("vaccinations");
                console.log(select);
                var allspcldata = [];
                for (var i = 0; i < select.options.length; i++) {
                    var obj = {
                        'id': select.options[i].value,
                        'title': select.options[i].textContent,
                    };
                    allspcldata.push(obj);
                }

                if (vaccinations.hasOwnProperty(key)) {

                    allspcldata.forEach(function(item) {
                        if (key == item.id) {
                            vaccinationsname = item.title;
                        }
                    });
                    const value = vaccinations[key];
                    str += '<ul>';
                    str += '<li class="w-50">' + vaccinationsname + '</li>';
                    str += '<li class="w-50 text-end"><button type="button"  id="remove-vaccinations" data-key="' + key +
                        '" onclick="remove_vaccinations(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.vaccinations-content').html(str);
        }

        function remove_vaccinations(obj, key) {
            if (vaccinations.hasOwnProperty($(obj).data('key'))) {

                var element = document.getElementById("remove-vaccinations");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    if (document.getElementById('job_id').value) {
                        let formData = {
                            'job_id': document.getElementById('job_id').value,
                            'vaccinations': key,
                        }
                        let removetype = 'vaccinations';
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            url: "{{ url('recruiter/remove') }}/" + removetype,
                            data: formData,
                            dataType: 'json',
                            success: function(data) {
                                // notie.alert({
                                //     type: 'success',
                                //     text: '<i class="fa fa-check"></i> ' + data.message,
                                //     time: 5
                                // });
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                    delete window.allvaccinations[$(obj).data('key')];
                    delete vaccinations[$(obj).data('key')];
                } else {
                    console.error('CSRF token not found.');
                }
                list_vaccinations();
            }
        }
    </script>
    <script>
        var certificate = {};

        function addcertifications() {
            if (!$('#certificate').val()) {
                notie.alert({
                    type: 'error',
                    text: '<i class="fa fa-check"></i> Select the certificate please.',
                    time: 3
                });
            } else {
                if (!certificate.hasOwnProperty($('#certificate').val())) {
                    console.log($('#certificate').val());

                    var select = document.getElementById("certificate");
                    var selectedOption = select.options[select.selectedIndex];
                    var optionText = selectedOption.textContent;

                    certificate[$('#certificate').val()] = optionText;
                    $('#certificate').val('');
                    list_certifications();
                }
            }
        }

        function list_certifications() {
            var str = '';
            if (window.allcertificate) {
                certificate = Object.assign({}, certificate, window.allcertificate);
            }
            for (const key in certificate) {
                let certificatename = "";
                var select = document.getElementById("certificate");
                console.log(select);
                var allspcldata = [];
                for (var i = 0; i < select.options.length; i++) {
                    var obj = {
                        'id': select.options[i].value,
                        'title': select.options[i].textContent,
                    };
                    allspcldata.push(obj);
                }

                if (certificate.hasOwnProperty(key)) {
                    allspcldata.forEach(function(item) {
                        if (key == item.id) {
                            certificatename = item.title;
                        }
                    });
                    const value = certificate[key];
                    str += '<ul>';
                    str += '<li class="w-50">' + certificatename + '</li>';
                    str += '<li class="w-50 text-end"><button type="button"  id="remove-certificate" data-key="' + key +
                        '" onclick="remove_certificate(this, ' + key +
                        ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                    str += '</ul>';
                }
            }
            $('.certificate-content').html(str);
        }

        function remove_certificate(obj, key) {
            if (certificate.hasOwnProperty($(obj).data('key'))) {
                var element = document.getElementById("remove-certificate");
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    event.preventDefault();
                    if (document.getElementById('job_id').value) {
                        let formData = {
                            'job_id': document.getElementById('job_id').value,
                            'certificate': key,
                        }
                        let removetype = 'certificate';
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            url: "{{ url('recruiter/remove') }}/" + removetype,
                            data: formData,
                            dataType: 'json',
                            success: function(data) {

                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                    delete window.allcertificate[$(obj).data('key')];
                    delete certificate[$(obj).data('key')];
                } else {
                    console.error('CSRF token not found.');
                }
                list_certifications();
            }
        }
    </script>
    <script>
        function askWorker(e, type, workerid, jobid) {
            // when we have the notification system inmplemented we will use this :

            // var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // if (csrfToken) {
            //     $.ajax({
            //         headers: {
            //             'X-CSRF-TOKEN': csrfToken
            //         },
            //         url: "{{ url('recruiter/ask-recruiter-notification') }}",
            //         data: {
            //             'token': csrfToken,
            //             'worker_id': workerid,
            //             'update_key': type,
            //             'job_id': jobid,
            //         },
            //         type: 'POST',
            //         dataType: 'json',
            //         success: function(result) {
            //             notie.alert({
            //                 type: 'success',
            //                 text: '<i class="fa fa-check"></i> ' + result.message,
            //                 time: 5
            //             });
            //         },
            //         error: function(error) {
            //             // Handle errors
            //         }
            //     });
            // } else {
            //     console.error('CSRF token not found.');
            // }

            // for now just redirecting to messages page
            let url = "{{ url('recruiter/recruiter-messages') }}";
             window.location = url + '?worker_id=' + workerid + '&job_id=' + jobid;
            // window.location = url;
        }

        function chatNow(id) {
            localStorage.setItem('nurse_id', id);
        }
        const numberOfReferencesField = document.getElementById('number_of_references');
        if (numberOfReferencesField) {
            numberOfReferencesField.addEventListener('input', function() {
                if (numberOfReferencesField.value.length > 9) {
                    numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
                }
            });
        }
        // $(document).ready(function() {
        //     let formData = {
        //         'country_id': '233',
        //         'api_key': '123',
        //     }
        //     $.ajax({
        //         type: 'POST',
        //         url: "{{ url('api/get-states') }}",
        //         data: formData,
        //         dataType: 'json',
        //         success: function(data) {
        //             var stateSelect = $('#facility-state-code');
        //             stateSelect.empty();
        //             stateSelect.append($('<option>', {
        //                 value: "",
        //                 text: "Select Facility State Code"
        //             }));
        //             $.each(data.data, function(index, state) {
        //                 stateSelect.append($('<option>', {
        //                     value: state.state_id,
        //                     text: state.name
        //                 }));
        //             });
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        // });

        function searchCity(e) {
            var selectedValue = e.value;
            console.log("Selected Value: " + selectedValue);
            let formData = {
                'state_id': selectedValue,
                'api_key': '123',
            }
            $.ajax({
                type: 'POST',
                url: "{{ url('api/get-cities') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var stateSelect = $('#facility-city');
                    stateSelect.empty();
                    stateSelect.append($('<option>', {
                        value: "",
                        text: "Select Facility City"
                    }));
                    $.each(data.data, function(index, state) {
                        stateSelect.append($('<option>', {
                            value: state.state_id,
                            text: state.name
                        }));
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function getSpecialitiesByProfession(e) {
            var selectedValue = e.value;
            let formData = {
                'profession_id': selectedValue,
                'api_key': '123',
            }
            $.ajax({
                type: 'POST',
                url: "{{ url('api/get-profession-specialities') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    var stateSelect = $('#preferred_specialty');
                    stateSelect.empty();
                    stateSelect.append($('<option>', {
                        value: "",
                        text: "Select Specialty"
                    }));
                    $.each(data.data, function(index, state) {
                        stateSelect.append($('<option>', {
                            value: state.state_id,
                            text: state.name
                        }));
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function open_modal(obj) {
            let name, title, modal, form, target;

            name = $(obj).data('name');
            title = $(obj).data('title');
            target = $(obj).data('target');

            modal = '#' + target + '_modal';
            form = modal + '_form';
            $(form).find('h4').html(title);

            $(modal).modal('show');
        }

        function open_stripe_modal() {
            let name, title, modal;

            name = 'stripe';
            title = 'add payment method';
            modal = '#stripe_modal';



            $(modal).modal('show');
        }

        function close_modal(obj) {
            let target = $(obj).data('target');
            $(target).modal('hide');
        }

        function close_stripe_modal() {
            let target = '#stripe_modal';
            $(target).modal('hide');
        }

        function counterOffer(offerId){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-offer-information') }}",
                    data: {
                        'token': csrfToken,
                        'offer_id' : offerId,
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        $("#application-details").html(result.content);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        function selectOfferCycleState(type){
            applicationStatusToggle(type);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-offers-by-type') }}",
                    data: {
                        'token': csrfToken,
                        'type' : type,
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        $("#application-list").html(result.content);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        function applicationStatusToggle(type){
            noApplicationDetailsContent();
            $("#listingname").removeClass("d-none");
            var applyElement = document.getElementById('Apply');
            var screeningElement = document.getElementById('Screening');
            var submittedElement = document.getElementById('Submitted');
            var offeredElement = document.getElementById('Offered');
            var onboardingElement = document.getElementById('Onboarding');
            var workingElement = document.getElementById('Working');
            var doneElement = document.getElementById('Done');
            var holdElement = document.getElementById('Hold');
            var rejectedElement = document.getElementById('Rejected');
            var blockedElement = document.getElementById('Blocked');
            var childDoneElement = document.getElementById('child_done');
            if (applyElement.classList.contains("active")) {
                applyElement.classList.remove("active");
            }
            if (screeningElement.classList.contains("active")) {
                screeningElement.classList.remove("active");
            }
            if (submittedElement.classList.contains("active")) {
                submittedElement.classList.remove("active");
            }
            if (offeredElement.classList.contains("active")) {
                offeredElement.classList.remove("active");
            }
            if (onboardingElement.classList.contains("active")) {
                onboardingElement.classList.remove("active");
            }
            if (workingElement.classList.contains("active")) {
                workingElement.classList.remove("active");
            }
            if (doneElement.classList.contains("active")) {
                doneElement.classList.remove("active");
            }
            if (rejectedElement.classList.contains("active")) {
                rejectedElement.classList.remove("active");
            }
            if (blockedElement.classList.contains("active")) {
                blockedElement.classList.remove("active");
            }
            if (holdElement.classList.contains("active")) {
                holdElement.classList.remove("active");
            }
            var activeElement = document.getElementById(type);
            if (activeElement) {
                activeElement.classList.add("active");
                childDoneElement.classList.add("active");
                if (type == 'Rejected' || type == 'Blocked') {
                    childDoneElement.classList.remove("active");
                    doneElement.classList.add("active");
                }
            }
            document.getElementById('listingname').innerHTML = type + ' Applications';
            if (type == 'Done' || type == 'Rejected' || type == 'Blocked') {

                document.getElementById("ss-appli-done-hed-btn-dv").classList.remove("d-none");
            } else {
                document.getElementById("ss-appli-done-hed-btn-dv").classList.add("d-none");
            }
        }

        function getOffersOfEachWorker(type,nurseId){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-offers-of-each-worker') }}",
                    data: {
                        'token': csrfToken,
                        'type' : type,
                        'nurse_id': nurseId
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                         $("#application-details").html(result.content);
                        console.log(result.content);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }
        function getOneOfferInformation(offerId){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/get-one-offer-information') }}",
                    data: {
                        'token': csrfToken,
                        'offer_id': offerId
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                         $("#application-details").html(result.content);
                        console.log(result.content);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        function applicationStatus(applicationstatus, id) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },

                    url: "{{ url('recruiter/update-application-status') }}",
                    data: {
                        'token': csrfToken,
                        'id': id,
                        'status': applicationstatus,
                        
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        notie.alert({
                            type: 'success',
                            text: result.message,
                            time: 5
                        });
                        const statusKeys = ['Apply', 'Screening', 'Submitted', 'Offered', 'Onboarding', 'Working', 'Rejected', 'Blocked', 'Hold'];

                        statusKeys.forEach(key => {
                            $(`#${key} span`).text(`${result.statusCounts[key]} Applicants`);
                        });

                        setTimeout(() => {
                            applicationStatusToggle(applicationstatus);
                            $('#' + applicationstatus).click();
                        }, 1000);

                    },
                    error: function(result) {
                        notie.alert({
                            type: 'error',
                            text: 'Oops ! Try again later. ',
                            time: 5
                        });

                        // open_stripe_modal();
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        }

        $(document).ready(function() {
            noApplicationDetailsContent();
            noApplicationWorkerListContent();
        });

        function noApplicationWorkerListContent(){
            $("#application-list").html("<div class='text-center no_details'><span>Select an application status</span></div>");
        }
        function noApplicationDetailsContent(){
            $("#application-details").html("<div class='text-center no_details'><span>Select a worker application</span></div>");
        }
        
    </script>
    <style>
        .ss-job-prfle-sec:after {
            background-image: none;
        }
        .no_details{
            margin-top:15vh;
            margin-bottom:15vh;
        }
        #application-details{
            height: fit-content;
        }
        #application-list{
            height: fit-content;
        }
    </style>
@endsection

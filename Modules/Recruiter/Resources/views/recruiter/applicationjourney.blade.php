@extends('recruiter::layouts.main')

@section('content')
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">
        <h2>Help your <span class="ss-color-pink">applicants advance!</span></h2>
        <div class="row mt-4 applicants-header text-center">
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Apply')" id="Apply">
                    <p>New</p>
                    <span>{{$statusCounts['Apply']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Screening')" id="Screening">
                    <p>Screening</p>
                    <span>{{$statusCounts['Screening']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Submitted')" id="Submitted">
                    <p>Submitted</p>
                    <span>{{$statusCounts['Submitted']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Offered')" id="Offered">
                    <p>Offered</p>
                    <span>{{$statusCounts['Offered']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Onboarding')" id="Onboarding">
                    <p>Onboarding</p>
                    <span>{{$statusCounts['Onboarding']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Working')" id="Working">
                    <p>Working</p>
                    <span>{{$statusCounts['Working']}} Applicants</span>
                </div>
            </div>
            <div style="flex: 1 1 0px;">
                <div class="ss-job-prfle-sec" onclick="applicationType('Done')" id="Done">
                    <p>Done</p>
                    <span>{{$statusCounts['Done']}} Applicants</span>
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
                        <li><a href="javascript:void(0)" onclick="applicationType('Done')" class="active">Done</a></li>
                        <li><a href="javascript:void(0)" onclick="applicationType('Rejected')">Rejected</a></li>
                        <li><a href="javascript:void(0)" onclick="applicationType('Blocked')">Blocked</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
        </div> -->
        <div class="ss-acount-profile">
            <div class="row">
                <div class="col-lg-5">
                    <div class="ss-account-form-lft-1">
                        <h5 class="mb-4" id="listingname">New application</h5>
                        <div id="application-list">
                            <!-- <div class="d-flex justify-content-between">
                                <a href="" class="p-0 bg-transparent">1112323</a>
                                <i>Recently Added</i>
                            </div>
                            <div class="d-flex">
                                <img src="" alt="" class="mr-2">
                                <h4>James Bond</h4>
                            </div>
                            <ul>
                                <li><a href="#">Los Angeles, CA</a></li>
                                <li><a href="#">10 wks</a></li>
                                <li><a href="#">2500/wk</a></li>
                            </ul> -->
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
</main>
<script>
    function applicationType(type, id = "", formtype, jobid = "") {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

        if(formtype == "joballdetails" || formtype == "createdraft"){
            event.preventDefault();
            var $form = $('#send-job-offer');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('CSRF token not found.');
                return;
            }
            var formData = $form.serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('recruiter-send-job-offer') }}",
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if(type == "createdraft"){
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Draft Created Successfully',
                            time: 5
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
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

        document.getElementById(type).classList.add("active")

        document.getElementById('listingname').innerHTML = type + ' Application';
        if (type == 'Done' || type == 'Rejected' || type == 'Blocked') {
            document.getElementById("ss-appli-done-hed-btn-dv").classList.remove("d-none");
        } else {
            document.getElementById("ss-appli-done-hed-btn-dv").classList.add("d-none");
        }
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
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
                    'jobid': jobid
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
                },
                error: function(error) {
                    // Handle errors
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
    $(document).ready(function() {
        applicationType('Apply')
    });

    // var addmoreexperience = document.querySelector('.add-more-experience');

    // addmoreexperience.onclick = function() {
    //     var allExperienceDiv = document.getElementById('all-experience');
    //     var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
    //     newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
    //     newExperienceDiv.querySelector('input[type="text"]').value = '';
    //     allExperienceDiv.appendChild(newExperienceDiv);
    // }

    // function addmoreexperience() {
    //     var allExperienceDiv = document.getElementById('all-experience');
    //     var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
    //     newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
    //     newExperienceDiv.querySelector('input[type="text"]').value = '';
    //     allExperienceDiv.appendChild(newExperienceDiv);
    // }

    // function addvacc() {
    //     var formfield = document.getElementById('add-more-vacc-immu');
    //     var newField = document.createElement('input');
    //     newField.setAttribute('type', 'text');
    //     newField.setAttribute('name', 'vaccinations');
    //     newField.setAttribute('onfocusout', 'editJob(this)');
    //     newField.setAttribute('class', 'mb-3');
    //     newField.setAttribute('placeholder', 'Enter Vacc. or Immu. name');
    //     formfield.appendChild(newField);
    // }

    // function addcertifications() {
    //     var formfieldcertificate = document.getElementById('add-more-certifications');
    //     var newField = document.createElement('input');
    //     newField.setAttribute('type', 'text');
    //     newField.setAttribute('onfocusout', 'editJob(this)');
    //     newField.setAttribute('name', 'certificate');
    //     newField.setAttribute('class', 'mb-3');
    //     newField.setAttribute('placeholder', 'Enter Certification');
    //     formfieldcertificate.appendChild(newField);
    // }

    function applicationStatus(applicationstatus, type, id, jobid) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('recruiter/update-application-status') }}",
                data: {
                    'token': csrfToken,
                    'type': type,
                    'id': id,
                    'formtype': applicationstatus,
                    'jobid': jobid,
                },
                type: 'POST',
                dataType: 'json',
                success: function(result) {

                    $("#application-list").html(result.applicationlisting);
                    $("#application-details").html(result.applicationdetails);
                },
                error: function(error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
    // $(document).ready(function() {
    //     $('#send-job-offer').on('submit', function(event) {
    //         event.preventDefault();
    //         var $form = $('#send-job-offer');
    //         var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //         if (!csrfToken) {
    //             console.error('CSRF token not found.');
    //             return;
    //         }
    //         var formData = $form.serialize();
    //         print_r(formData);
    //         return;
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             type: 'POST',
    //             url: "{{ route('recruiter-send-job-offer') }}",
    //             data: formData,
    //             dataType: 'json',
    //             success: function(data) {
    //                 notie.alert({
    //                     type: 'success',
    //                     text: '<i class="fa fa-check"></i> ' + data.message,
    //                     time: 5
    //                 });
    //             },
    //             error: function(xhr, status, error) {
    //                 console.log('AJAX request error:', error);
    //             }
    //         });
    //     });
    // });

    // function editJob(inputField) {
    //     var value = inputField.value;
    //     var name = inputField.name;
    //     if (value != "") {
    //         if (name == "vaccinations" || name == "preferred_specialty" || name == "preferred_experience" || name == "certificate") {
    //             var inputFields = document.querySelectorAll(name == "vaccinations" ? 'input[name="vaccinations"]' : name == "preferred_specialty" ? 'select[name="preferred_specialty"]' : name == "preferred_experience" ? 'input[name="preferred_experience"]' : 'input[name="certificate"]');
    //             var data = [];
    //             inputFields.forEach(function(input) {
    //                 data.push(input.value);
    //             });
    //             console.log(data.join(', '));
    //             value = data.join(', ');
    //         }
    //         var formData = {};
    //         formData[inputField.name] = value;
    //         formData['job_id'] = document.getElementById('job_id').value;
    //         var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': csrfToken
    //             },
    //             type: 'POST',
    //             url: "{{ url('recruiter/recruiter-create-opportunity') }}/update",
    //             data: formData,
    //             dataType: 'json',
    //             success: function(data) {
    //                 notie.alert({
    //                     type: 'success',
    //                     text: '<i class="fa fa-check"></i> ' + data.message,
    //                     time: 5
    //                 });
    //                 if (document.getElementById('job_id').value.trim() == '' || document.getElementById('job_id').value.trim() == 'null' || document.getElementById('job_id').value.trim() == null) {
    //                     document.getElementById("job_id").value = data.job_id;
    //                 }
    //             },
    //             error: function(error) {
    //                 console.log(error);
    //             }
    //         });
    //     }
    // }

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
                        text: '<i class="fa fa-check"></i> ' + result.message,
                        time: 5
                    });
                },
                error: function(error) {
                    // Handle errors
                }
            });
        } else {
            console.error('CSRF token not found.');
        }
    }
    setInterval(function(){
            $(document).ready(function(){
                $('.application-job-slider-owl').owlCarousel({
                    items: 3,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    margin: 20,
                    nav: false,
                    dots: false,
                    navText: ['<span class="fa fa-angle-left  fa-2x"></span>', '<span class="fas fa fa-angle-right fa-2x"></span>'],
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
        }
    , 3000)
</script>
<script>
    var speciality = {};

    // console.log(window.allspecialty)
    // console.log(window.allvaccinations)
    // console.log(window.allcertificate)

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
        if(window.allspecialty){
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
                str += '<li><button type="button"  id="remove-speciality" data-key="' + key + '" onclick="remove_speciality(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.speciality-content').html(str);
    }
</script>
<script>
    var vaccinations = {};

    function addvacc() {

        // var container = document.getElementById('add-more-certifications');

        // var newSelect = document.createElement('select');
        // newSelect.name = 'certificate';
        // newSelect.className = 'mb-3';

        // var originalSelect = document.getElementById('certificate');
        // var options = originalSelect.querySelectorAll('option');
        // for (var i = 0; i < options.length; i++) {
        //     var option = options[i].cloneNode(true);
        //     newSelect.appendChild(option);
        // }
        // container.querySelector('.col-md-11').appendChild(newSelect);

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
        if(window.allvaccinations){
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
                str += '<li class="w-50 text-end"><button type="button"  id="remove-vaccinations" data-key="' + key + '" onclick="remove_vaccinations(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations (obj, key){
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
        if(window.allcertificate){
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
                str += '<li class="w-50 text-end"><button type="button"  id="remove-certificate" data-key="' + key + '" onclick="remove_certificate(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }

    function remove_certificate (obj, key){
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
    function askWorker(e, type, workerid, jobid){
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      if (csrfToken) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          url: "{{ url('recruiter/ask-recruiter-notification') }}",
          data: {
            'token': csrfToken,
            'worker_id': workerid,
            'update_key': type,
            'job_id': jobid,
          },
          type: 'POST',
          dataType: 'json',
          success: function(result) {
            notie.alert({
              type: 'success',
              text: '<i class="fa fa-check"></i> ' + result.message,
              time: 5
            });
          },
          error: function(error) {
            // Handle errors
          }
        });
      } else {
        console.error('CSRF token not found.');
      }
    }
    function chatNow(id){
        localStorage.setItem('nurse_id', id);
    }
    const numberOfReferencesField = document.getElementById('number_of_references');
    numberOfReferencesField.addEventListener('input', function() {
        if (numberOfReferencesField.value.length > 9) {
            numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
        }
    });
    $(document).ready(function () {
        let formData = {
            'country_id': '233',
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-states') }}",
            data: formData,
            dataType: 'json',
            success: function(data) {
                var stateSelect = $('#facility-state-code');
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility State Code"
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
    });
    function searchCity(e){
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
    function getSpecialitiesByProfession(e){
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
</script>
@endsection

@extends('employer::layouts.main')

@section('content')
<main style="padding-top: 120px" class="ss-main-body-sec">
    <div class="container">
        <div class="ss-opport-mngr-mn-div-sc">
            <div class="ss-opport-mngr-hedr">
                <div class="row">
                    <div class="col-lg-6">
                        <h4>Opportunities Manager</h4>
                    </div>
                    <div class="col-lg-6">
                        <ul>
                            <li><button id="drafts" onclick="opportunitiesType('drafts')"
                                    class="ss-darfts-sec-draft-btn">Drafts</button></li>
                            <li><button id="published" onclick="opportunitiesType('published')"
                                    class="ss-darfts-sec-publsh-btn">Published</button></li>
                            <li><button id="hidden" onclick="opportunitiesType('hidden')"
                                    class="ss-darfts-sec-publsh-btn">Hidden</button></li>
                            <li><button id="closed" onclick="opportunitiesType('closed')"
                                    class="ss-darfts-sec-publsh-btn">Close</button></li>
                            <li><a href="{{ route('recruiter-create-opportunity') }}" class="ss-opr-mngr-plus-sec"><i
                                        class="fas fa-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>
<script>


    function editJob(type) {
        $('#' + type).focus();
    }

    function addvacc() {
        var container = document.getElementById('add-more-vacc-immu');

        var newSelect = document.createElement('select');
        newSelect.name = 'vaccinations';
        newSelect.className = 'vaccinations mb-3';

        var originalSelect = document.getElementById('vacc-immu');
        var options = originalSelect.querySelectorAll('option');
        for (var i = 0; i < options.length; i++) {
            var option = options[i].cloneNode(true);
            newSelect.appendChild(option);
        }
        container.querySelector('.col-md-11').appendChild(newSelect);
    }



    $(document).ready(function () {
        $('#create-new-job').on('submit', function (event) {

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {

                event.preventDefault();

                var formData = $(this).serialize();
                formData += '&active=' + encodeURIComponent(1);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    // url: '{{ url("recruiter/recruiter-create-opportunity/create") }}',
                    url: "{{ route('recruiter-create-opportunity-store', ['check_type' => 'create']) }}",

                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }

        });
    });

    function createJob() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            event.preventDefault();
            let check_type = "published";
            if (document.getElementById('job_id').value) {
                 let formData = {
                    'job_i d': document.getElementById('job_id').value
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                    },
                    error: function (error) {
                         console.log(error);
                    }
                });
            }
        } else {
            console.error('CSRF token not found.');
        }
    }

    function createDraft() {
        if (document.getElementById('job_name').value.trim() !== '' && document.getEle mentById('type').value.trim() !== '') {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Draft created successfully',
                time: 5
            });
        } else {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Please enter Job Name and Type',
                time: 5
            });
        }
    }
    document.addEventListener('DOMContentLoaded', function () {

        var myForm = document.getElementById('create-new-job');

        var inputFields = myForm.querySelectorAll('input, select, textarea');
        inputFields.forEach(function (input) {
            input.addEventListener('blur', function () {
                if (document.getElementById('job_name').value.trim() !== '' && document.getElementById('type').value.trim() !== '') {
                    var csrfToken = $('meta[name="csrf-token"]').attr('conten t');
                    if (csrfToken) {
                        event.preventDefault();
                        var formData = "";
                        var check_type = "";
                        if (document.getElementById('job_id').value.trim() !== '') {
                            form Data = $(this).serialize();
                            formName = $(this).attr('name')
                            if (formName == "vaccinations" || formName == "preferred_specialty" || formName == "preferred_experience" || formName == "certificate") {
                                var inputFields = document.querySelectorAll(formName == "vaccinations" ? 'select[name="vaccinations"]' : formName == "preferred_specialty" ? 'select[name="preferred_specialty"]' : formName == "preferred_experience" ? 'input[name="preferred_experience"]' : 'select[name="certificate"]');
                                var data = [];
                                inputFields.forEach(function (input) {
                                    data.push(input.value);
                                });
                                 formData = formName + '=' + data.join(',');
                            }
                            formData += '&job_id=' + encodeURIComponent(document.getElementById( 'job_id').value.trim());
                            check_ty pe = "update";
                        } else {
                            formData = {
                                'job_name': document.getElementById('job_name').value.trim(),
                                'type': document.getElementById('type').value.trim(),
                            };
                            check_type = "create";
                        }
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            // url: "{{ route('recruiter-create-opportunity-store', ['check_type' => '" + urlencode(check_type) + "']) }}",
                            url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

                            data: formData,
                            dataType: 'json',
                            success: function (data) {

                                if (document.getElementById('job_id').value.trim() == '' || document.getElementById('job_id').value.trim() == 'null' || document.getElementById('job_id').value.trim() == null) {
                                     document.getElementById("job_id").value = data.job_id;
                                    document.getElementById("goodwork_number").value = data.goodwork_number;
                                }
                            },
                            error: function (error) {
                                console.log(error);
                            }
                        });
                    } else {
                        console.error('CSRF token not found.');
                    }
                }
            });
        });
    });
</script>


@endsection

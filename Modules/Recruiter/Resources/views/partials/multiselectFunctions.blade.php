<script>

var certificateStr = '';
var vaccinationsStr = '';
var skillsStr = '';
var shifttimeofdayStr = '';
var benefitsStr = '';
var professional_licensureStr = '';
var nurse_classificationStr = '';
var EmrStr = '';

// from edited job
var certificateStrEdit = '';
var vaccinationsStrEdit = '';
var skillsStrEdit = '';
var shifttimeofdayStrEdit = '';
var benefitsStrEdit = '';
var professional_licensureStrEdit = '';
var nurse_classificationStrEdit = '';
var EmrStrEdit = '';

var certificate = {};
var vaccinations = {};
var skills = {};
var shifttimeofday = {};
var benefits = {};
var professional_licensure = {};
var nurse_classification = {};
var Emr = {};



function addcertifications(type) {
        var id;
        var idtitle;
        if (type == 'from_add') {
            id = $('#certificate');
            idtitle = "certificate";
        } else if (type == 'from_draft') {
            id = $('#certificateDraft');
            idtitle = "certificateDraft";
        } else {
            id = $('#certificateEdit');
            idtitle = "certificateEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the certificate please.',
                time: 3
            });
        } else {
            if (!certificate.hasOwnProperty(id.val())) {

                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                certificate[id.val()] = optionText;

                certificateStr = Object.values(certificate).join(', ');
                // console.log(certificate);
                id.val('');
                list_certifications();
            }
        }

    }

    function list_certifications() {
        var str = '';
        // let certificatename = "";
        // certificatename = Object.values(certificate).join(', ');
        // console.log(certificatename);
        // document.getElementById("certificateEdit").value = certificatename;
        // console.log(certificate);

        for (const key in certificate) {
            console.log(certificate);

            let certificatename = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Certification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (certificate.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        certificatename = item.title;
                    }
                });
                const value = certificate[key];
                str += '<ul>';
                str += '<li class="w-50">' + certificatename + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-certificate" data-key="' +
                    key + '" onclick="remove_certificate(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }

    function remove_certificate(obj, key) {
        if (certificate.hasOwnProperty(key)) {
            delete certificate[key];

            // to dlete the removed certificate from the hidden input
            certificateStr = Object.values(certificate).join(', ');

            list_certifications(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Certificate removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Certificate not found.',
                time: 3
            });
        }
    }


{{-- script managing vaccinations  --}}

    

    function addvacc(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#vaccinations');
            idtitle = "vaccinations";
        } else if (type == 'from_draft') {
            id = $('#vaccinationsDraft');
            idtitle = "vaccinationsDraft";
        } else {
            id = $('#vaccinationsEdit');
            idtitle = "vaccinationsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                time: 3
            });
        } else {
            if (!vaccinations.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                vaccinations[id.val()] = optionText;
                vaccinationsStr = Object.values(vaccinations).join(', ');
                id.val('');
                list_vaccinations();
            }
        }
    }

    function list_vaccinations() {
        var str = '';
        console.log(vaccinations);

        for (const key in vaccinations) {
            console.log(vaccinations);

            let vaccinationsname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (vaccinations.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        vaccinationsname = item.title;
                    }
                });
                const value = vaccinations[key];
                str += '<ul>';
                str += '<li>' + vaccinationsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-vaccinations" data-key="' +
                    key + '" onclick="remove_vaccinations(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations(obj, key) {
        if (vaccinations.hasOwnProperty(key)) {
            delete vaccinations[key]; // Remove the vaccination from the object
            vaccinationsStr = Object.values(vaccinations).join(', '); // Update the hidden input value
            list_vaccinations(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Vaccination removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Vaccination not found.',
                time: 3
            });
        }
    }


{{-- script managing skills --}}


   

    function addskills(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#skills');
            idtitle = "skills";
        } else if (type == 'from_draft') {
            id = $('#skillsDraft');
            idtitle = "skillsDraft";
        } else {
            id = $('#skillsEdit');
            idtitle = "skillsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the skills please.',
                time: 3
            });
        } else {
            if (!skills.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                skills[id.val()] = optionText;
                skillsStr = Object.values(skills).join(', ');
                id.val('');
                list_skills();
            }
        }
    }

    function list_skills() {
        var str = '';
        console.log('fomr skiiiils', skills);

        for (const key in skills) {
            console.log('skills', skills);


            let skillsname = "";
            let allspcldata = @json($allKeywords['Speciality']);

            console.log('allspcldata', allspcldata);

            if (skills.hasOwnProperty(key)) {
                var data = allspcldata;

                data.forEach(function(item) {
                    if (key == item.id) {
                        skillsname = item.title;
                    }
                });
                const value = skills[key];
                str += '<ul>';
                str += '<li>' + skillsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-skills" data-key="' + key +
                    '" onclick="remove_skills(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.skills-content').html(str);
    }

    function remove_skills(obj, key) {
        if (skills.hasOwnProperty(key)) {
            delete skills[key]; // Remove the skill from the object
            skillsStr = Object.values(skills).join(', '); // Update the hidden input value
            list_skills(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Skill removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Skill not found.',
                time: 3
            });
        }
    }


{{-- script managing shifttimeofday --}}

    
    function addshifttimeofday(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#shifttimeofday');
            idtitle = "shifttimeofday";
        } else if (type == 'from_draft') {
            id = $('#shifttimeofdayDraft');
            idtitle = "shifttimeofdayDraft";
        } else {
            id = $('#shifttimeofdayEdit');
            idtitle = "shifttimeofdayEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the shifttimeofday please.',
                time: 3
            });
        } else {
            if (!shifttimeofday.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                shifttimeofday[id.val()] = optionText;
                shifttimeofdayStr = Object.values(shifttimeofday).join(', ');
                console.log('shifttimeofdayStr', shifttimeofdayStr);
                id.val('');
                list_shifttimeofday();
            }
        }
    }

    function list_shifttimeofday() {
        var str = '';
        console.log(shifttimeofday);

        for (const key in shifttimeofday) {
            console.log(shifttimeofday);

            let shifttimeofdayname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (shifttimeofday.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        shifttimeofdayname = item.title;
                    }
                });
                const value = shifttimeofday[key];
                str += '<ul>';
                str += '<li>' + shifttimeofdayname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-shifttimeofday" data-key="' +
                    key + '" onclick="remove_shifttimeofday(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.shifttimeofday-content').html(str);
    }

    function remove_shifttimeofday(obj, key) {
        if (shifttimeofday.hasOwnProperty(key)) {
            delete shifttimeofday[key]; // Remove the shifttimeofday from the object
            shifttimeofdayStr = Object.values(shifttimeofday).join(', '); // Update the hidden input value
            list_shifttimeofday(); // Refresh the list to reflect the deletion
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


{{-- script managing benefits --}}

    

    function addbenefits(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#benefits');
            idtitle = "benefits";
        } else if (type == 'from_draft') {
            id = $('#benefitsDraft');
            idtitle = "benefitsDraft";
        } else {
            id = $('#benefitsEdit');
            idtitle = "benefitsEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the benefits please.',
                time: 3
            });
        } else {
            if (!benefits.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                benefits[id.val()] = optionText;
                benefitsStr = Object.values(benefits).join(', ');
                console.log('benefitsStr', benefitsStr);
                id.val('');
                list_benefits();
            }
        }
    }

    function list_benefits() {
        var str = '';
        console.log(benefits);

        for (const key in benefits) {
            console.log(benefits);

            let benefitsname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Benefits']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (benefits.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        benefitsname = item.title;
                    }
                });
                const value = benefits[key];
                str += '<ul>';
                str += '<li>' + benefitsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-benefits" data-key="' + key +
                    '" onclick="remove_benefits(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.benefits-content').html(str);
    }

    function remove_benefits(obj, key) {
        if (benefits.hasOwnProperty(key)) {
            delete benefits[key]; // Remove the benefits from the object
            benefitsStr = Object.values(benefits).join(', '); // Update the hidden input value
            list_benefits(); // Refresh the list to reflect the deletion
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


{{-- script managing professional_licensure  --}}

   

    function addprofessional_licensure(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#professional_licensure');
            idtitle = "professional_licensure";
        } else if (type == 'from_draft') {
            id = $('#professional_licensureDraft');
            idtitle = "professional_licensureDraft";
        } else {
            id = $('#professional_licensureEdit');
            idtitle = "professional_licensureEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the professional_licensure please.',
                time: 3
            });
        } else {
            if (!professional_licensure.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                professional_licensure[id.val()] = optionText;
                professional_licensureStr = Object.values(professional_licensure).join(', ');
                console.log('professional_licensureStr', professional_licensureStr);
                id.val('');
                list_professional_licensure();
            }
        }
    }

    function list_professional_licensure() {
        var str = '';
        console.log(professional_licensure);

        for (const key in professional_licensure) {
            console.log(professional_licensure);

            let professional_licensurename = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['StateCode']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (professional_licensure.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        professional_licensurename = item.title;
                    }
                });
                const value = professional_licensure[key];
                str += '<ul>';
                str += '<li>' + professional_licensurename + '</li>';
                str +=
                    '<li class="w-50 text-end pe-3"><button type="button"  id="remove-professional_licensure" data-key="' +
                    key + '" onclick="remove_professional_licensure(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.professional_licensure-content').html(str);
    }

    function remove_professional_licensure(obj, key) {
        if (professional_licensure.hasOwnProperty(key)) {
            delete professional_licensure[key]; // Remove the professional_licensure from the object
            professional_licensureStr = Object.values(professional_licensure).join(
            ', '); // Update the hidden input value
            list_professional_licensure(); // Refresh the list to reflect the deletion
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


{{-- script managing Emr  --}}

    

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
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                Emr[id.val()] = optionText;
                EmrStr = Object.values(Emr).join(', ');
                console.log('EmrStr', EmrStr);
                id.val('');
                list_Emr();
            }
        }
    }

    function list_Emr() {
        var str = '';
        console.log(Emr);

        for (const key in Emr) {
            console.log(Emr);

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
                text: '<i class="fa fa-check"></i> Emr removed successfully.',
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


{{-- script managing nurse_classification  --}}

    

    function addnurse_classification(type) {
        let id;
        let idtitle;
        if (type == 'from_add') {
            id = $('#nurse_classification');
            idtitle = "nurse_classification";
        } else if (type == 'from_draft') {
            id = $('#nurse_classificationDraft');
            idtitle = "nurse_classificationDraft";
        } else {
            id = $('#nurse_classificationEdit');
            idtitle = "nurse_classificationEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the nurse_classification please.',
                time: 3
            });
        } else {
            if (!nurse_classification.hasOwnProperty(id.val())) {
                console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                nurse_classification[id.val()] = optionText;
                nurse_classificationStr = Object.values(nurse_classification).join(', ');
                console.log('nurse_classificationStr', nurse_classificationStr);
                id.val('');
                list_nurse_classification();
            }
        }
    }

    function list_nurse_classification() {
        var str = '';
        console.log(nurse_classification);

        for (const key in nurse_classification) {
            console.log(nurse_classification);

            let nurse_classificationname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (nurse_classification.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        nurse_classificationname = item.title;
                    }
                });
                const value = nurse_classification[key];
                str += '<ul>';
                str += '<li>' + nurse_classificationname + '</li>';
                str +=
                    '<li class="w-50 text-end pe-3"><button type="button"  id="remove-nurse_classification" data-key="' +
                    key + '" onclick="remove_nurse_classification(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.nurse_classification-content').html(str);
    }

    function remove_nurse_classification(obj, key) {
        if (nurse_classification.hasOwnProperty(key)) {
            delete nurse_classification[key]; // Remove the nurse_classification from the object
            nurse_classificationStr = Object.values(nurse_classification).join(', '); // Update the hidden input value
            list_nurse_classification(); // Refresh the list to reflect the deletion
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

    var data = {};
    var OfferFieldsName = [
    'job_id',
    'job_name',
    'type',
    'specialty',
    'profession',
    'state',
    'city',
    'weekly_pay',
    'terms',
    'preferred_assignment_duration',
    'facility_shift_cancelation_policy',
    'traveler_distance_from_facility',
    'clinical_setting',
    'Patient_ratio',
    'Unit',
    'scrub_color',
    'rto',
    'guaranteed_hours',
    'hours_per_week',
    'hours_shift',
    'weeks_shift',
    'referral_bonus',
    'sign_on_bonus',
    'completion_bonus',
    'extension_bonus',
    'other_bonus',
    'actual_hourly_rate',
    'overtime',
    'on_call',
    'on_call_rate',
    'holiday',
    'orientation_rate',
    'block_scheduling',
    'float_requirement',
    'number_of_references',
    'urgency',
    'facilitys_parent_system',
    'facility_name',
    'pay_frequency',
    'preferred_experience',
    'contract_termination_policy',
    'health_insaurance',
    'feels_like_per_hour',
    'call_back_rate',
    'weekly_non_taxable_amount',
    'start_date',
    'end_date',
    'preferred_experience',
    'professional_licensure',
    'description',
    'preferred_work_location',
    'as_soon_as',
    'goodwork_weekly_amount',
    'total_organization_amount',
    'total_goodwork_amount',
    'total_contract_amount'
    ];

    

    function getValues(OfferFieldsName) {
        try {
            for (let i = 0; i < OfferFieldsName.length; i++) {
                
                let fieldName = OfferFieldsName[i];
                //console.log(fieldName);
                let fieldValue = document.getElementById(fieldName).value;
                if (fieldValue !== null && fieldValue.trim() !== '') {
                    data[fieldName] = fieldValue;
                }
                
            }
        } catch (error) {
            console.log(error);
        }
    }

    function getDiffBeforeSend(oldData, newData) {
        //console.log('oldData', oldData);
        //console.log('newData', newData)
     
        const changes = {};

        for (const key in newData) {
            let oldValue = oldData[key];
            let newValue = newData[key];
        
            // YES No conditions
            if (oldValue === 0 && newValue === "No") newValue = 0;
            if (oldValue === 1 && newValue === "Yes") newValue = 1;
        
            // if type is number so newData will be number
            if (typeof oldValue === "number") newValue = Number(newValue);
        
            if (oldValue !== newValue) {
                changes[key] = {
                    oldValue,
                    newValue
                };
            }
        }
        
        if (Object.keys(changes).length > 0) {
            //console.log("Changed fields:", changes);
            return changes;
        }else{
            //console.log("No changes");
            return null;
        }

        return changes;
        
    }


    function offerSend(event) {
        
        try {

            event.preventDefault();
            getValues(OfferFieldsName);
            let offerdetails = @json($offerdetails);
            getDiffBeforeSend(offerdetails , data);
            let id = document.getElementById('offer_id').value;
            //console.log(id);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/recruiter-counter-offer') }}",
                    data: {
                        'token': csrfToken,
                        'data': data,
                        'id': id
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Counter Offer Sended',
                            time: 2
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        } catch (error) {
            console.log(error);
        }
        //console.log('offer countered data', data);
    }

    function editOffer(event) {  
        try {
            event.preventDefault();
            getValues(OfferFieldsName);
            let id = document.getElementById('offer_id').value;
            console.log(id);
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: "{{ url('recruiter/update-job-offer') }}",
                    data: {
                        'token': csrfToken,
                        'data': data,
                        'offer_id': id
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function(result) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Offer edited successfully',
                            time: 2
                        });
                        setTimeout(() => {
                            //location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }
        } catch (error) {
            console.log(error);
        }
        console.log('offer edited', data);
    }

    function getOfferDataToEdit(){
        var offerdetails = @json($offerdetails);
        console.log('offerdetails',offerdetails);
        if(offerdetails != null)
        {
            var result = offerdetails;
            console.log('result',result);

            const fields = {
                'id': { id: 'offer_id', type: 'number' },
                'job_id': { id: 'job_id', type: 'number' },
                'job_name': { id: 'job_name', type: 'text' },
                'type': { id: 'type', type: 'select' },
                'specialty': { id: 'specialty', type: 'select' },
                'profession': { id: 'profession', type: 'select' },
                'state': { id: 'state', type: 'select' },
                'city': { id: 'city', type: 'select' },
                'weekly_pay': { id: 'weekly_pay', type: 'number' },
                'terms': { id: 'terms', type: 'select' },
                'preferred_assignment_duration': { id: 'preferred_assignment_duration', type: 'number' },
                'facility_shift_cancelation_policy': { id: 'facility_shift_cancelation_policy', type: 'text' },
                'traveler_distance_from_facility': { id: 'traveler_distance_from_facility', type: 'number' },
                'clinical_setting': { id: 'clinical_setting', type: 'select' },
                'Patient_ratio': { id: 'Patient_ratio', type: 'number' },
                'Unit': { id: 'Unit', type: 'text' },
                'scrub_color': { id: 'scrub_color', type: 'text' },
                'rto': { id: 'rto', type: 'select' },
                'guaranteed_hours': { id: 'guaranteed_hours', type: 'number' },
                'hours_per_week': { id: 'hours_per_week', type: 'number' },
                'hours_shift': { id: 'hours_shift', type: 'number' },
                'weeks_shift': { id: 'weeks_shift', type: 'number' },
                'referral_bonus': { id: 'referral_bonus', type: 'number' },
                'sign_on_bonus': { id: 'sign_on_bonus', type: 'number' },
                'completion_bonus': { id: 'completion_bonus', type: 'number' },
                'extension_bonus': { id: 'extension_bonus', type: 'number' },
                'other_bonus': { id: 'other_bonus', type: 'number' },
                'actual_hourly_rate': { id: 'actual_hourly_rate', type: 'number' },
                'overtime': { id: 'overtime', type: 'number' },
                'on_call': { id: 'on_call', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'on_call_rate': { id: 'on_call_rate', type: 'number' },
                'holiday': { id: 'holiday', type: 'date' },
                'orientation_rate': { id: 'orientation_rate', type: 'number' },
                'block_scheduling': { id: 'block_scheduling', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'float_requirement': { id: 'float_requirement', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'number_of_references': { id: 'number_of_references', type: 'number' },
                'urgency': { id: 'urgency', type: 'checkbox' },
                'facilitys_parent_system': { id: 'facilitys_parent_system', type: 'text' },
                'facility_name': { id: 'facility_name', type: 'text' },
                'pay_frequency': { id: 'pay_frequency', type: 'select' },
                'preferred_experience': { id: 'preferred_experience', type: 'number' },
                'contract_termination_policy': { id: 'contract_termination_policy', type: 'text' },
                'four_zero_one_k': { id: 'four_zero_one_k', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'health_insaurance': { id: 'health_insaurance', type: 'select', options: { 'No': '0', 'Yes': '1' } },
                'feels_like_per_hour': { id: 'feels_like_per_hour', type: 'number' },
                'call_back_rate': { id: 'call_back_rate', type: 'number' },
                'weekly_non_taxable_amount': { id: 'weekly_non_taxable_amount', type: 'number' },
                'start_date': { id: 'start_date', type: 'date' },
                'end_date': { id: 'end_date', type: 'date' },
                'preferred_experience': { id: 'preferred_experience', type: 'number' },
                'professional_state_licensure': { id: 'professional_state_licensure_pending', type: 'radio' },
                'description': { id: 'description', type: 'text' },
                'preferred_work_location': { id: 'preferred_work_location', type: 'text' },
                'as_soon_as': { id: 'as_soon_as', type: 'checkbox' },
                'goodwork_weekly_amount': { id: 'goodwork_weekly_amount', type: 'number' },
                'total_organization_amount': { id: 'total_organization_amount', type: 'number' },
                'total_goodwork_amount' : { id: 'total_goodwork_amount', type: 'number' },
                'total_contract_amount' : { id: 'total_contract_amount', type: 'number' }
            };

            for (const [key, field] of Object.entries(fields)) {
                console.log('key', key);
                const element = document.getElementById(field.id);
                if (!element || result[key] == null) continue;
            
                if (field.type === 'select') {
                    if (field.options) {
                        element.value = result[key] == '1' ? 'Yes' : 'No';
                    } else {
                        const option = document.createElement('option');
                        option.value = result[key];
                        option.text = result[key];
                        option.hidden = true;
                        element.add(option);
                        element.value = result[key];

                    }
                } else if (field.type === 'checkbox') {
                    field.id === 'urgencyDraft' ? element.checked = result[key] === 'Auto Offer' : element.checked = result[key] === '1';
                    console.log('checkbox test', result[key]);
                }
                else if (field.type === 'radio') {
                    console.log('radio', result[key]);
                    if (result[key] === 'Accept Pending') {
                        document.getElementById('professional_state_licensure_pendingDraft').checked = true;
                    } else {
                        document.getElementById('professional_state_licensure_activeDraft').checked = true;
                    }
                }
                 else {
                    console.log('key', key);
                    console.log('value', result[key]);
                    element.value = result[key];
                }
            }
            // list emr 
            var emr = result['Emr'];
            if(emr !== null){
            emr = emr.split(', ');
            
            emr.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['EMR']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        Emr[itemData.id] = item;
                    }
                });
            });
            }
            list_Emr();

            // list benefits

            var benefitsResult = result['benefits'];
            if(benefitsResult) {
            benefitsResult = benefitsResult.split(', ');
            
                benefitsResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['Benefits']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            benefits[itemData.id] = item;
                        }
                    });
                });
            }
            list_benefits();

            // list nurse classification
            var nurse_classificationResult = result['nurse_classification'];
            if(nurse_classificationResult !== null){
            nurse_classificationResult = nurse_classificationResult.split(', ');
            
                nurse_classificationResult.forEach(function(item) {
                    @php
                        $allKeywordsJSON = json_encode($allKeywords['NurseClassification']);
                    @endphp
                    let allspcldata = '{!! $allKeywordsJSON !!}';
                    var data = JSON.parse(allspcldata);
                    data.forEach(function(itemData) {
                        if (item == itemData.title) {
                            nurse_classification[itemData.id] = item;
                        }
                    });
                });
            }

            list_nurse_classification();

            // list professional licensure

            var professional_licensureResult = result['job_location'];
            if(professional_licensureResult !== null){
            
            professional_licensureResult = professional_licensureResult.split(', ');
            

            professional_licensureResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['StateCode']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        professional_licensure[itemData.id] = item;
                    }
                });
            });
            }
            list_professional_licensure();

            // list skills

            var skillsResult = result['skills'];
            if(skillsResult !== null){
            skillsResult = skillsResult.split(', ');
            
            skillsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Speciality']);
                @endphp
                let allspcldata = {!! json_encode($allKeywordsJSON) !!};
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        skills[itemData.id] = item;
                    }
                });
            });
            }
            list_skills();

            // list shift time of day

            var shifttimeofdayresult = result['preferred_shift_duration'];
            console.log("sift time of day : ",shifttimeofdayresult);
            // shifttimeofday is a string use trim to check if it is empty
            if (shifttimeofdayresult !== null) {
                console.log('triiiiiiimed');
            shifttimeofdayresult = shifttimeofdayresult.split(', ');
            
            shifttimeofdayresult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['PreferredShift']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        shifttimeofday[itemData.id] = item;
                    }
                });
            });
            }
            list_shifttimeofday();

            // list certifications

            var certificationsResult = result['certificate'];
            if (certificationsResult !== null) {
            certificationsResult = certificationsResult.split(', ');
            
            certificationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Certification']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        certificate[itemData.id] = item;
                    }
                });
            });
            }

            list_certifications();

            // list vaccinations

            var vaccinationsResult = result['vaccinations'];
            if (vaccinationsResult !== null) {
            vaccinationsResult = vaccinationsResult.split(', ');
            vaccinationsResult.forEach(function(item) {
                @php
                    $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
                @endphp
                let allspcldata = '{!! $allKeywordsJSON !!}';
                var data = JSON.parse(allspcldata);
                data.forEach(function(itemData) {
                    if (item == itemData.title) {
                        vaccinations[itemData.id] = item;
                    }
                });
            });
            }

            list_vaccinations();

        } else {
            
            $("#job-list-draft").html('<div class="text-center"><span>No Job</span></div>');
            

            // add d-none class to hide the form
            document.getElementById('details_info').classList.add('d-none');
            // remove d-none class to show the message
            document.getElementById('no-job-for-draft').classList.remove('d-none');

            

        }
    }

</script>
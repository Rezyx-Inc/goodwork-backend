<script>

    var certificate = {};
    var vaccinations = {};
    var skills = {};
    var shifttimeofday = {};
    var benefits = {};
    var professional_licensure = {};
    var nurse_classification = {};
    var Emr = {};
    var holiday = {};

    var certificateStr = '';
    var vaccinationsStr = '';
    var skillsStr = '';
    var shifttimeofdayStr = '';
    var benefitsStr = '';
    var professional_licensureStr = '';
    var nurse_classificationStr = '';
    var EmrStr = '';
    var holidayStr = '';

    var certificateStrEdit = '';
    var vaccinationsStrEdit = '';
    var skillsStrEdit = '';
    var shifttimeofdayStrEdit = '';
    var benefitsStrEdit = '';
    var professional_licensureStrEdit = '';
    var nurse_classificationStrEdit = '';
    var EmrStrEdit = '';
    var holidayStrEdit = '';

    const requiredToSubmit = @json($requiredFieldsToSubmit);

    const myForm = document.getElementById('create_job_form');
    const draftJobs = @json($draftJobs);
    const publishedJobs = @json($publishedJobs);
    const onholdJobs = @json($onholdJobs);

// cards toggle
    function toggleActiveClass(workerUserId, type) {

        var element = document.getElementById(workerUserId);
        element.classList.add('active');

        var allElements = document.getElementsByClassName(type);

        for (var i = 0; i < allElements.length; i++) {
            if (allElements[i].classList.contains('active')) {
                allElements[i].classList.remove('active');
            }
        }
        if (!element.classList.contains('active')) {
            element.classList.add('active');
        }

        $('.collapse').removeClass('show');
        $('.helper').text('');
    }
</script>
{{-- Infinite scroll --}}
<script>


    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Add an intersect Observer for infinite scroll

    var draftObserverContainer = document.querySelector('#draftObserverContainer');
    var publishedObserverContainer = document.querySelector('#publishedObserverContainer');
    var onholdObserverContainer = document.querySelector('#onholdObserverContainer');

    var counter = {{$counter}};

    var draftObserver = new window.IntersectionObserver(([entry]) => {

        // Only observe intersections
        if (entry.isIntersecting) {
            skip > 0 ? null : 0;

            loadMoreJobs(skip, 'draft');
            skip+=20;
            return
        }

    }, {
      root: null,
      threshold: 0.5,
    });

    var publishedObserver = new window.IntersectionObserver(([entry]) => {

        // Only observe intersections
        if (entry.isIntersecting) {
            skip > 0 ? null : 0;

            loadMoreJobs(skip, 'published');
            skip+=20;
            return
        }

    }, {
      root: null,
      threshold: 0.5,
    });

    var onholdObserver = new window.IntersectionObserver(([entry]) => {

        // Only observe intersections
        if (entry.isIntersecting) {
            skip > 0 ? null : 0;

            loadMoreJobs(skip, 'onhold');
            skip+=20;
            return
        }

    }, {
      root: null,
      threshold: 0.5,
    });

    // Observe
    draftObserver.observe(draftObserverContainer);
    publishedObserver.observe(publishedObserverContainer);
    onholdObserver.observe(onholdObserverContainer);

    function loadMoreJobs(skip, type){

        $.ajax({

            headers: {
                'X-CSRF-TOKEN': csrfToken
            },

            type: 'POST',
            url: "{{ url('organization/load-more-jobs') }}",
            data: {skip:skip},
            dataType: 'json',
            success: function(data) {

                pushCards(data.message, type);

            },
            error: function(error) {

                console.log("Load more jobs", error);
            }

        });
    }

    function pushCards(jobData, type){

        if(type == 'draft'){

            for (let job of jobData.draftJobs){

                draftJobs.push(job);
                var escapedJob = JSON.stringify(job).replaceAll("\"", "'");
                var jobCard = `<div class="col-12 ss-job-prfle-sec draft-cards" onclick="editDataJob(this),toggleActiveClass('`+job.id+`_drafts','draft-cards')"`+
                ` job_id="`+counter+`"`+
                ` id="`+job.id+`_drafts">`+
                `<h4>`+job.profession+` - `+job.preferred_specialty+`</h4>`+
                `<h6>`+job.job_name+`</h6>`+
                `<ul>
                    <li><a href="#"><img
                                src=" {{ URL::asset('frontend/img/location.png') }}">
                            ${ job.job_city }, ${ job.job_state }</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                            ${ job.preferred_assignment_duration }
                            wks</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                            ${ job.weekly_pay }/wk</a></li>
                </ul>`;

                $('#infiniteDraft').append(jobCard);
                counter++;
            }

        }else if(type == 'published'){

            for (let job of jobData.publishedJobs){

                publishedJobs.push(job);

                var escapedJob = JSON.stringify(job).replaceAll("\"", "'");
                var jobCard = `<div class="col-12 ss-job-prfle-sec published-cards" onclick="opportunitiesType('published','`+job.id+`','jobdetails'),toggleActiveClass('`+job.id+`_published','published-cards')"`+
                ` id="`+job.id+`_published">`+
                `<p><span> +`+job.applyCount+` Applied</span></p>`+
                `<h4>`+job.profession+` - `+job.preferred_specialty+`</h4>`+
                `<h6>`+job.job_name+`</h6>`+
                `<ul>
                    <li><a href="#"><img
                                src=" {{ URL::asset('frontend/img/location.png') }}">
                            ${ job.job_city }, ${ job.job_state }</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                            ${ job.preferred_assignment_duration }
                            wks</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                            ${ job.weekly_pay }/wk</a></li>
                </ul>`;

                $('#infinitePublished').append(jobCard);

            }

        }else if(type == 'onhold'){

            for (let job of jobData.onholdJobs){

                onholdJobs.push(job);

                var escapedJob = JSON.stringify(job).replaceAll("\"", "'");
                var jobCard = `<div class="col-12 ss-job-prfle-sec onhold-cards" onclick="opportunitiesType('onhold','`+job.id+`','jobdetails'),toggleActiveClass('`+job.id+`_onhold','onhold-cards')"`+
                ` id="`+job.id+`_onhold">`+
                `<h4>`+job.profession+` - `+job.preferred_specialty+`</h4>`+
                `<h6>`+job.job_name+`</h6>`+
                `<ul>
                    <li><a href="#"><img
                                src=" {{ URL::asset('frontend/img/location.png') }}">
                            ${ job.job_city }, ${ job.job_state }</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/calendar.png') }}">
                            ${ job.preferred_assignment_duration }
                            wks</a></li>
                    <li><a href="#"><img
                                src="{{ URL::asset('frontend/img/dollarcircle.png') }}">
                            ${ job.weekly_pay }/wk</a></li>
                </ul>`;

                $('#infiniteOnhold').append(jobCard);
            }

        }

    }
</script>
{{-- script managing certification --}}
<script>

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

                //console.log(id.val());

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

        for (const key in certificate) {
            //console.log(certificate);

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
</script>
{{-- end managing certifications --}}

{{-- script managing holidays --}}
<script>
    function addholidays(type) {
        var id;
        var idtitle;
        if (type == 'from_add') {
            id = $('#holiday');
            idtitle = "holiday";
        } else if (type == 'from_draft') {
            id = $('#holidayDraft');
            idtitle = "holidayDraft";
        } else {
            id = $('#holidayEdit');
            idtitle = "holidayEdit";
        }

        if (!id.val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the holiday please.',
                time: 3
            });
        } else {
            if (!holiday.hasOwnProperty(id.val())) {

                //console.log(id.val());

                // var select = document.getElementById(idtitle);
                // var selectedOption = select.options[select.selectedIndex];
                // var optionText = selectedOption.textContent;

                holiday[id.val()] = id.val();

                holidayStr = Object.values(holiday).join(', ');
                // console.log(certificate);
                id.val('');
                list_holidays();
            }
        }

    }



    function list_holidays() {
        var str = '';

        for (const key in holiday) {
            //console.log(holiday);

            let holidayname = "";
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (holiday.hasOwnProperty(key)) {
                // var data = JSON.parse(allspcldata);

                // data.forEach(function(item) {
                //     if (key == item.id) {
                //         holidayname = item.title;
                //     }
                // });
                const value = holiday[key];
                str += '<ul>';
                str += '<li class="w-50">' + holiday[value] + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-holiday" data-key="' +
                    key + '" onclick="remove_holiday(this, ' + key +
                    ')"><img src="{{ URL::asset('frontend/img/delete-img.png') }}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.holiday-content').html(str);
    }



    function remove_holiday(obj, key) {
        //console.log(holiday[$(obj).data('key')]);
        if (holiday.hasOwnProperty($(obj).data('key'))) {
            delete holiday[$(obj).data('key')];

            // to dlete the removed holiday from the hidden input
            holidayStr = Object.values(holiday).join(', ');

            list_holidays(); // Refresh the list to reflect the deletion
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Holiday removed successfully.',
                time: 3
            });
        } else {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-times"></i> Holiday not found.',
                time: 3
            });
        }
    }
</script>
{{-- end script managing holidays --}}

{{-- script managing vaccinations  --}}
<script>
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
                //console.log(id.val());

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
        //console.log(vaccinations);

        for (const key in vaccinations) {
            //console.log(vaccinations);

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
</script>

{{-- script managing skills --}}

<script>
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
                //console.log(id.val());

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
        //console.log('fomr skiiiils', skills);

        for (const key in skills) {
            //console.log('skills', skills);


            let skillsname = "";
            let allspcldata = @json($allKeywords['Speciality']);

            //console.log('allspcldata', allspcldata);

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
</script>

{{-- script managing shifttimeofday --}}
<script>
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
                //console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                shifttimeofday[id.val()] = optionText;
                shifttimeofdayStr = Object.values(shifttimeofday).join(', ');
                //console.log('shifttimeofdayStr', shifttimeofdayStr);
                id.val('');
                list_shifttimeofday();
            }
        }
    }

    function list_shifttimeofday() {
        var str = '';
        //console.log(shifttimeofday);

        for (const key in shifttimeofday) {
            //console.log(shifttimeofday);

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
</script>

{{-- script managing benefits --}}
<script>
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
                //console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                benefits[id.val()] = optionText;
                benefitsStr = Object.values(benefits).join(', ');
                //console.log('benefitsStr', benefitsStr);
                id.val('');
                list_benefits();
            }
        }
    }

    function list_benefits() {
        var str = '';
        //console.log(benefits);

        for (const key in benefits) {
            //console.log(benefits);

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
</script>

{{-- script managing professional_licensure  --}}
<script>
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
                //console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                professional_licensure[id.val()] = optionText;
                professional_licensureStr = Object.values(professional_licensure).join(', ');
                //console.log('professional_licensureStr', professional_licensureStr);
                id.val('');
                list_professional_licensure();
            }
        }
    }

    function list_professional_licensure() {
        var str = '';
        //console.log(professional_licensure);

        for (const key in professional_licensure) {
            //console.log(professional_licensure);

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
</script>

{{-- script managing Emr  --}}
<script>
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
                //console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                Emr[id.val()] = optionText;
                EmrStr = Object.values(Emr).join(', ');
                //console.log('EmrStr', EmrStr);
                id.val('');
                list_Emr();
            }
        }
    }

    function list_Emr() {
        var str = '';
        //console.log(Emr);

        for (const key in Emr) {
            //console.log(Emr);

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
</script>

{{-- script managing nurse_classification  --}}
<script>
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
                //console.log(id.val());

                var select = document.getElementById(idtitle);
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                nurse_classification[id.val()] = optionText;
                nurse_classificationStr = Object.values(nurse_classification).join(', ');
                //console.log('nurse_classificationStr', nurse_classificationStr);
                id.val('');
                list_nurse_classification();
            }
        }
    }

    function list_nurse_classification() {
        var str = '';
        //console.log(nurse_classification);

        for (const key in nurse_classification) {
            //console.log(nurse_classification);

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
</script>

<script>
    
    // Fill the form with dump data only for dev purposes 
    function fillData() {
        const fields = {
            'job_id': 1, // type number
            'job_name': 'job name', // type input text
            'job_type': 'Clinical', // type select
            'preferred_specialty': 'Adult Medicine', // type select
            'perferred_profession': 'Clerical', // type select
            'job_state': 'Arizona', // type select
            'weekly_pay': 250, // type number
            'terms': 'Perm', // type select
            'preferred_assignment_duration': 3, // type number
            'facility_shift_cancelation_policy': 'shift cancellation policy', // type text
            'traveler_distance_from_facility': 50, // type number
            'clinical_setting': 'Clinic', // type select
            'Patient_ratio': 20, // type number 
            'Unit': 'Unit', // type text
            'scrub_color': 'Blue', // type text
            'rto': 'allowed', // type select 
            'guaranteed_hours': 7, // type number 
            'hours_per_week': 30, // type number
            'hours_shift': 5, // type number 
            'weeks_shift': 3, // type number 
            'referral_bonus': 30, // type number
            'sign_on_bonus': 10, // type number
            'completion_bonus': 3, // type number
            'extension_bonus': 3, // type number
            'other_bonus': 3, // type number
            'actual_hourly_rate': 3, // type number
            'overtime': 3, // type number 
            'on_call': 'Yes', // type no/yes select
            'on_call_rate': 10, // type number
            'holiday': '2025-04-27', // type date
            'orientation_rate': 19, // type number
            'block_scheduling': 'No', // type no/yes select
            'float_requirement': 'No', // type no/yes select
            'number_of_references': 1, // type number

            'urgency': 'Auto Offer', // type checkbox
            'facilitys_parent_system': 'Parent system', // type text
            'facility_name': 'Facility Name', // type text
            'pay_frequency': 'Daily', // type select
            'preferred_experience': 'preferred experience', // type number
            'contract_termination_policy': 'Contract policy', // type text


            'feels_like_per_hour': 5, // type number 
            'call_back_rate': 16, // type number
            'weekly_non_taxable_amount': 100, // type number
            'start_date': '2025-04-27', // type date
            'end_date': '2025-04-30', // type date
            'preferred_experience': 10, // type number
            'weekly_taxable_amount': 100, // type number
            'goodwork_weekly_amount': 110, // type number
            'total_organization_amount': 120,
            'total_goodwork_amount': 130,
            'total_contract_amount': 140,
            'preferred_work_location': 'Work location',
            'description': 'description',


        };

        for (const [id, value] of Object.entries(fields)) {

            document.getElementById(id).value = value;
        }
    }

    document.addEventListener('DOMContentLoaded', async function() {

        // for job creation in dev mode
         fillData();

        // FOR JOB CREATION 
        // get professions according to job type :

        const jobTypeSelect = document.getElementById('job_type');
        const professionSelect = document.getElementById('perferred_profession');
        jobTypeSelect.addEventListener('change', function() {

            const selectedJobType = this.value;
            let professions = [];

            if (selectedJobType === 'Clinical') {

                professions = @json($allKeywords['ClinicalProfession']);

            } else if (selectedJobType === 'Non-Clinical') {

                professions = @json($allKeywords['Non-ClinicalProfession']);

            } else {

                professions = @json($allKeywords['Profession']);

            }

            professionSelect.innerHTML = '<option value="">Profession</option>';

            professions.forEach(function(profession) {

                const option = document.createElement('option');
                option.value = profession.title;
                option.textContent = profession.title;
                professionSelect.appendChild(option);

            });
        });

        // get cities according to state :

        const jobState = document.getElementById('job_state');
        const jobCity = document.getElementById('job_city');
        let citiesData = [];
        const selectedJobState = jobState.value;
        const selectedState = $(jobState).find(':selected').attr('id');

        jobState.addEventListener('change', async function() {

            const selectedJobState = this.value;
            const selectedState = $(this).find(':selected').attr('id');

            await $.get(`/api/cities/${selectedState}`, function(cities) {
                citiesData = cities;
            });

            jobCity.innerHTML = '<option value="">Cities</option>';

            citiesData.forEach(function(City) {

                const option = document.createElement('option');
                option.value = City.name;
                option.textContent = City.name;
                jobCity.appendChild(option);

            });

        })

        // FOR JOB EDITING

        // get professions according to job type :

        const jobTypeSelectEdit = document.getElementById('job_typeEdit');
        const professionSelectEdit = document.getElementById('perferred_professionEdit');

        jobTypeSelectEdit.addEventListener('change', function() {

            const selectedJobType = this.value;
            let professions = [];

            if (selectedJobType === 'Clinical') {

                professions = @json($allKeywords['ClinicalProfession']);

            } else if (selectedJobType === 'Non-Clinical') {

                professions = @json($allKeywords['Non-ClinicalProfession']);

            } else {

                professions = @json($allKeywords['Profession']);
            }

            professionSelectEdit.innerHTML = '<option value="">Profession</option>';

            professions.forEach(function(profession) {

                const option = document.createElement('option');
                option.value = profession.title;
                option.textContent = profession.title;
                professionSelectEdit.appendChild(option);

            });
        });

        // get cities according to state :

        const jobStateEdit = document.getElementById('job_stateEdit');

        const jobCityEdit = document.getElementById('job_cityEdit');

        let citiesDataEdit = [];

        const selectedJobStateEdit = jobStateEdit.value;

        const selectedStateEdit = $(jobStateEdit).find(':selected').attr('id');

        jobStateEdit.addEventListener('change', async function() {

            const selectedJobState = this.value;

            const selectedState = $(this).find(':selected').attr('id');

            await $.get(`/api/cities/${selectedState}`, function(cities) {
                citiesDataEdit = cities;
            });

            jobCityEdit.innerHTML = '<option value="">Cities</option>';

            citiesDataEdit.forEach(function(City) {

                const option = document.createElement('option');
                option.value = City.name;
                option.textContent = City.name;
                jobCityEdit.appendChild(option);

            });

        })

        // FOR JOB DRAFT

        // get professions according to job type :

        const jobTypeSelectDraft = document.getElementById('job_typeDraft');

        const professionSelectDraft = document.getElementById('perferred_professionDraft');

        jobTypeSelectDraft.addEventListener('change', function() {

            const selectedJobType = this.value;

            let professions = [];

            if (selectedJobType === 'Clinical') {

                professions = @json($allKeywords['ClinicalProfession']);

            } else if (selectedJobType === 'Non-Clinical') {

                professions = @json($allKeywords['Non-ClinicalProfession']);

            } else {

                professions = @json($allKeywords['Profession']);

            }

            professionSelectDraft.innerHTML = '<option value="">Profession</option>';

            professions.forEach(function(profession) {

                const option = document.createElement('option');
                option.value = profession.title;
                option.textContent = profession.title;
                professionSelectDraft.appendChild(option);

            });
        });

        // get cities according to state :

        const jobStateDraft = document.getElementById('job_stateDraft');

        const jobCityDraft = document.getElementById('job_cityDraft');

        let citiesDataDraft = [];

        const selectedJobStateDraft = jobStateDraft.value;

        const selectedStateDraft = $(jobStateDraft).find(':selected').attr('id');

        jobStateDraft.addEventListener('change', async function() {

            const selectedJobState = this.value;

            const selectedState = $(this).find(':selected').attr('id');

            await $.get(`/api/cities/${selectedState}`, function(cities) {
                citiesDataDraft = cities;
            });

            jobCityDraft.innerHTML = '<option value="">Cities</option>';

            citiesDataDraft.forEach(function(City) {

                const option = document.createElement('option');
                option.value = City.name;
                option.textContent = City.name;
                jobCityDraft.appendChild(option);

            });

        })

    });

    if (publishedJobs == 0) {
        $("#application-details-apply").html('<div class="text-center"><span>Data Not found</span></div>');
        $("#job-list-published").html('<div class="text-center"><span>No Job</span></div>');
    }

    if (onholdJobs == 0) {
        $("#application-details-apply-onhold").html('<div class="text-center"><span>Data Not found</span></div>');
        $("#job-list-onhold").html('<div class="text-center"><span>No Job</span></div>');
    }

// the first draft job
    function getFirstDraftJob() {
        if (draftJobs.length !== 0) {

            var result = draftJobs[0];

            const fields = {
                'id': {
                    id: 'idDraft',
                    type: 'number'
                },
                'job_id': {
                    id: 'job_idDraft',
                    type: 'number'
                },
                'job_name': {
                    id: 'job_nameDraft',
                    type: 'text'
                },
                'job_type': {
                    id: 'job_typeDraft',
                    type: 'select'
                },
                'preferred_specialty': {
                    id: 'preferred_specialtyDraft',
                    type: 'select'
                },
                'profession': {
                    id: 'perferred_professionDraft',
                    type: 'select'
                },
                'job_state': {
                    id: 'job_stateDraft',
                    type: 'select'
                },
                'job_city': {
                    id: 'job_cityDraft',
                    type: 'select'
                },
                'is_resume': {
                    id: 'job_is_resumeDraft',
                    type: 'checkbox'
                },
                'weekly_pay': {
                    id: 'weekly_payDraft',
                    type: 'number'
                },
                'terms': {
                    id: 'termsDraft',
                    type: 'select'
                },
                'preferred_assignment_duration': {
                    id: 'preferred_assignment_durationDraft',
                    type: 'number'
                },
                'facility_shift_cancelation_policy': {
                    id: 'facility_shift_cancelation_policyDraft',
                    type: 'text'
                },
                'traveler_distance_from_facility': {
                    id: 'traveler_distance_from_facilityDraft',
                    type: 'number'
                },
                'clinical_setting': {
                    id: 'clinical_settingDraft',
                    type: 'select'
                },
                'Patient_ratio': {
                    id: 'Patient_ratioDraft',
                    type: 'number'
                },
                'Unit': {
                    id: 'UnitDraft',
                    type: 'text'
                },
                'scrub_color': {
                    id: 'scrub_colorDraft',
                    type: 'text'
                },
                'rto': {
                    id: 'rtoDraft',
                    type: 'select'
                },
                'guaranteed_hours': {
                    id: 'guaranteed_hoursDraft',
                    type: 'number'
                },
                'hours_per_week': {
                    id: 'hours_per_weekDraft',
                    type: 'number'
                },
                'hours_shift': {
                    id: 'hours_shiftDraft',
                    type: 'number'
                },
                'weeks_shift': {
                    id: 'weeks_shiftDraft',
                    type: 'number'
                },
                'referral_bonus': {
                    id: 'referral_bonusDraft',
                    type: 'number'
                },
                'sign_on_bonus': {
                    id: 'sign_on_bonusDraft',
                    type: 'number'
                },
                'completion_bonus': {
                    id: 'completion_bonusDraft',
                    type: 'number'
                },
                'extension_bonus': {
                    id: 'extension_bonusDraft',
                    type: 'number'
                },
                'other_bonus': {
                    id: 'other_bonusDraft',
                    type: 'number'
                },
                'actual_hourly_rate': {
                    id: 'actual_hourly_rateDraft',
                    type: 'number'
                },
                'overtime': {
                    id: 'overtimeDraft',
                    type: 'number'
                },
                'on_call': {
                    id: 'on_callDraft',
                    type: 'select',
                    options: {
                        'No': '0',
                        'Yes': '1'
                    }
                },
                'on_call_rate': {
                    id: 'on_call_rateDraft',
                    type: 'number'
                },
                'holiday': {
                    id: 'holidayDraft',
                    type: 'date'
                },
                'orientation_rate': {
                    id: 'orientation_rateDraft',
                    type: 'number'
                },
                'block_scheduling': {
                    id: 'block_schedulingDraft',
                    type: 'select',
                    options: {
                        'No': '0',
                        'Yes': '1'
                    }
                },
                'float_requirement': {
                    id: 'float_requirementDraft',
                    type: 'select',
                    options: {
                        'No': '0',
                        'Yes': '1'
                    }
                },
                'number_of_references': {
                    id: 'number_of_referencesDraft',
                    type: 'number'
                },
                'eligible_work_in_us': {
                    id: 'eligible_work_in_usDraft',
                    type: 'select'
                },
                'urgency': {
                    id: 'urgencyDraft',
                    type: 'checkbox'
                },
                'facilitys_parent_system': {
                    id: 'facilitys_parent_systemDraft',
                    type: 'text'
                },
                'facility_name': {
                    id: 'facility_nameDraft',
                    type: 'text'
                },
                'pay_frequency': {
                    id: 'pay_frequencyDraft',
                    type: 'select'
                },
                'preferred_experience': {
                    id: 'preferred_experienceDraft',
                    type: 'number'
                },
                'contract_termination_policy': {
                    id: 'contract_termination_policyDraft',
                    type: 'text'
                },
                'four_zero_one_k': {
                    id: 'four_zero_one_kDraft',
                    type: 'select',
                    options: {
                        'No': '0',
                        'Yes': '1'
                    }
                },
                'health_insaurance': {
                    id: 'health_insauranceDraft',
                    type: 'select',
                    options: {
                        'No': '0',
                        'Yes': '1'
                    }
                },
                'feels_like_per_hour': {
                    id: 'feels_like_per_hourDraft',
                    type: 'number'
                },
                'call_back_rate': {
                    id: 'call_back_rateDraft',
                    type: 'number'
                },
                'weekly_non_taxable_amount': {
                    id: 'weekly_non_taxable_amountDraft',
                    type: 'number'
                },
                'start_date': {
                    id: 'start_dateDraft',
                    type: 'date'
                },
                'preferred_experience': {
                    id: 'preferred_experienceDraft',
                    type: 'number'
                },
                'professional_state_licensure': {
                    id: 'professional_state_licensure_pendingDraft',
                    type: 'radio'
                },
                'description': {
                    id: 'descriptionDraft',
                    type: 'text'
                },
                'preferred_work_location': {
                    id: 'preferred_work_locationDraft',
                    type: 'text'
                },
                'as_soon_as': {
                    id: 'as_soon_asDraft',
                    type: 'checkbox'
                },
            };

            for (const [key, field] of Object.entries(fields)) {

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

                    if (field.id === 'urgencyDraft') {

                        element.checked = result[key] === 'Auto Offer';

                    } else {

                        element.checked = Boolean(result[key]);
                    }

                } else if (field.type === 'radio') {

                    if (result[key] === 'Accept Pending') {

                        document.getElementById('professional_state_licensure_pendingDraft').checked = true;

                    } else {

                        document.getElementById('professional_state_licensure_activeDraft').checked = true;

                    }

                } else {

                    element.value = result[key];
                }
            }

            // list emr 
            var emr = result['Emr'];
            if (emr !== null) {
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

            if (benefitsResult) {

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

            if (nurse_classificationResult !== null) {

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

            if (professional_licensureResult !== null) {

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

            if (skillsResult !== null) {

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

            // shifttimeofday is a string use trim to check if it is empty
            if (shifttimeofdayresult !== null) {

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


// displaying the form for creating a job 
    function request_job_form_appear() {

        certificate = {};
        vaccinations = {};
        skills = {};
        shifttimeofday = {};
        benefits = {};
        professional_licensure = {};
        nurse_classification = {};
        Emr = {};

        document.getElementById('no-job-posted').classList.add('d-none');
        document.getElementById('published-job-details').classList.add('d-none');
        document.getElementById('create_job_request_form').classList.remove('d-none');

    }

// redirect to the cooresponding type published jobs | onhold jobs | draft jobs
    function opportunitiesType(type, id = "", formtype) {
        skip = 20;
        certificate = {};
        vaccinations = {};
        skills = {};
        shifttimeofday = {};
        benefits = {};
        professional_licensure = {};
        nurse_classification = {};
        Emr = {};

        var draftsElement = document.getElementById('drafts');
        var publishedElement = document.getElementById('published');
        var onholdElement = document.getElementById('onhold');

        if (type == "drafts") {

            getFirstDraftJob();

            document.getElementById("onholdCards").classList.add('d-none');
            document.getElementById("draftCards").classList.remove('d-none');
            document.getElementById("publishedCards").classList.add('d-none');
            document.getElementById("details_edit_job").classList.add("d-none");

            document.getElementById("no-job-posted").classList.add("d-none");
            document.getElementById("details_draft").classList.remove("d-none");
            document.getElementById("details_published").classList.add("d-none");
            document.getElementById("details_onhold").classList.add("d-none");
            document.getElementById('published-job-details').classList.remove('d-none');
            document.getElementById("create_job_request_form").classList.add("d-none");

        } else if (type == "published") {

            document.getElementById("onholdCards").classList.add('d-none');
            document.getElementById("draftCards").classList.add('d-none');
            document.getElementById("publishedCards").classList.remove('d-none');
            document.getElementById("details_edit_job").classList.add("d-none");

            document.getElementById("no-job-posted").classList.add("d-none");
            document.getElementById("details_published").classList.remove("d-none");
            document.getElementById("details_onhold").classList.add("d-none");
            document.getElementById("details_draft").classList.add("d-none");

            document.getElementById('published-job-details').classList.remove('d-none');
            document.getElementById("create_job_request_form").classList.add("d-none");

        } else if (type == "onhold") {

            document.getElementById("draftCards").classList.add('d-none');
            document.getElementById("publishedCards").classList.add('d-none');
            document.getElementById("onholdCards").classList.remove('d-none');
            document.getElementById("details_edit_job").classList.add("d-none");

            document.getElementById("no-job-posted").classList.add("d-none");
            document.getElementById("details_onhold").classList.remove("d-none");
            document.getElementById("details_published").classList.add("d-none");
            document.getElementById("details_draft").classList.add("d-none");

            document.getElementById('published-job-details').classList.remove('d-none');
            document.getElementById("create_job_request_form").classList.add("d-none");
        }

        if (draftsElement.classList.contains("active")) {

            draftsElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }

        if (publishedElement.classList.contains("active")) {

            publishedElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }

        if (onholdElement.classList.contains("active")) {

            onholdElement.classList.remove("active");
            document.getElementById("create_job_request_form").classList.add("d-none");

        }

        document.getElementById(type).classList.add("active")

        // Change scroll behavior
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

        let activestatus = 0;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (csrfToken) {

            $.ajax({

                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('organization/get-job-listing') }}",
                beforeSend: function(xhr) {

                    xhr.withCredentials = true;

                },
                data: {

                    "_token": "{{ csrf_token() }}",
                    'type': type,
                    'id': id,
                    'formtype': formtype,

                },
                type: 'POST',
                dataType: 'json',
                success: function(result) {

                    window.allspecialty = result.allspecialty;
                    window.allvaccinations = result.allvaccinations;
                    window.allcertificate = result.allcertificate;
                    window.allbenefits = result.allcertificate;
                    window.allprofessional_licensure = result.allcertificate;
                    window.allEmr = result.allcertificate;
                    window.allnurse_classification = result.allcertificate;

                    list_vaccinations();
                    list_certifications();
                    list_benefits();
                    list_professional_licensure();
                    list_Emr();
                    list_nurse_classification();

                    if (result.joblisting != "") {

                        if (type == "published") {

                            document.getElementById("published-job-details").classList.remove("d-none");
                            document.getElementById("no-job-posted").classList.add("d-none");
                            $("#application-details-apply").html(result.jobdetails);
                            $("#job-list-published").html(result.joblisting);

                        } else if (type == "onhold") {

                            document.getElementById("published-job-details").classList.remove("d-none");
                            document.getElementById("no-job-posted").classList.add("d-none");
                            $("#application-details-apply-onhold").html(result.jobdetails);
                            $("#job-list-onhold").html(result.joblisting);

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

// display the main view of the opp manager, hiding the drafts | published | onhold sections
    $(document).ready(function() {
        document.getElementById("details_draft").classList.add("d-none");
        document.getElementById('published-job-details').classList.add('d-none');
        document.getElementById('details_edit_job').classList.add('d-none');

    });

// change job status from hide to publish and the opposite  
    function changeStatus(type, id = '0') {

        if (type == "draft") {

            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Draft Updated Successfully',
                time: 5
            });

        } else {

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (csrfToken) {

                event.preventDefault();
                let check_type = type;

                if (id == '0') {

                    id = document.getElementById('job_id').value;

                }

                let formData = {

                    'job_id': id

                }

                $.ajax({

                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },

                    type: 'POST',
                    url: "{{ url('organization/organization-create-opportunity') }}/" + check_type,

                    data: formData,
                    dataType: 'json',
                    success: function(data) {

                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 3
                        });

                        window.location.reload();

                    },
                    error: function(error) {

                        console.log("Change Status Error", error);
                    }

                });

            } else {

                console.error('CSRF token not found.');

            }

        }

    }

</script>

<script>
// for redirecting the organization the corresponding room chat 
    function askWorker(e, type, workerid, organization_id, name) {
        let url = "{{ url('organization/organization-messages') }}";
        window.location = url + '?worker_id=' + workerid + '&organization_id=' + organization_id + '&name=' + name;
    }
</script>

<script type="text/javascript">

// for populating the corresponding job draft data in its draft form

    function editDataJob(element) {
        $(`.helper`).text('');
        certificate = {};
        vaccinations = {};
        skills = {};
        shifttimeofday = {};
        benefits = {};
        professional_licensure = {};
        nurse_classification = {};
        Emr = {};

        const jobId = element.getAttribute('job_id');
        element.classList.add("active");

        var result = draftJobs[jobId];

        const fields = {
            'id': {
                id: 'idDraft',
                type: 'number'
            },
            'job_id': {
                id: 'job_idDraft',
                type: 'number'
            },
            'job_name': {
                id: 'job_nameDraft',
                type: 'text'
            },
            'job_type': {
                id: 'job_typeDraft',
                type: 'select'
            },
            'preferred_specialty': {
                id: 'preferred_specialtyDraft',
                type: 'select'
            },
            'profession': {
                id: 'perferred_professionDraft',
                type: 'select'
            },
            'job_state': {
                id: 'job_stateDraft',
                type: 'select'
            },
            'job_city': {
                id: 'job_cityDraft',
                type: 'select'
            },
            'is_resume': {
                id: 'job_isResumeDraft',
                type: 'checkbox'
            },

            'weekly_pay': {
                id: 'weekly_payDraft',
                type: 'number'
            },
            'terms': {
                id: 'termsDraft',
                type: 'select'
            },
            'preferred_assignment_duration': {
                id: 'preferred_assignment_durationDraft',
                type: 'number'
            },
            'facility_shift_cancelation_policy': {
                id: 'facility_shift_cancelation_policyDraft',
                type: 'text'
            },
            'traveler_distance_from_facility': {
                id: 'traveler_distance_from_facilityDraft',
                type: 'number'
            },
            'clinical_setting': {
                id: 'clinical_settingDraft',
                type: 'select'
            },
            'Patient_ratio': {
                id: 'Patient_ratioDraft',
                type: 'number'
            },
            'Unit': {
                id: 'UnitDraft',
                type: 'text'
            },
            'scrub_color': {
                id: 'scrub_colorDraft',
                type: 'text'
            },
            'rto': {
                id: 'rtoDraft',
                type: 'select'
            },
            'guaranteed_hours': {
                id: 'guaranteed_hoursDraft',
                type: 'number'
            },
            'hours_per_week': {
                id: 'hours_per_weekDraft',
                type: 'number'
            },
            'hours_shift': {
                id: 'hours_shiftDraft',
                type: 'number'
            },
            'weeks_shift': {
                id: 'weeks_shiftDraft',
                type: 'number'
            },
            'referral_bonus': {
                id: 'referral_bonusDraft',
                type: 'number'
            },
            'sign_on_bonus': {
                id: 'sign_on_bonusDraft',
                type: 'number'
            },
            'completion_bonus': {
                id: 'completion_bonusDraft',
                type: 'number'
            },
            'extension_bonus': {
                id: 'extension_bonusDraft',
                type: 'number'
            },
            'other_bonus': {
                id: 'other_bonusDraft',
                type: 'number'
            },
            'actual_hourly_rate': {
                id: 'actual_hourly_rateDraft',
                type: 'number'
            },
            'overtime': {
                id: 'overtimeDraft',
                type: 'number'
            },
            'on_call': {
                id: 'on_callDraft',
                type: 'select',
                options: {
                    'No': '0',
                    'Yes': '1'
                }
            },
            'on_call_rate': {
                id: 'on_call_rateDraft',
                type: 'number'
            },
            'holiday': {
                id: 'holidayDraft',
                type: 'date'
            },
            'orientation_rate': {
                id: 'orientation_rateDraft',
                type: 'number'
            },
            'block_scheduling': {
                id: 'block_schedulingDraft',
                type: 'select',
                options: {
                    'No': '0',
                    'Yes': '1'
                }
            },
            'float_requirement': {
                id: 'float_requirementDraft',
                type: 'select',
                options: {
                    'No': '0',
                    'Yes': '1'
                }
            },
            'number_of_references': {
                id: 'number_of_referencesDraft',
                type: 'number'
            },
            'eligible_work_in_us': {
                id: 'eligible_work_in_usDraft',
                type: 'select'
            },
            'urgency': {
                id: 'urgencyDraft',
                type: 'checkbox'
            },
            'facilitys_parent_system': {
                id: 'facilitys_parent_systemDraft',
                type: 'text'
            },
            'facility_name': {
                id: 'facility_nameDraft',
                type: 'text'
            },
            'pay_frequency': {
                id: 'pay_frequencyDraft',
                type: 'select'
            },
            'preferred_experience': {
                id: 'preferred_experienceDraft',
                type: 'number'
            },
            'contract_termination_policy': {
                id: 'contract_termination_policyDraft',
                type: 'text'
            },
            'four_zero_one_k': {
                id: 'four_zero_one_kDraft',
                type: 'select',
                options: {
                    'No': '0',
                    'Yes': '1'
                }
            },
            'health_insaurance': {
                id: 'health_insauranceDraft',
                type: 'select',
                options: {
                    'No': '0',
                    'Yes': '1'
                }
            },
            'feels_like_per_hour': {
                id: 'feels_like_per_hourDraft',
                type: 'number'
            },
            'call_back_rate': {
                id: 'call_back_rateDraft',
                type: 'number'
            },
            'weekly_non_taxable_amount': {
                id: 'weekly_non_taxable_amountDraft',
                type: 'number'
            },
            'weekly_taxable_amount': {
                id: 'weekly_taxable_amountDraft',
                type: 'number'
            },
            'start_date': {
                id: 'start_dateDraft',
                type: 'date'
            },
            'preferred_experience': {
                id: 'preferred_experienceDraft',
                type: 'number'
            },
            'professional_state_licensure': {
                id: 'professional_state_licensure_pendingDraft',
                type: 'radio'
            },
            'description': {
                id: 'descriptionDraft',
                type: 'text'
            },
            'preferred_work_location': {
                id: 'preferred_work_locationDraft',
                type: 'text'
            },
            'as_soon_as': {
                id: 'as_soon_asDraft',
                type: 'checkbox'
            },
            'goodwork_weekly_amount': {
                id: 'goodwork_weekly_amountDraft',
                type: 'number'
            },
            'total_organization_amount': {
                id: 'total_organization_amountDraft',
                type: 'number'
            },
            'total_goodwork_amount': {
                id: 'total_goodwork_amountDraft',
                type: 'number'
            },
            'total_contract_amount': {
                id: 'total_contract_amountDraft',
                type: 'number'
            }
        };

        for (const [key, field] of Object.entries(fields)) {
            const element = document.getElementById(field.id);
            if (!element) continue;
            if (result[key] === null) {
                if (field.type === 'select') {
                    element.value = '';
                } else if (field.type === 'checkbox') {
                    element.checked = false;
                } else {
                    element.value = '';
                }

                continue;
            }

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



                if (field.id === 'urgencyDraft') {
                    element.checked = result[key] === 'Auto Offer';

                } else if (field.id === "job_isResumeDraft") {
                    element.checked = Boolean(result[key]);

                } else {

                    element.checked = false;
                }
            } else if (field.type === 'radio') {

                if (result[key] === 'Accept Pending') {
                    document.getElementById('professional_state_licensure_pendingDraft').checked = true;
                } else {
                    document.getElementById('professional_state_licensure_activeDraft').checked = true;
                }
            } else {
                element.value = result[key];
            }
        }
        // list emr 
        var emr = result['Emr'];
        if (emr !== null) {
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
        if (benefitsResult) {
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
        if (nurse_classificationResult !== null) {
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
        if (professional_licensureResult !== null) {

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
        if (skillsResult !== null) {
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
        //console.log("sift time of day : ", shifttimeofdayresult);
        // shifttimeofday is a string use trim to check if it is empty
        if (shifttimeofdayresult !== null) {

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

    }

// for populating the corresponding job published data in its edit form

    function job_details_to_edit(id) {
        document.getElementById('details_published').classList.add('d-none');
        document.getElementById('details_edit_job').classList.remove('d-none');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: "{{ url('organization/get-job-to-edit') }}",
                data: {
                    'id': id
                },
                type: 'POST',
                dataType: 'json',

                success: function(result) {

                    const fields = {

                        'id': {
                            id: 'idEdit',
                            type: 'number'
                        },
                        'job_id': {
                            id: 'job_idEdit',
                            type: 'number'
                        },
                        'job_name': {
                            id: 'job_nameEdit',
                            type: 'text'
                        },
                        'job_type': {
                            id: 'job_typeEdit',
                            type: 'select'
                        },
                        'preferred_specialty': {
                            id: 'preferred_specialtyEdit',
                            type: 'select'
                        },
                        'profession': {
                            id: 'perferred_professionEdit',
                            type: 'select'
                        },
                        'job_state': {
                            id: 'job_stateEdit',
                            type: 'select'
                        },
                        'job_city': {
                            id: 'job_cityEdit',
                            type: 'select'
                        },
                        'is_resume': {
                            id: 'job_isResumeEdit',
                            type: 'checkbox'
                        },
                        'weekly_pay': {
                            id: 'weekly_payEdit',
                            type: 'number'
                        },
                        'terms': {
                            id: 'termsEdit',
                            type: 'select'
                        },
                        'preferred_assignment_duration': {
                            id: 'preferred_assignment_durationEdit',
                            type: 'number'
                        },
                        'facility_shift_cancelation_policy': {
                            id: 'facility_shift_cancelation_policyEdit',
                            type: 'text'
                        },
                        'traveler_distance_from_facility': {
                            id: 'traveler_distance_from_facilityEdit',
                            type: 'number'
                        },
                        'clinical_setting': {
                            id: 'clinical_settingEdit',
                            type: 'select'
                        },
                        'Patient_ratio': {
                            id: 'Patient_ratioEdit',
                            type: 'number'
                        },
                        'Unit': {
                            id: 'UnitEdit',
                            type: 'text'
                        },
                        'scrub_color': {
                            id: 'scrub_colorEdit',
                            type: 'text'
                        },
                        'rto': {
                            id: 'rtoEdit',
                            type: 'select'
                        },
                        'guaranteed_hours': {
                            id: 'guaranteed_hoursEdit',
                            type: 'number'
                        },
                        'hours_per_week': {
                            id: 'hours_per_weekEdit',
                            type: 'number'
                        },
                        'hours_shift': {
                            id: 'hours_shiftEdit',
                            type: 'number'
                        },
                        'weeks_shift': {
                            id: 'weeks_shiftEdit',
                            type: 'number'
                        },
                        'referral_bonus': {
                            id: 'referral_bonusEdit',
                            type: 'number'
                        },
                        'sign_on_bonus': {
                            id: 'sign_on_bonusEdit',
                            type: 'number'
                        },
                        'completion_bonus': {
                            id: 'completion_bonusEdit',
                            type: 'number'
                        },
                        'extension_bonus': {
                            id: 'extension_bonusEdit',
                            type: 'number'
                        },
                        'other_bonus': {
                            id: 'other_bonusEdit',
                            type: 'number'
                        },
                        'actual_hourly_rate': {
                            id: 'actual_hourly_rateEdit',
                            type: 'number'
                        },
                        'overtime': {
                            id: 'overtimeEdit',
                            type: 'number'
                        },
                        'on_call': {
                            id: 'on_callEdit',
                            type: 'select',
                            options: {
                                'No': '0',
                                'Yes': '1'
                            }
                        },
                        'on_call_rate': {
                            id: 'on_call_rateEdit',
                            type: 'number'
                        },
                        'holiday': {
                            id: 'holidayEdit',
                            type: 'date'
                        },
                        'orientation_rate': {
                            id: 'orientation_rateEdit',
                            type: 'number'
                        },
                        'block_scheduling': {
                            id: 'block_schedulingEdit',
                            type: 'select',
                            options: {
                                'No': '0',
                                'Yes': '1'
                            }
                        },
                        'float_requirement': {
                            id: 'float_requirementEdit',
                            type: 'select',
                            options: {
                                'No': '0',
                                'Yes': '1'
                            }
                        },
                        'number_of_references': {
                            id: 'number_of_referencesEdit',
                            type: 'number'
                        },
                        'urgency': {
                            id: 'urgencyEdit',
                            type: 'checkbox'
                        },
                        'facilitys_parent_system': {
                            id: 'facilitys_parent_systemEdit',
                            type: 'text'
                        },
                        'facility_name': {
                            id: 'facility_nameEdit',
                            type: 'text'
                        },
                        'pay_frequency': {
                            id: 'pay_frequencyEdit',
                            type: 'select'
                        },
                        'preferred_experience': {
                            id: 'preferred_experienceEdit',
                            type: 'number'
                        },
                        'contract_termination_policy': {
                            id: 'contract_termination_policyEdit',
                            type: 'text'
                        },
                        'four_zero_one_k': {
                            id: 'four_zero_one_kEdit',
                            type: 'select',
                            options: {
                                'No': '0',
                                'Yes': '1'
                            }
                        },
                        'health_insaurance': {
                            id: 'health_insauranceEdit',
                            type: 'select',
                            options: {
                                'No': '0',
                                'Yes': '1'
                            }
                        },
                        'feels_like_per_hour': {
                            id: 'feels_like_per_hourEdit',
                            type: 'number'
                        },
                        'call_back_rate': {
                            id: 'call_back_rateEdit',
                            type: 'number'
                        },
                        'weekly_non_taxable_amount': {
                            id: 'weekly_non_taxable_amountEdit',
                            type: 'number'
                        },
                        'weekly_taxable_amount': {
                            id: 'weekly_taxable_amountEdit',
                            type: 'number'
                        },
                        'start_date': {
                            id: 'start_dateEdit',
                            type: 'date'
                        },
                        'preferred_experience': {
                            id: 'preferred_experienceEdit',
                            type: 'number'
                        },
                        'professional_state_licensure': {
                            id: 'professional_state_licensure_pendingEdit',
                            type: 'radio'
                        },
                        'description': {
                            id: 'descriptionEdit',
                            type: 'text'
                        },
                        'preferred_work_location': {
                            id: 'preferred_work_locationEdit',
                            type: 'text'
                        },
                        'as_soon_as': {
                            id: 'as_soon_asEdit',
                            type: 'checkbox'
                        },
                        'goodwork_weekly_amount': {
                            id: 'goodwork_weekly_amountEdit',
                            type: 'number'
                        },
                        'total_organization_amount': {
                            id: 'total_organization_amountEdit',
                            type: 'number'
                        },
                        'total_goodwork_amount': {
                            id: 'total_goodwork_amountEdit',
                            type: 'number'
                        },
                        'total_contract_amount': {
                            id: 'total_contract_amountEdit',
                            type: 'number'
                        }
                    };

                    for (const [key, field] of Object.entries(fields)) {
                        const element = document.getElementById(field.id);
                        if (!element) continue;
                        if (result[key] === null) {
                            if (field.type === 'select') {
                                element.value = '';
                            } else if (field.type === 'checkbox') {
                                element.checked = false;
                            } else {
                                element.checked = '';
                            }
                            continue;
                        }

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
                            if (field.id === 'urgencyEdit') {
                                element.checked = result[key] === 'Auto Offer';
                            } else {
                                element.checked = Boolean(result[key]);
                            }

                        } else if (field.type === 'radio') {

                            if (result[key] === 'Accept Pending') {
                                document.getElementById('professional_state_licensure_pendingEdit')
                                    .checked = true;
                            } else {
                                document.getElementById('professional_state_licensure_activeEdit').checked =
                                    true;
                            }
                        } else {
                            element.value = result[key];
                        }
                    }
                    // list emr 
                    var emr = result['Emr'];
                    if (emr !== null) {
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
                    if (benefitsResult) {
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
                    if (nurse_classificationResult !== null) {
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
                    if (professional_licensureResult !== null) {

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
                    if (skillsResult !== null) {
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
                    //console.log("sift time of day : ", shifttimeofdayresult);
                    // shifttimeofday is a string use trim to check if it is empty
                    if (shifttimeofdayresult !== null) {

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




                },
                error: function(error) {
                    console.log(error);
                }

            });
        } else {
            console.error('CSRF token not found.');
        }

    }

// Publish or save a job as a draft

    const submitBtn = document.querySelector(".submit");
    const saveDrftBtn = document.querySelectorAll(".saveDrftBtn");
    
    function validateFirst() {

        var access = true;

        var specialtyElement = document.getElementById("preferred_specialty");
        var specialty = specialtyElement.value;
        var professionElement = document.getElementById("perferred_profession");
        var profession = professionElement.value;
        var cityElement = document.getElementById("job_city");
        var city = cityElement.value;
        var stateElement = document.getElementById("job_state");
        var state = stateElement.value;
        var weeklyPayElement = document.getElementById("weekly_pay");
        var weeklyPay = weeklyPayElement.value;
        var termsElement = document.getElementById("terms");
        var terms = termsElement.value;
        var hoursPerWeekElement = document.getElementById("hours_per_week");
        var hoursPerWeek = hoursPerWeekElement.value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rate");
        var actual_hourly_rate = actual_hourly_rate.value;

        if (terms.trim() === '') {

            $('.help-block-terms').text('Please enter the terms');
            $('.help-block-terms').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-terms').text('');
        }


        if (specialty.trim() === '') {

            $('.help-block-preferred_specialty').text('Please enter the Work speciality');
            $('.help-block-preferred_specialty').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialty').text('');
        }

        if (profession.trim() === '') {

            $('.help-block-perferred_profession').text('Please enter the Work profession');
            $('.help-block-perferred_profession').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_profession').text('');
        }

        if (city.trim() === '') {

            $('.help-block-job_city').text('Please enter the Work city');
            $('.help-block-job_city').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_city').text('');
        }

        if (state.trim() === '') {

            $('.help-block-job_state').text('Please enter the Work state');
            $('.help-block-job_state').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_state').text('');
        }



        if (terms === "Perm") {

            if (actual_hourly_rate.trim() === '') {

                $('.help-block-actual_hourly_rate').text('Perm Job requires Actual hourly rate');
                $('.help-block-actual_hourly_rate').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-actual_hourly_rate').text('');
            }

            if (hoursPerWeek.trim() === '') {
                hoursPerWeekElement.closest('.collapse').classList.add('show');
                $('.help-block-hours_per_week').text('Perm Job requires Hours per week');
                $('.help-block-hours_per_week').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-hours_per_week').text('');
            }

        } else if (terms === "Shift") {

            if (actual_hourly_rate.trim() === '') {
                // actual_hourly_rate.closest('.collapse').classList.add('show');
                $('.help-block-actual_hourly_rate').text('Shift Job requires Actual hourly rate');
                $('.help-block-actual_hourly_rate').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-actual_hourly_rate').text('');
            }

        } else if (terms === "Contract (Travel or Local)" || terms === "Contract to Perm" || terms ===
            "Contract (Travel only)" || terms === "Contract (Local only)") {

            if (weeklyPay.trim() === '') {

                $('.help-block-weekly_pay').text('Contract Job requires Weekly pay');
                $('.help-block-weekly_pay').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-weekly_pay').text('');
            }

            if (hoursPerWeek.trim() === '') {
                hoursPerWeekElement.closest('.collapse').classList.add('show');
                $('.help-block-hours_per_week').text(' Contract Job requires Hours per week');
                $('.help-block-hours_per_week').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-hours_per_week').text('');
            }
        }

        if (access) {
            return true;
        } else {
            return false;
        }
    }

    function validateRequiredFieldsToSubmit(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                if (element === 'professional_state_licensure') {
                    const activeElementHtml = document.getElementById('professional_state_licensure_active');
                    const pendingElementHtml = document.getElementById('professional_state_licensure_pending');

                    if (!activeElementHtml.checked && !pendingElementHtml.checked) {
                        const collapseElement = pendingElementHtml.closest('.collapse');
                        if(collapseElement){
                            collapseElement.classList.add('show');
                        }
                        $(`.help-block-professional_state_licensure`).text(`This field is required`);
                        $(`.help-block-professional_state_licensure`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-professional_state_licensure`).text('');

                    }

                    return;
                }

                if (element === 'urgency') {

                    const urgencyElementHtml = document.getElementById('urgency');
                    if (!urgencyElementHtml.checked) {
                        urgencyElementHtml.closest('.collapse').classList.add('show');
                        $(`.help-block-urgency`).text(`This field is required`);
                        $(`.help-block-urgency`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-urgency`).text('');
                    }

                    return;
                }

                const elementHtml = document.getElementById(element);
                const elementValue = elementHtml.value;
                if (elementValue.trim() === '') {
                    const collapseElement = elementHtml.closest('.collapse');
                    if(collapseElement){
                        collapseElement.classList.add('show');
                    }
                    
                    $(`.help-block-${element}`).text(`This field is required`);
                    $(`.help-block-${element}`).addClass('text-danger');
                    access = false;
                } else {

                    $(`.help-block-${element}`).text('');

                }

            });
        }

        return access;
    }

    function validateRequiredMultiCheckFieldsToSubmit(slideFields) {

        let access = true;

        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));
        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                const elementStr = element;
                const elementValue = window[elementStr];
                //console.log('from multiselectcheckvalidation : ', elementValue);

                if (Object.keys(elementValue).length === 0) {
                    //console.log('from first check : ', elementValue);
                    const htmlElement = document.getElementById(element);
                    const collapseElement = htmlElement.closest('.collapse');
                    if(collapseElement){
                        collapseElement.classList.add('show');
                    }
                    $(`.help-block-${element}`).text(`This field is required`);
                    $(`.help-block-${element}`).addClass('text-danger');
                    access = false;
                } else {
                    //console.log('from first check : ', elementValue);
                    $(`.help-block-${element}`).text('');
                }

            });
        }

        return access;
    }

    submitBtn.addEventListener("click", function(event) {

        event.preventDefault();
        $(`.helper`).text('');

        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValues");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValues");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValues");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValues");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValues");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValues");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValues");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }

        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValues");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }

        let holidays_all_values = document.getElementById("holidayAllValues");
        if (holidays_all_values) {
            holidays_all_values.value = holidayStr;
        }

        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills",
            "shift_time_of_day",
            "professional_licensure",
            'holiday'
        ];

        const otherSlideFields = [
            "preferred_experience",
            "traveler_distance_from_facility",
            "clinical_setting",
            "Patient_ratio",
            "Unit",
            "scrub_color",
            "rto",
            "job_id",
            "job_name",
            "job_type",
            "preferred_work_location",
            "referral_bonus",
            "sign_on_bonus",
            "completion_bonus",
            "extension_bonus",
            "other_bonus",
            "on_call",
            "on_call_rate",
            "description",
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure",
            "is_resume",
            "total_contract_amount",
            "total_goodwork_amount",
            "total_organization_amount",
            "goodwork_weekly_amount",
        ];

        if (validateRequiredMultiCheckFieldsToSubmit(slideFields) && validateRequiredFieldsToSubmit(
                otherSlideFields) && validateFirst()) {
            document.getElementById("active").value = true;
            document.getElementById("is_open").value = true;
            event.target.form.submit();
        }



    });

    saveDrftBtn.forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            event.preventDefault();
            let nurse_classification_all_values = document.getElementById(
                "nurse_classificationAllValues");
            if (nurse_classification_all_values) {
                nurse_classification_all_values.value = nurse_classificationStr;
            }

            let professional_licensure_all_values = document.getElementById(
                "professional_licensureAllValues");
            if (professional_licensure_all_values) {
                professional_licensure_all_values.value = professional_licensureStr;
            }

            let Emr_all_values = document.getElementById("EmrAllValues");
            if (Emr_all_values) {
                Emr_all_values.value = EmrStr;
            }

            let benefits_all_values = document.getElementById("benefitsAllValues");
            if (benefits_all_values) {
                benefits_all_values.value = benefitsStr;
            }

            let certif_all_values = document.getElementById("certificateAllValues");
            if (certif_all_values) {
                certif_all_values.value = certificateStr;
            }
            let vaccin_all_values = document.getElementById("vaccinationsAllValues");
            if (vaccin_all_values) {
                vaccin_all_values.value = vaccinationsStr;
            }
            let skills_all_values = document.getElementById("skillsAllValues");
            if (skills_all_values) {
                skills_all_values.value = skillsStr;
            }

            let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValues");
            if (shifttimeofday_all_values) {
                shifttimeofday_all_values.value = shifttimeofdayStr;
            }

            let holidays_all_values = document.getElementById("holidayAllValues");
            if (holidays_all_values) {
                holidays_all_values.value = holidayStr;
            }

            document.getElementById("active").value = true;
            document.getElementById("is_open").value = false;
            let act = document.getElementById("active").value;

            // var jobName = document.getElementById("job_name").value;
            // if (jobName.trim() === '') {
            //     $('.help-block-job_name').text('Enter at least a Work name');
            //     $('.help-block-job_name').addClass('text-danger');
            // } else {
            //     $('.help-block-job_name').text('');
            //     event.target.form.submit();
            // }

            event.target.form.submit();

        });
    });

 // Publish a job draft or save it as a draft

    const submitBtnDraft = document.querySelector(".submitDraft");
    const saveDrftBtnDraft = document.querySelectorAll(".saveDrftBtnDraft");

    function validateFirstDraft() {

        var access = true;

        var specialtyElement = document.getElementById("preferred_specialtyDraft");
        var specialty = specialtyElement.value;
        var professionElement = document.getElementById("perferred_professionDraft");
        var profession = professionElement.value;
        var cityElement = document.getElementById("job_cityDraft");
        var city = cityElement.value;
        var stateElement = document.getElementById("job_stateDraft");
        var state = stateElement.value;
        var weeklyPayElement = document.getElementById("weekly_payDraft");
        var weeklyPay = weeklyPayElement.value;
        var termsElement = document.getElementById("termsDraft");
        var terms = termsElement.value;
        var hoursPerWeekElement = document.getElementById("hours_per_weekDraft");
        var hoursPerWeek = hoursPerWeekElement.value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateDraft");
        var actual_hourly_rate = actual_hourly_rate.value;



        if (terms.trim() === '') {

            $('.help-block-termsDraft').text('Please enter the terms');
            $('.help-block-termsDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-termsDraft').text('');
        }


        if (specialty.trim() === '') {

            $('.help-block-preferred_specialtyDraft').text('Please enter the Work speciality');
            $('.help-block-preferred_specialtyDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialtyDraft').text('');
        }

        if (profession.trim() === '') {

            $('.help-block-perferred_professionDraft').text('Please enter the Work profession');
            $('.help-block-perferred_professionDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_professionDraft').text('');
        }

        if (city.trim() === '') {

            $('.help-block-job_cityDraft').text('Please enter the Work city');
            $('.help-block-job_cityDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_cityDraft').text('');
        }

        if (state.trim() === '') {

            $('.help-block-job_stateDraft').text('Please enter the Work state');
            $('.help-block-job_stateDraft').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_stateDraft').text('');
        }



        if (terms === "Perm") {

            if (actual_hourly_rate.trim() === '') {

                $('.help-block-actual_hourly_rateDraft').text('Perm Job requires Actual hourly rate');
                $('.help-block-actual_hourly_rateDraft').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-actual_hourly_rateDraft').text('');
            }

            if (hoursPerWeek.trim() === '') {
                hoursPerWeekElement.closest('.collapse').classList.add('show');
                $('.help-block-hours_per_weekDraft').text('Perm Job requires Hours per week');
                $('.help-block-hours_per_weekDraft').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-hours_per_weekDraft').text('');
            }

        } else if (terms === "Shift") {

            if (actual_hourly_rate.trim() === '') {
                // actual_hourly_rate.closest('.collapse').classList.add('show');
                $('.help-block-actual_hourly_rateDraft').text('Shift Job requires Actual hourly rate');
                $('.help-block-actual_hourly_rateDraft').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-actual_hourly_rateDraft').text('');
            }

        } else if (terms === "Contract (Travel or Local)" || terms === "Contract to Perm" || terms ===
            "Contract (Travel only)" || terms === "Contract (Local only)") {

            if (weeklyPay.trim() === '') {

                $('.help-block-weekly_payDraft').text('Contract Job requires Weekly pay');
                $('.help-block-weekly_payDraft').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-weekly_payDraft').text('');
            }

            if (hoursPerWeek.trim() === '') {
                hoursPerWeekElement.closest('.collapse').classList.add('show');
                $('.help-block-hours_per_weekDraft').text(' Contract Job requires Hours per week');
                $('.help-block-hours_per_weekDraft').addClass('text-danger');
                access = false;
            } else {
                $('.help-block-hours_per_weekDraft').text('');
            }
        }


        if (access) {
            return true;
        } else {
            return false;
        }
    }

    function validateRequiredFieldsToSubmitDraft(slideFields) {

        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                if (element === 'professional_state_licensure') {
                    const activeElementHtml = document.getElementById(
                        'professional_state_licensure_activeDraft');
                    const pendingElementHtml = document.getElementById(
                        'professional_state_licensure_pendingDraft');

                    if (!activeElementHtml.checked && !pendingElementHtml.checked) {
                        $(`.help-block-professional_state_licensureDraft`).text(`This field is required`);
                        $(`.help-block-professional_state_licensureDraft`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-professional_state_licensureDraft`).text('');

                    }

                    return;
                }

                if (element === 'urgency') {

                    const urgencyElementHtml = document.getElementById('urgencyDraft');
                    if (!urgencyElementHtml.checked) {
                        $(`.help-block-urgencyDraft`).text(`This field is required`);
                        $(`.help-block-urgencyDraft`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-urgencyDraft`).text('');
                    }

                    return;
                }

                const elementHtml = document.getElementById(element + 'Draft');
                const elementValue = elementHtml.value;
                if (elementValue.trim() === '') {

                    const collapseElement = elementHtml.closest('.collapse');
                    if (collapseElement){
                        collapseElement.classList.add('show');
                    }

                    $(`.help-block-${element}Draft`).text(`This field is required`);
                    $(`.help-block-${element}Draft`).addClass('text-danger');
                    access = false;

                } else {

                    $(`.help-block-${element}Draft`).text('');

                }

            });
        }

        return access;
    }

    function validateRequiredMultiCheckFieldsToSubmitDraft(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                const elementStr = element;
                const elementValue = window[elementStr];


                if (Object.keys(elementValue).length === 0) {

                    const htmlElement = document.getElementById(element);
                    const collapseElement = htmlElement.closest('.collapse');
                    if (collapseElement){
                        collapseElement.classList.add('show');
                    }
                    $(`.help-block-${element}Draft`).text(`This field is required`);
                    $(`.help-block-${element}Draft`).addClass('text-danger');
                    access = false;
                } else {
                    $(`.help-block-${element}Draft`).text('');
                }
            });
        }

        return access;
    }

    submitBtnDraft.addEventListener("click", function(event) {
        event.preventDefault();
        document.getElementById("activeDraft").value = true;
        document.getElementById("is_openDraft").value = true;
        $(`.helper`).text('');

        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesDraft");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesDraft");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesDraft");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesDraft");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesDraft");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesDraft");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesDraft");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesDraft");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }
        let holidays_all_values = document.getElementById("holidayAllValuesDraft");
        if (holidays_all_values) {
            holidays_all_values.value = holidayStr;
        }


        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills",
            "shift_time_of_day",
            "professional_licensure",
            "shift_time_of_day",
            "professional_licensure",
            'holiday'
        ];

        const otherSlideFields = [
            "preferred_experience",
            "traveler_distance_from_facility",
            "clinical_setting",
            "Patient_ratio",
            "Unit",
            "scrub_color",
            "rto",
            "job_id",
            "job_name",
            "job_type",
            "preferred_work_location",
            "referral_bonus",
            "sign_on_bonus",
            "completion_bonus",
            "extension_bonus",
            "other_bonus",
            "on_call",
            "on_call_rate",
            "description",
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure",
            "is_resume",
        ];

        if (validateRequiredMultiCheckFieldsToSubmitDraft(slideFields) && validateRequiredFieldsToSubmitDraft(
                otherSlideFields) && validateFirstDraft()) {
            event.target.form.submit();
        }
    });

    saveDrftBtnDraft.forEach(function(saveDrftBtnDraft) {
        saveDrftBtnDraft.addEventListener("click", function(event) {
            event.preventDefault();
            let nurse_classification_all_values = document.getElementById(
                "nurse_classificationAllValuesDraft");
            if (nurse_classification_all_values) {
                nurse_classification_all_values.value = nurse_classificationStr;
            }

            let professional_licensure_all_values = document.getElementById(
                "professional_licensureAllValuesDraft");
            if (professional_licensure_all_values) {
                professional_licensure_all_values.value = professional_licensureStr;
            }

            let Emr_all_values = document.getElementById("EmrAllValuesDraft");
            if (Emr_all_values) {
                Emr_all_values.value = EmrStr;
            }

            let benefits_all_values = document.getElementById("benefitsAllValuesDraft");
            if (benefits_all_values) {
                benefits_all_values.value = benefitsStr;
            }

            let certif_all_values = document.getElementById("certificateAllValuesDraft");
            if (certif_all_values) {
                certif_all_values.value = certificateStr;
            }
            let vaccin_all_values = document.getElementById("vaccinationsAllValuesDraft");
            if (vaccin_all_values) {
                vaccin_all_values.value = vaccinationsStr;
            }
            let skills_all_values = document.getElementById("skillsAllValuesDraft");
            if (skills_all_values) {
                skills_all_values.value = skillsStr;
            }
            let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesDraft");
            if (shifttimeofday_all_values) {
                shifttimeofday_all_values.value = shifttimeofdayStr;
            }
            let holidays_all_values = document.getElementById("holidayAllValuesDraft");
            if (holidays_all_values) {
                holidays_all_values.value = holidayStr;
            }
            document.getElementById("activeDraft").value = true;
            document.getElementById("is_openDraft").value = false;
            // var jobName = document.getElementById("job_nameDraft").value;
            // if (jobName.trim() === '') {
            //     $('.help-block-job_name').text('Enter at least a Work name');
            //     $('.help-block-job_name').addClass('text-danger');

            // } else {
            //     $('.help-block-job_name').text('');
            //     event.target.form.submit();
            // }
            event.target.form.submit();
        });
    });

// Edit a publish job or save it as a draft

    const submitBtnEdit = document.querySelector(".submitEdit");
    const saveDrftBtnEdit = document.querySelectorAll(".saveDrftBtnEdit");

    function validateFirstEdit() {
        var access = true;
        
        var specialtyElement = document.getElementById("preferred_specialtyEdit");
        var specialty = specialtyElement.value;
        var professionElement = document.getElementById("perferred_professionEdit");
        var profession = professionElement.value;
        var cityElement = document.getElementById("job_cityEdit");
        var city = cityElement.value;
        var stateElement = document.getElementById("job_stateEdit");
        var state = stateElement.value;
        var weeklyPayElement = document.getElementById("weekly_payEdit");
        var weeklyPay = weeklyPayElement.value;
        var termsElement = document.getElementById("termsEdit");
        var terms = termsElement.value;
        var hoursPerWeekElement = document.getElementById("hours_per_weekEdit");
        var hoursPerWeek = hoursPerWeekElement.value;
        var actual_hourly_rate = document.getElementById("actual_hourly_rateEdit");
        var actual_hourly_rate = actual_hourly_rate.value;

        if (specialty.trim() === '') {
            $('.help-block-preferred_specialtyEdit').text('Please enter the Work speciality');
            $('.help-block-preferred_specialtyEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-preferred_specialtyEdit').text('');

        }

        if (profession.trim() === '') {
            $('.help-block-perferred_professionEdit').text('Please enter the Work profession');
            $('.help-block-perferred_professionEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-perferred_professionEdit').text('');

        }

        if (terms.trim() === '') {
            $('.help-block-termsEdit').text('Please enter the terms');
            $('.help-block-termsEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-termsEdit').text('');
        }

        if (city.trim() === '') {
            $('.help-block-job_cityEdit').text('Please enter the Work city');
            $('.help-block-job_cityEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_cityEdit').text('');

        }

        if (state.trim() === '') {
            $('.help-block-job_stateEdit').text('Please enter the Work state');
            $('.help-block-job_stateEdit').addClass('text-danger');
            access = false;
        } else {
            $('.help-block-job_stateEdit').text('');
        }

        if (terms === "Perm") {

                if (actual_hourly_rate.trim() === '') {
                
                    $('.help-block-actual_hourly_rateEdit').text('Perm Job requires Actual hourly rate');
                    $('.help-block-actual_hourly_rateEdit').addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-actual_hourly_rateEdit').text('');
                }

                if (hoursPerWeek.trim() === '') {
                    hoursPerWeekElement.closest('.collapse').classList.add('show');
                    $('.help-block-hours_per_weekEdit').text('Perm Job requires Hours per week');
                    $('.help-block-hours_per_weekEdit').addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-hours_per_weekEdit').text('');
                }

        } else if (terms === "Shift") {
                
                if (actual_hourly_rate.trim() === '') {
                    // actual_hourly_rate.closest('.collapse').classList.add('show');
                    $('.help-block-actual_hourly_rateEdit').text('Shift Job requires Actual hourly rate');
                    $('.help-block-actual_hourly_rateEdit').addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-actual_hourly_rateEdit').text('');
                }

        } else if (terms === "Contract (Travel or Local)" || terms === "Contract to Perm" || terms ===
                "Contract (Travel only)" || terms === "Contract (Local only)") {
                
                if (weeklyPay.trim() === '') {
                
                    $('.help-block-weekly_payEdit').text('Contract Job requires Weekly pay');
                    $('.help-block-weekly_payEdit').addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-weekly_payEdit').text('');
                }

                if (hoursPerWeek.trim() === '') {
                    hoursPerWeekElement.closest('.collapse').classList.add('show');
                    $('.help-block-hours_per_weekEdit').text(' Contract Job requires Hours per week');
                    $('.help-block-hours_per_weekEdit').addClass('text-danger');
                    access = false;
                } else {
                    $('.help-block-hours_per_weekEdit').text('');
                }
        }

        if (access) {
            return true;
        } else {
            return false;
        }
 
    }

    function validateRequiredFieldsToSubmitEdit(slideFields) {

        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                if (element === 'professional_state_licensure') {
                    const activeElementHtml = document.getElementById(
                    'professional_state_licensure_activeEdit');
                    const pendingElementHtml = document.getElementById(
                        'professional_state_licensure_pendingEdit');

                    if (!activeElementHtml.checked && !pendingElementHtml.checked) {
                        $(`.help-block-professional_state_licensureEdit`).text(`This field is required`);
                        $(`.help-block-professional_state_licensureEdit`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-professional_state_licensureEdit`).text('');

                    }

                    return;
                }

                if (element === 'urgency') {

                    const urgencyElementHtml = document.getElementById('urgencyEdit');
                    if (!urgencyElementHtml.checked) {
                        $(`.help-block-urgencyEdit`).text(`This field is required`);
                        $(`.help-block-urgencyEdit`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-urgencyEdit`).text('');
                    }

                    return;
                }

                if (element === 'is_resume') {

                    const resumeElementHtml = document.getElementById('job_isResumeEdit');
                    if (!resumeElementHtml.checked) {
                        $(`.help-block-resumeEdit`).text(`This field is required`);
                        $(`.help-block-resumeEdit`).addClass('text-danger');
                        access = false;
                    } else {
                        $(`.help-block-resumeEdit`).text('');
                    }

                    return;
                }

                const elementHtml = document.getElementById(element + 'Edit');
                const elementValue = elementHtml.value;
                if (elementValue.trim() === '') {

                    $(`.help-block-${element}Edit`).text(`This field is required`);
                    $(`.help-block-${element}Edit`).addClass('text-danger');
                    access = false;

                } else {

                    $(`.help-block-${element}Edit`).text('');

                }

            });
        }

        return access;
    }

    function validateRequiredMultiCheckFieldsToSubmitEdit(slideFields) {
        let access = true;
        const commonElements = slideFields.filter(element => requiredToSubmit.includes(element));

        if (commonElements.length > 0) {

            commonElements.forEach(element => {

                const elementStr = element;
                const elementValue = window[elementStr];


                if (Object.keys(elementValue).length === 0) {
                    $(`.help-block-${element}Edit`).text(`This field is required`);
                    $(`.help-block-${element}Edit`).addClass('text-danger');
                    access = false;
                } else {
                    $(`.help-block-${element}Edit`).text('');
                }
            });
        }

        return access;
    }

    submitBtnEdit.addEventListener("click", function(event) {

        event.preventDefault();
        $(`.helper`).text('');
        let nurse_classification_all_values = document.getElementById("nurse_classificationAllValuesEdit");
        if (nurse_classification_all_values) {
            nurse_classification_all_values.value = nurse_classificationStr;
        }

        let professional_licensure_all_values = document.getElementById("professional_licensureAllValuesEdit");
        if (professional_licensure_all_values) {
            professional_licensure_all_values.value = professional_licensureStr;
        }

        let Emr_all_values = document.getElementById("EmrAllValuesEdit");
        if (Emr_all_values) {
            Emr_all_values.value = EmrStr;
        }

        let benefits_all_values = document.getElementById("benefitsAllValuesEdit");
        if (benefits_all_values) {
            benefits_all_values.value = benefitsStr;
        }

        let certif_all_values = document.getElementById("certificateAllValuesEdit");
        if (certif_all_values) {
            certif_all_values.value = certificateStr;
        }
        let vaccin_all_values = document.getElementById("vaccinationsAllValuesEdit");
        if (vaccin_all_values) {
            vaccin_all_values.value = vaccinationsStr;
        }
        let skills_all_values = document.getElementById("skillsAllValuesEdit");
        if (skills_all_values) {
            skills_all_values.value = skillsStr;
        }
        let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesEdit");
        if (shifttimeofday_all_values) {
            shifttimeofday_all_values.value = shifttimeofdayStr;
        }
        let holidays_all_values = document.getElementById("holidayAllValuesEdit");
        if (holidays_all_values) {
            holidays_all_values.value = holidayStr;
        }
        document.getElementById("activeEdit").value = true;
        document.getElementById("is_openEdit").value = true;

        const slideFields = [
            "nurse_classification",
            "Emr",
            "benefits",
            "certificate",
            "vaccinations",
            "skills"
        ];

        const otherSlideFields = [
            "preferred_experience",
            "traveler_distance_from_facility",
            "clinical_setting",
            "Patient_ratio",
            "Unit",
            "scrub_color",
            "rto",
            "job_id",
            "job_name",
            "job_type",
            "preferred_work_location",
            "referral_bonus",
            "sign_on_bonus",
            "completion_bonus",
            "extension_bonus",
            "other_bonus",
            "on_call",
            "on_call_rate",
            "description",
            "holiday",
            "orientation_rate",
            "block_scheduling",
            "float_requirement",
            "number_of_references",
            "facilitys_parent_system",
            "facility_name",
            "contract_termination_policy",
            "facility_shift_cancelation_policy",
            "four_zero_one_k",
            "health_insaurance",
            "feels_like_per_hour",
            "call_back_rate",
            "as_soon_as",
            "start_date",
            "urgency",
            "professional_state_licensure",
            "is_resume",
            "total_contract_amount",
            "total_goodwork_amount",
            "total_organization_amount",
            "goodwork_weekly_amount",
        ];

        if (validateRequiredMultiCheckFieldsToSubmitEdit(slideFields) && validateRequiredFieldsToSubmitEdit(
                otherSlideFields) && validateFirstEdit()) {
            event.target.form.submit();
        }
    });


    saveDrftBtnEdit.forEach(function(saveDrftBtnEdit) {
        saveDrftBtnEdit.addEventListener("click", function(event) {
            event.preventDefault();
            let nurse_classification_all_values = document.getElementById(
                "nurse_classificationAllValuesEdit");
            if (nurse_classification_all_values) {
                nurse_classification_all_values.value = nurse_classificationStr;
            }

            let professional_licensure_all_values = document.getElementById(
                "professional_licensureAllValuesEdit");
            if (professional_licensure_all_values) {
                professional_licensure_all_values.value = professional_licensureStr;
            }

            let Emr_all_values = document.getElementById("EmrAllValuesEdit");
            if (Emr_all_values) {
                Emr_all_values.value = EmrStr;
            }

            let benefits_all_values = document.getElementById("benefitsAllValuesEdit");
            if (benefits_all_values) {
                benefits_all_values.value = benefitsStr;
            }

            let certif_all_values = document.getElementById("certificateAllValuesEdit");
            if (certif_all_values) {
                certif_all_values.value = certificateStr;
            }
            let vaccin_all_values = document.getElementById("vaccinationsAllValuesEdit");
            if (vaccin_all_values) {
                vaccin_all_values.value = vaccinationsStr;
            }
            let skills_all_values = document.getElementById("skillsAllValuesEdit");
            if (skills_all_values) {
                skills_all_values.value = skillsStr;
            }
            let shifttimeofday_all_values = document.getElementById("shifttimeofdayAllValuesEdit");
            if (shifttimeofday_all_values) {
                shifttimeofday_all_values.value = shifttimeofdayStr;
            }
            let holidays_all_values = document.getElementById("holidayAllValuesEdit");
            if (holidays_all_values) {
                holidays_all_values.value = holidayStr;
            }
            document.getElementById("activeEdit").value = true;
            document.getElementById("is_openEdit").value = false;
            // var jobName = document.getElementById("job_nameEdit").value;
            // if (jobName.trim() === '') {
            //     $('.help-block-job_name').text('Enter at least a Work name');
            //     $('.help-block-job_name').addClass('text-danger');
            // } else {
            //     $('.help-block-job_name').text('');
            //     event.target.form.submit();
            // }
            event.target.form.submit();
        });
    });

    
</script>


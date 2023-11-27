@extends('recruiter::layouts.main')

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
              <li><button id="drafts" onclick="opportunitiesType('drafts')" class="ss-darfts-sec-draft-btn">Drafts</button></li>
              <li><button id="published" onclick="opportunitiesType('published')" class="ss-darfts-sec-publsh-btn">Published</button></li>
              <li><button id="hidden" onclick="opportunitiesType('hidden')" class="ss-darfts-sec-publsh-btn">Hidden</button></li>
              <li><button id="closed" onclick="opportunitiesType('closed')" class="ss-darfts-sec-publsh-btn">Close</button></li>
              <li><a href="{{ route('recruiter-create-opportunity') }}" class="ss-opr-mngr-plus-sec"><i class="fas fa-plus"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="ss-no-job-mn-dv" id="no-job-posted">
        <div class="row">
          <div class="col-lg-12">
            <div class="ss-nojob-dv-hed">
              <h6>No Job Posted.<br>
                Start Creating Job Request</h6>
              <a href="{{ route('recruiter-create-opportunity') }}">Create Job Request</a>
            </div>
          </div>
        </div>
      </div>
      <div class="ss-acount-profile d-none" id="published-job-details">
        <div class="row">
          <div class="col-lg-5">
            <div class="ss-account-form-lft-1">
              <h5 class="mb-4 text-capitalize" id="opportunitylistname"></h5>
              <div id="job-list">
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="ss-opp--mng-publ-right-dv">
              <div id="job-details">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
  function opportunitiesType(type, id = "", formtype) {

    var draftsElement = document.getElementById('drafts');
    var publishedElement = document.getElementById('published');
    var hiddenElement = document.getElementById('hidden');
    var closedElement = document.getElementById('closed');

    if (draftsElement.classList.contains("active")) {
        draftsElement.classList.remove("active");
    }
    if (publishedElement.classList.contains("active")) {
        publishedElement.classList.remove("active");
    }
    if (hiddenElement.classList.contains("active")) {
        hiddenElement.classList.remove("active");
    }
    if (closedElement.classList.contains("active")) {
        closedElement.classList.remove("active");
    }
    document.getElementById(type).classList.add("active")

    window.scrollTo({
      top: 0,
      behavior: "smooth" 
    });
    let activestatus = 0;
    document.getElementById('opportunitylistname').innerHTML = type;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (csrfToken) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        url: "{{ url('recruiter/get-job-listing') }}",
        data: {
          'token': csrfToken,
          'type': type,
          'id': id,
          'formtype': formtype,
        },
        type: 'POST',
        dataType: 'json',
        success: function(result) {
          $("#job-list").html(result.joblisting);
          $("#job-details").html(result.jobdetails);

          window.allspecialty = result.allspecialty;
          window.allvaccinations = result.allvaccinations;
          window.allcertificate = result.allcertificate;
          list_specialities();
          list_vaccinations();
          list_certifications();
          if (result.joblisting != "") {
            document.getElementById("published-job-details").classList.remove("d-none");
            document.getElementById("no-job-posted").classList.add("d-none");
          }
        },
        error: function(error) {
          // Handle errors
        }
      });
    } else {
      console.error('CSRF token not found.');
    }
    // editJob();
  }
  $(document).ready(function() {
    opportunitiesType('published')
  });

  function editOpportunity(id = "", formtype) {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (csrfToken) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        url: "{{ url('recruiter/get-job-listing') }}",
        data: {
          'id': id,
          'formtype': formtype
        },
        type: 'POST',
        dataType: 'json',
        success: function(result) {

          // $("#application-list").html(result.applicationlisting);
          // $("#application-details").html(result.applicationdetails);
        },
        error: function(error) {
          // Handle errors
        }
      });
    } else {
      console.error('CSRF token not found.');
    }
  }

  function editJob(inputField) {
    var value = inputField.value;
    var name = inputField.name;

    if (value != "") {
      if (name == "vaccinations" || name == "preferred_specialty" || name == "preferred_experience" || name == "certificate") {
        var inputFields = document.querySelectorAll(name == "vaccinations" ? 'select[name="vaccinations"]' : name == "preferred_specialty" ? 'select[name="preferred_specialty"]' : name == "preferred_experience" ? 'input[name="preferred_experience"]' : 'select[name="certificate"]');
        var data = [];
        inputFields.forEach(function(input) {
          data.push(input.value);
        });
 
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
          // notie.alert({
          //   type: 'success',
          //   text: '<i class="fa fa-check"></i> ' + data.message,
          //   time: 5
          // });
          if (document.getElementById('job_id').value.trim() == '' || document.getElementById('job_id').value.trim() == 'null' || document.getElementById('job_id').value.trim() == null) {
            document.getElementById("job_id").value = data.job_id;
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }
  }

  function addmoreexperience() {
    var allExperienceDiv = document.getElementById('all-experience');
    var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
    newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
    newExperienceDiv.querySelector('input[type="number"]').value = '';
    allExperienceDiv.appendChild(newExperienceDiv);
  }

  function changeStatus(type, id = '0'){
    if(type == "draft"){
      notie.alert({
        type: 'success',
        text: '<i class="fa fa-check"></i> Draft Updated Successfully' ,
        time: 5
      });
    }else{
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      if (csrfToken) {
          event.preventDefault();
          let check_type = type;
          console.log(document.getElementById('job_id'));
          // if (document.getElementById('job_id').value) {
              if(id == '0'){
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
                  url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,
    
                  data: formData,
                  dataType: 'json',
                  success: function(data) {
                      notie.alert({
                          type: 'success',
                          text: '<i class="fa fa-check"></i> ' + data.message,
                          time: 5
                      });
                      
                      if(type == "hidejob"){
                        opportunitiesType('hidden')
                      }else{
                        opportunitiesType('published')
                      }
                  },
                  error: function(error) {
                      console.log(error);
                  }
              });
          // }
      } else {
          console.error('CSRF token not found.');
      }
    }
  }


  // function addvacc() {
  //   var formfield = document.getElementById('add-more-vacc-immu');
  //   var newField = document.createElement('input');
  //   newField.setAttribute('type', 'text');
  //   newField.setAttribute('name', 'vaccinations');
  //   newField.setAttribute('class', 'mb-3');
  //   newField.setAttribute('placeholder', 'Enter Vacc. or Immu. name');
  //   newField.setAttribute('onfocusout', 'editJob(this)');
  //   formfield.appendChild(newField);
  // }
  // function addvacc() {
  //   var container = document.getElementById('add-more-vacc-immu');

  //   var newSelect = document.createElement('select');
  //   newSelect.name = 'vaccinations';
  //   newSelect.className = 'vaccinations mb-3';

  //   var originalSelect = document.getElementById('vacc-immu');
  //   var options = originalSelect.querySelectorAll('option');
  //   for (var i = 0; i < options.length; i++) {
  //       var option = options[i].cloneNode(true);
  //       newSelect.appendChild(option);
  //   }
  //   container.querySelector('.col-md-11').appendChild(newSelect);
  // }

  // function addcertifications() {
  //   var formfieldcertificate = document.getElementById('add-more-certifications');
  //   var newField = document.createElement('input');
  //   newField.setAttribute('type', 'text');
  //   newField.setAttribute('name', 'certificate');
  //   newField.setAttribute('class', 'mb-3');
  //   newField.setAttribute('placeholder', 'Enter Certification');
  //   newField.setAttribute('onfocusout', 'editJob(this)');
  //   formfieldcertificate.appendChild(newField);
  // }
  // function addcertifications() {
  //   var container = document.getElementById('add-more-certifications');

  //   var newSelect = document.createElement('select');
  //   newSelect.name = 'certificate';
  //   newSelect.className = 'mb-3';

  //   var originalSelect = document.getElementById('certificate');
  //   var options = originalSelect.querySelectorAll('option');
  //   for (var i = 0; i < options.length; i++) {
  //       var option = options[i].cloneNode(true);
  //       newSelect.appendChild(option);
  //   }
  //   container.querySelector('.col-md-11').appendChild(newSelect);
  // }

    function offerSend(id, jobid, type, workerid, recruiterid) {
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      if (csrfToken) {
        let counterstatus = "1";
        if (type == "rejectcounter") {
          counterstatus = "0";
        }
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': csrfToken
          },
          url: "{{ url('recruiter/recruiter-send-job-offer') }}",
          data: {
            'token': csrfToken,
            'id': id,
            'job_id': jobid,
            'counterstatus': counterstatus,
            'worker_user_id': workerid,
            'recruiter_id': recruiterid,
            'is_draft': "1",
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
    }, 3000)

    function updateJob(){
      notie.alert({
          type: 'success',
          text: '<i class="fa fa-check"></i> Job Updated Successfully.',
          time: 3
      });
    }

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
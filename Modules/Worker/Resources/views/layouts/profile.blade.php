@extends('layouts.dashboard')
@section('mytitle', 'My Profile')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">

    <div class="ss-my-profile--basic-mn-sec">
      <div class="row">
        <div class="col-lg-5">
            <div class="ss-my-profil-div">
                <h2>My <span class="ss-pink-color">Profile</span></h2>
                <div class="ss-my-profil-img-div">
                    <img src="{{URL::asset('images/workers/profile/'.$user->image)}}" onerror="this.onerror=null;this.src='{{USER_IMG}}';" id="preview" width="112px" height="112px" style="object-fit: cover;"/>
                    <h4>{{$user->first_name}}</h4>
                    <p>{{$model->id}} </p>
                </div>
                <div class="ss-profil-complet-div">
                    <ul>
                        <li><img src="{{URL::asset('frontend/img/progress.png')}}" /></li>
                        <li>
                        <h6>Profile Incomplete</h6>
                        <p>You're just a few percent away from revenue. Complete your profile and get 5%.</p>
                        </li>
                    </ul>
                </div>

                <div class="ss-my-presnl-btn-mn">

                    <div class="ss-my-prsnl-wrapper">
                        <div class="ss-my-prosnl-rdio-btn" data-href="{{route('worker-profile')}}" onclick="redirect_to(this)">
                            <input type="radio" name="select" id="option-1" {{ ( in_array(request()->route()->getName(), ['worker-profile','info-required', 'urgency', 'float-requirement','patient-ratio', 'interview-dates', 'bonuses', 'work-hours'])) ? 'checked': ''}}>
                            <label for="option-1" class="option option-1">
                                <div class="dot"></div>
                                <ul>
                                    <li><img src="{{URL::asset('frontend/img/my-per--con-user.png')}}" /></li>
                                    <li><p>Professional Information </p></li>
                                    <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
                                </ul>
                            </label>
                        </div>

                        <div class="ss-my-prosnl-rdio-btn" data-href="{{route('vaccination')}}" onclick="redirect_to(this)">
                            <input type="radio" name="select" id="option-2" {{ ( request()->route()->getName()== 'vaccination') ? 'checked': ''}}>
                            <label for="option-2" class="option option-2">
                                <div class="dot"></div>
                                <ul>
                                    <li><img src="{{URL::asset('frontend/img/my-per--con-vaccine.png')}}" /></li>
                                    <li><p>Vaccinations & Immunizations</p> </li>
                                        <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
                                </ul>
                            </label>
                        </div>

                        <div class="ss-my-prosnl-rdio-btn"  data-href="{{route('references')}}" onclick="redirect_to(this)">
                            <input type="radio" name="select" id="option-3" {{ ( request()->route()->getName()== 'references') ? 'checked': ''}}>
                            <label for="option-3" class="option option-3">
                            <div class="dot"></div>
                                <ul>
                                    <li><img src="{{URL::asset('frontend/img/my-per--con-refren.png')}}" /></li>
                                    <li><p>References</p> </li>
                                    <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
                                </ul>
                            </label>
                        </div>

                        <div class="ss-my-prosnl-rdio-btn" data-href="{{route('certification')}}" onclick="redirect_to(this)">
                            <input type="radio" name="select" id="option-4" {{ ( request()->route()->getName()== 'certification') ? 'checked': ''}}>
                            <label for="option-4" class="option option-4">
                            <div class="dot"></div>
                                <ul>
                                    <li><img src="{{URL::asset('frontend/img/my-per--con-key.png')}}" /></li>
                                    <li><p>Certifications</p> </li>
                                    <li><span class="img-white"><img src="{{URL::asset('frontend/img/arrowcircleright.png')}}" /></span></li>
                                </ul>
                            </label>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!--------Professional Information form--------->

         <div class="col-lg-7">
           <div class="ss-pers-info-form-mn-dv">
             @yield('form')
           </div>
         </div>
      </div>
    </div>

    </div>

</main>
@stop


@section('js')

<script>
    // $('#skills').tagsinput();
    $(document).ready(function () {
        $('#skills').tagsinput();
    });
</script>


<script>

    function redirect_to(obj)
    {
        window.location.href = $(obj).data('href');
    }


    function get_states(obj)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: full_path+'get-states',
            type: 'POST',
            dataType: 'json',
            // processData: false,
            // contentType: false,
            data: {
                country_id: $(obj).val()
            },
            success: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                if (resp.success) {
                    $('#state').html(resp.content);
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
            }
        });
    }


    function get_cities(obj)
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: full_path+'get-cities',
            type: 'POST',
            dataType: 'json',
            // processData: false,
            // contentType: false,
            data: {
                state_id: $(obj).val()
            },
            success: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
                if (resp.success) {
                    $('#city').html(resp.content);
                }
            },
            error: function (resp) {
                console.log(resp);
                ajaxindicatorstop();
            }
        });
    }

    function hide_show_select(obj) {
        let value, type, container;
        value = $(obj).val();
        type = $(obj).data('type');
        console.log(type);
        container = $('.'+type);

        if (value === 'Yes') {
            container.removeClass('d-none');
        }else{
            container.addClass('d-none');
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('.no-redirect-form').on('submit', function (event) {
            var form = $(this);
            $('.help-block').html('').closest('.has-error').removeClass('has-error');
            event.preventDefault();
            ajaxindicatorstart();
            const url = form.attr('action');
            var data = new FormData(form[0]);
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function (resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                    if (resp.success) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + resp.msg,
                            time: 3
                        });
                        // setTimeout(() => {
                        //     window.location.href = $('#skip-button').data('href');
                        // }, 3000);

                    }
                },
                error: function (resp) {
                    ajaxindicatorstop();
                    console.log(resp);
                    $.each(resp.responseJSON.errors, function (key, val) {
                        console.log(val[0]);
                        form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                        form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
                    });

                }
            })
        });

        $('.redirect-after-submit').on('submit', function (event) {
            var form = $(this);
            $('.help-block').html('').closest('.has-error').removeClass('has-error');
            event.preventDefault();
            ajaxindicatorstart();
            const url = form.attr('action');
            var data = new FormData(form[0]);
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: data,
                success: function (resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                    if (resp.success) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + resp.msg,
                            time: 3
                        });
                        setTimeout(() => {
                            window.location.href = $('#skip-button').data('href');
                        }, 3000);

                    }
                },
                error: function (resp) {
                    ajaxindicatorstop();
                    console.log(resp);
                    $.each(resp.responseJSON.errors, function (key, val) {
                        console.log(val[0]);
                        form.find('[name="' + key + '"]').closest('.ss-form-group').find('.help-block').html(val[0]);
                        form.find('[name="' + key + '"]').closest('.ss-form-group').addClass('has-error');
                    });

                }
            })
        });

    });
</script>
@stack('form-js')
@stop

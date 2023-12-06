<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var full_path = '<?= url('/') . '/'; ?>';
        var logged_in = '<?= (Auth()->guard('frontend')->guest()) ? false : true; ?>';
    </script>
    <meta charset="UTF-8">


    <title>@yield('mytitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">
    <link href="{{URL::asset('landing/css/style.css')}}" rel="stylesheet">
    <link rel='stylesheet' href="{{URL::asset('landing/css/owl.carousel.css')}}">
    <script src="https://kit.fontawesome.com/69b12198c3.js" crossorigin="anonymous"></script>
    {{-- jquery confirm --}}
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    {{-- Notie --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    @yield('css')
</head>


<body>
    <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>
    

        <!-- nav WITH ONLY LOGOUT -->

<!-----header section------>
<section class="ss-hm-header-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{route('/')}}"><img src="{{URL::asset('landing/img/logo.png')}}" /></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarText">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                
                            </ul>
                            <span class="navbar-text">
                                <div class="ss-log-div-sec">
                                    <ul>
                                        <li>
                                        </li>
                                        <li>
                                            <a href="{{route('employer-logout')}}">Add Job</a>
                                        </li>
                                        <li>
                                            <a href="{{route('employer-logout')}}">Logout</a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-----header section------>

    
    <section class="ss-login-work-sec ss-admin-login-sed">
    <div class="col-lg-12">
          <!--------login form------->
          <div class="ss-login-work-dv">
            <h4><span>Save </span> New Job</h4>
          
            <form class="" method="post" action="{{route('addJob.store')}}" id="addJob-form-submit">
            @csrf
                <div class="ss-form-group">
                    <input type="text" name="job_type" placeholder="Job Type">
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group">
                    <input type="text" name="job_name" placeholder="Job Name" />
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text" name="job_city" placeholder="City">
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text"  name="job_state" placeholder="State" >
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text"  name="preferred_assignment_duration" placeholder="Weeks duration" >
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text"  name="weekly_pay" placeholder="Weekly pay" >
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text"  name="preferred_specialty" placeholder="Speciality" >
                    <span class="help-block"></span>
                </div>
                
                <div>
                    <button type="submit">Confirm</button>
                </div>
              
                
            </form>


            <!-- <div class="ss-skipfor-div">
              <a href="#">Skip for now >></a>
            </div> -->
          </div>

        </div>
</section>
  

    <!-- <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/mdb.min.js')}}"></script>

    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    {{-- <script type="text/javascript" src="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script> --}}
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    {{-- Jquery Mask --}}
    <script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
    <script type="text/javascript">
        $('.ss-feed-slid').owlCarousel({

            items: 3,

            loop: true,

            autoplay: true,

            autoplayTimeout: 5000,

            margin: 20,

            nav: false,

            dots: true,

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

                    items: 3

                }

            }



        });
    </script>

    <script type="text/javascript">
        $('.ss-what-ofr-slid-mob').owlCarousel({

            items: 3,

            loop: true,

            autoplay: true,

            autoplayTimeout: 5000,

            margin: 20,

            nav: false,

            dots: true,

            navText: ['<span class="fa fa-angle-left  fa-2x"></span>', '<span class="fas fa fa-angle-right fa-2x"></span>'],

            responsive: {

                0: {

                    items: 1

                },

                480: {

                    items: 1

                },

                768: {

                    items: 1

                },

                992: {

                    items: 1

                }

            }



        });
    </script>

    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>
    @yield('js')
    @include('partials.flashMsg')

</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var full_path = '<?= url('/') . '/' ?>';
        var logged_in = '<?= Auth()->guard('frontend')->guest() ? false : true ?>';
    </script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Thanks For Applying</title>
    
    <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" crossorigin="anonymous">

    <link rel="stylesheet" href="{{URL::asset('frontend/css/fontawesome_all.css')}}" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" /> --}}
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/mdb.min.css') }}" />
    {{-- jquery confirm --}}
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    {{-- Notie --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ URL::asset('frontend/css/style.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('frontend/custom/css/custom.css') }}" />
    @yield('css')


    <style>
        .ads-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .ad {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd; /* Optional: Add a border to separate ads */
        }

        .ad-image {
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            margin-right: 20px; /* Space between image and text */
        }

        .ad-content {
            flex: 1;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .cta-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>

<body>
    <script src="{{ URL::asset('landing/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!--Main Navigation-->
    <header>
        <!-- Sidebar -->
        @include('worker::partials.worker_sidebar')
        <!-- Sidebar Header-->
        @include('worker::partials.worker_header')
    </header>

    @yield('content')
    
    <script src="{{ URL::asset('landing/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    
    
    <!--Main layout-->
    <main style="padding-top: 170px" class="ss-main-body-sec">
        <div class="ads-container">
            
            <div class="mb-4">

                <h1>Thanks! You've successfully applied</h1>
                <p>Find Work, Build Credit, and Take Control of Your Finances – All in One Place!</p>
 
            </div>
            
            <a href="https://www.bhmediatrack.com/cmp/M15NZ8/2MCHZ8R/?sub1=&sub2=&sub3=" target="_blank">
                <div class="ad">
                    <img src="{{ asset('images/debtmd.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>Become Debt-Free Today</h2>
                        <ul>
                            <li>Up to 50% lower monthly payments</li>
                            <li>Flexible payment plans from 12-48 months</li>
                            <li>Free consultation and zero up-front fees</li>
                        </ul>
                    </div>
                </div>
            </a>

            <a href="https://www.bhmediatrack.com/25S2ZK4/2LWX2H7/?sub1=1&sub2=2&sub3=3" target="_blank">
            
                <div class="ad">
                    <img src="{{ asset('images/myPerfectResume.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>Get the help you need to land your next gig with a <b>custom resume!</b></h2>
                        <p>
                            Our Industry-leading resume tools take your job search from basic to next level.
                        </p>
                    </div>
                </div>

            </a>

            <a href="https://www.bhmediatrack.com/25S2ZK4/6Z7MWN9/?sub1=1&sub2=2&sub3=3"  target="_blank">
                    
                <div class="ad">
                    <img src="{{ asset('images/chime.png') }}" alt="Ad Image" class="ad-image">
                    <div class="ad-content">
                        <h2>
                            The best way to get up to $500 before payday*
                        </h2>
                        <ul>
                            <li>No interest*</li>
                            <li>No credit check</li>
                            <li>No mandatory fees</li>
                        </ul>
                    </div>
                </div>

            </a>
        </div>
    </main>
    <script type="text/javascript" src="{{ URL::asset('frontend/js/mdb.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}"
        type="text/javascript"></script>

    {{-- <script type="text/javascript" src="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script> --}}

    <script type="text/javascript" src="{{ URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>

    {{-- CK editor --}}
    <script src="{{ URL::asset('backend/vendors/ckeditor/ckeditor.js') }}"></script>

    {{-- Jquery Mask --}}
    <script type="text/javascript" src="{{ URL::asset('frontend/vendor/mask/jquery.mask.min.js') }}"></script>

    <!-- Custom scripts -->
    <script type="text/javascript" src="{{ URL::asset('frontend/js/nav-bar-script.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('frontend/custom/js/profile.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('frontend/custom/js/script.js') }}"></script>

    @yield('js')

</body>

</html>

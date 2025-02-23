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
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome -->
    <!-- Bootstrap CSS -->
    <!-- <link href="{{ URL::asset('landing/css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
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
        main {
            padding-bottom: 0px;
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
    <!--Main Navigation-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!--Main layout-->
    @yield('content')
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="{{ URL::asset('landing/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script> -->
    <script src="{{ URL::asset('landing/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
    <!--Main layout-->

    <!-- MDB -->
    <main style="padding-top: 170px" class="ss-main-body-sec">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="w-75 ss-job-prfle-sec m-auto p-5">
                        <h3 class="ss-color-pink font-weight-bold">Application stages</h3>
                        <canvas id="recruiterStats"></canvas>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="side-ads-container">
                        <div id="promenade"></div>

                        <script>
                            (function(cfg) {
                                var script = document.createElement('script');
                                script.async = true;
                                script.src = 'https://api.boardwalk.marketing/promenade/loader/?pid=' + cfg.pid + '&role=' + cfg.role;
                                document.head.appendChild(script);
                                window.boardwalk = cfg;
                            }({
                                pid: '23d64e3b-185b-442b-b6d7-75839d66e308',
                                role: 'path',
                                root: 'promenade',
                                stylesheets: ['https://cdn1.boardwalk.marketing/css/pathcss-light-column-list.css'],
                            }))
                        </script>
                    </div>
                </div>
            </div>
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
    @include('partials.flashMsg')


    <script>
        let yValues = <?php echo json_encode($statusCounts); ?>;
        //let yValues = values;
        const ctx = document.getElementById('recruiterStats');

        let max = Math.max(...yValues);

        const xValues = ['New', 'Screening', 'Submitted', 'Offered', 'Onboarding', 'Cleared', 'Working'];


        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: xValues,
                datasets: [{
                    data: yValues,
                    backgroundColor: [
                        '#A8DFF1',
                        '#AED2F9',
                        '#FF6370',
                        '#73B0CD',
                        '#66B2FF',
                        '#C292D0',
                        '#66BBBB',
                    ]

                }]
            },

            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: ""
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: max,
                            stepSize: 1
                        }
                    }]
                },
            }
        });
    </script>

</body>

</html>

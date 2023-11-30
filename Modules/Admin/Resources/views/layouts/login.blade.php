<!DOCTYPE html>
<html lang="en">
  <head>
    <script type="text/javascript">
        var full_path = '<?= url('/admin'); ?>';
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{env('PROJECT_NAME', 'Goodwork')}}</title>

    <!-- Bootstrap -->
    <link href="{{URL::asset('backend/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{URL::asset('backend/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{URL::asset('backend/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{URL::asset('backend/vendors/animate.css/animate.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{URL::asset('backend/custom/css/custom.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        @yield('content')
      </div>
    </div>
    <!-- jQuery -->
    <script src="{{URL::asset('backend/vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Notie alert -->
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    <!-- Custom -->
    <script src="{{URL::asset('backend/custom/js/profile.js')}}"></script>
    <script src="{{URL::asset('backend/custom/js/script.js')}}"></script>
    @include('admin::partials.flashMsg')
  </body>
</html>

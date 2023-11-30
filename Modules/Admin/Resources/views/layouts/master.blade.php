<!DOCTYPE html>
<html lang="en">
  <head>
    <script type="text/javascript">
        var full_path = '<?= url('/') . '/admin/'; ?>';
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{URL::asset('backend/assets/images/favicon.ico')}}" type="image/ico" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{env('PROJECT_NAME', 'Goodwork')}}</title>

    <!-- Bootstrap -->
    <link href="{{URL::asset('backend/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{URL::asset('backend/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{URL::asset('backend/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{URL::asset('backend/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{URL::asset('backend/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{URL::asset('backend/vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{URL::asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{URL::asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('backend/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('backend/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('backend/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

    <!-- bootstrap-wysiwyg -->
	<link href="{{URL::asset('backend/vendors/google-code-prettify/bin/prettify.min.css')}}" rel="stylesheet">
    <!-- Select2 -->
	<link href="{{URL::asset('backend/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
	<!-- Switchery -->
	<link href="{{URL::asset('backend/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
	<!-- starrr -->
	<link href="{{URL::asset('backend/vendors/starrr/dist/starrr.css')}}" rel="stylesheet">

    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
    <!-- Custom Theme Style -->
    <link href="{{URL::asset('backend/custom/css/custom.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('backend/custom/css/custom.css')}}" rel="stylesheet">
    @yield('css')

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">

        {{-- Sidebar --}}
        @include('admin::partials.sidebar')

        <!-- top navigation -->
        @include('admin::partials.header')
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        @include('admin::partials.footer')
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{URL::asset('backend/vendors/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{URL::asset('backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{URL::asset('backend/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script src="{{URL::asset('backend/vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script src="{{URL::asset('backend/vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- gauge.js -->
    <script src="{{URL::asset('backend/vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{URL::asset('backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{URL::asset('backend/vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Skycons -->
    <script src="{{URL::asset('backend/vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script src="{{URL::asset('backend/vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/Flot/jquery.flot.resize.js')}}"></script>
    <!-- Flot plugins -->
    <script src="{{URL::asset('backend/vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script src="{{URL::asset('backend/vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{URL::asset('backend/vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{URL::asset('backend/vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- Datatables -->
    <script src="{{URL::asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/pdfmake/build/vfs_fonts.js')}}"></script>

    <!-- morris.js -->
    <script src="{{URL::asset('backend/vendors/raphael/raphael.min.js')}}"></script>
    <script src="{{URL::asset('backend/vendors/morris.js/morris.min.js')}}"></script>

    <!-- bootstrap-wysiwyg -->
	<script src="{{URL::asset('backend/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}}"></script>
	<script src="{{URL::asset('backend/vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
	<script src="{{URL::asset('backend/vendors/google-code-prettify/src/prettify.js')}}"></script>
	<!-- jQuery Tags Input -->
	{{-- <script src="{{URL::asset('backend/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
	<!-- Switchery -->
	<script src="{{URL::asset('backend/vendors/switchery/dist/switchery.min.js')}}"></script>
	<!-- Select2 -->
	<script src="{{URL::asset('backend/vendors/select2/dist/js/select2.full.min.js')}}"></script>
	<!-- Parsley -->
	<script src="{{URL::asset('backend/vendors/parsleyjs/dist/parsley.min.js')}}"></script>
	<!-- Autosize -->
	<script src="{{URL::asset('backend/vendors/autosize/dist/autosize.min.js')}}"></script>
	<!-- jQuery autocomplete -->
	<script src="{{URL::asset('backend/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js')}}"></script>
	<!-- starrr -->
	<script src="{{URL::asset('backend/vendors/starrr/dist/starrr.js')}}"></script>

    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    {{-- <script type="text/javascript" src="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script> --}}
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    {{-- CK editor --}}
    <script src="{{URL::asset('backend/vendors/ckeditor/ckeditor.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{URL::asset('backend/custom/js/custom.min.js')}}"></script>
    <!-- Custom Scripts -->
    <script src="{{URL::asset('backend/custom/js/script.js')}}"></script>
    <script src="{{URL::asset('backend/custom/js/profile.js')}}"></script>
    @yield('js')
    @include('admin::partials.flashMsg')
  </body>
</html>

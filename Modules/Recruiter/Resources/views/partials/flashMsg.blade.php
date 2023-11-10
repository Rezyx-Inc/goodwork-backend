@if(Session::has('success'))
<input type="hidden" id="success_msg" value="{{ Session::get('success') }}"/>
@php
Session::forget('success');
@endphp
<script>
    var success_msg = $('#success_msg').val();
    $(document).ready(function () {
        notie.alert({
            type: 'success',
            text: '<i class="fa fa-check"></i> ' + success_msg,
            time: 6
        });
    });
</script>

@endif

@if(Session::has('error'))
<input type="hidden" id="error_msg" value="{{ Session::get('error') }}"/>
@php
Session::forget('error');
@endphp
<script>
    var error_msg = $('#error_msg').val();
    $(document).ready(function () {
        notie.alert({
            type: 'error',
            text: '<i class="fa fa-times"></i> ' + error_msg,
            time: 6
        });
    });
</script>

@endif
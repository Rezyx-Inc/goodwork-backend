<form onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
    <!-- first form slide Basic Information -->

    <div class=" page slide-page">
        {{-- <div class="page slide-page"> --}}
        @include('worker::dashboard.profile.settings.personal_info')
        <!-- end first form slide Basic Information -->

        <!-- second form slide Professional Information -->
        @include('worker::dashboard.profile.settings.professional_info')
        <!-- end second form slide (Profession, Slide) -->

        <!-- Third form slide Document management -->
        {{-- <div class="page slide-page ">
            @include('worker::dashboard.profile.documents_info')
        </div> --}}
        <!-- end Third form slide Document management -->
        <div>
        </div>
</form>


<style>
    /* for collapse */

    .btn.first-collapse,
    .btn.first-collapse:hover,
    .btn.first-collapse:focus,
    .btn.first-collapse:active {
        background-color: #fff8fd;
        color: rgb(65, 41, 57);
        font-size: 14px;
        font-family: 'Neue Kabel';
        font-style: normal;
        width: 100%;
        background: #FFEEEF;
    }
</style>

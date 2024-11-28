<form onsubmit="return false;" method="post" action="{{ route('update-worker-profile') }}">
    {{-- <form> --}}
    @csrf
    <!-- first form slide Basic Information -->
    <div class="page slide-page">
        @include('worker::dashboard.profile.personal_info')
    </div>
    <!-- end first form slide Basic Information -->

    <!-- second form slide Professional Information -->
    <div class="page slide-page">
        @include('worker::dashboard.profile.professional_info')
    </div>
    <!-- end second form slide (Profession, Slide) -->

    <!-- Third form slide Document management -->
    <div class="page slide-page ">
        @include('worker::dashboard.profile.documents_info')
    </div>
    <!-- end Third form slide Document management -->
    <div>
    </div>
</form>

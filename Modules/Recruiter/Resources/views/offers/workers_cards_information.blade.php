@if ($noApplications)
    <div class="text-center"><span>No Application</span></div>
@else
@foreach ($offerData as $data)
<div id="{{$data['workerUserId']}}" class="ss-job-prfle-sec cards"  onclick="toggleActiveClass('{{$data['workerUserId']}}'); getOffersOfEachWorker('{{$data['type']}}','{{ $data['workerUserId'] }}')">
        <div class="ss-job-id-no-name">
            <ul>
                <li class="w-50">
                    <span class="mb-3">{{ $data['workerUserId'] }}</span>
                </li>
                <li class="w-50">
                    <p>Recently Added</p>
                </li>
            </ul>
        </div>
        <ul>
            <li>
                <img width="50px" height="50px" src="{{ URL::asset('public/images/nurses/profile/' . $data['image']) }}" onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-pic-big.png') }}';" style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
            </li>
            <li>
                <h6 class="job-title">{{ $data['firstName'] }} {{ $data['lastName'] }}</h6>
            </li>
        </ul>
        <ul class="row ss-expl-applicion-ul2 worker-cards">
            @if ($data['hourlyPayRate'])
                <li class="col-7" ><a href="#">Hourly pay rate {{ $data['hourlyPayRate'] }} $</a></li>
            @endif
            @if ($data['city'] && $data['state'])
                <li class="col-5"><a href="#">{{ $data['city'] }}, {{ $data['state'] }}</a></li>
            @endif
            @if ($data['muSpecialty'])
                <li class="col-6"><a href="#">{{ $data['muSpecialty'] }}</a></li>
            @endif
            @if ($data['specialty'])
                <li class="col-6"><a href="#">{{ $data['specialty'] }}</a></li>
            @endif
        </ul>
</div>
@endforeach
@endif

<script>
    function toggleActiveClass(workerUserId) {
    var element = document.getElementById(workerUserId);
    var allElements = document.getElementsByClassName('cards');
    for (var i = 0; i < allElements.length; i++) {
        if (allElements[i].classList.contains('active')) {
            allElements[i].classList.remove('active');
        }
    }
    if (!element.classList.contains('active')) {
        element.classList.add('active');
    }
}
</script>

<style>
    .worker-cards{
        gap:0px !important;
    }
</style>

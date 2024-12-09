@if ($noApplications)
    <div class="text-center"><span>No Application</span></div>
@else
    <div class="row" style="display: flex ; justify-content: space-between;">
        @foreach ($offerData as $data)
            <div id="{{ $data['workerUserId'] }}-{{ $data['offerId']
             }}" class="ss-job-prfle-sec cards col-lg-6 row"
                onclick="toggleActiveClass('{{ $data['workerUserId'] }}-{{ $data['offerId']}}'); applicationStatusToScreening('Screening','{{ $data['workerUserId'] }}', '{{ $data['offerId'] }}');">
                <div style="col-10">
                <div class="ss-job-id-no-name">
                    <ul>
                        <li class="w-50">
                            <span class="mb-3">{{ $data['workerUserId'] }}</span>
                        </li>
                        <li class="w-50">
                            <p>{{ gettype($data['recently_added']) == 'boolean' ? 'Recently Added' : $data['recently_added'] }}</p>
                        </li>
                    </ul>
                </div>
                <ul>
                    <li>
                        <img width="50px" height="50px"
                            src="{{ URL::asset('public/images/nurses/profile/' . $data['image']) }}"
                            onerror="this.onerror=null;this.src='{{ URL::asset('frontend/img/profile-pic-big.png') }}';"
                            style="object-fit: cover;" class="rounded-3" alt="Profile Picture">
                    </li>
                    <li>
                        <h6 class="job-title">{{ $data['firstName'] }} {{ $data['lastName'] }}</h6>
                    </li>
                </ul>

                <ul class="row ss-expl-applicion-ul2 worker-cards">
                    @if ($data['JobId'])
                        <li class="col-5"><a href="#">{{ $data['JobId'] }} </a></li>
                    @endif
                    @if ($data['city'] && $data['state'])
                        <li class="col-7"><a href="#">{{ $data['city'] }}, {{ $data['state'] }}</a></li>
                    @endif
                    @if ($data['profession'] && $data['specialty'])
                        <li class="col-12"><a href="#">{{ $data['profession'] }} , {{ $data['specialty'] }}</a>
                        </li>
                    @elseif ($data['specialty'])
                        <li class="col-6"><a href="#">{{ $data['specialty'] }}</a></li>
                    @elseif ($data['profession'])
                        <li class="col-6"><a href="#">{{ $data['profession'] }}</a></li>
                    @endif
                    @if ($data['facilityName'])
                        <li class="col-6"><a href="#">{{ $data['facilityName'] }}</a></li>
                    @endif
                    @if ($data['preferred_shift_duration'])
                        <li class="col-6"><a href="#">{{ $data['preferred_shift_duration'] }}</a></li>
                    @endif

                </ul>
                </div>
            </div>
        @endforeach
    </div>
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
    .worker-cards {
        gap: 0px !important;
    }
</style>

@extends('layouts.dashboard')
@section('mytitle', 'My Profile')
@section('content')
<!--Main layout-->
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="container">

  <div class="ss-my-work-jorny-btn-dv">
    <div class="row">
      <div class="col-lg-6">
        <h2>My Work Journey</h2>
      </div>

      <div class="col-lg-6">
        <div class="ss-my-work-tab-div">
            {{-- <ul onclick="myFunction(event)" id='navList'> --}}
            <ul>
                <li><a href="{{route('my-work-journey')}}" class="ss-saved-btn {{ ( request()->route()->getName() == 'my-work-journey' ) ? 'active' :'' }}">Saved</a></li>
                <li><a href="{{route('applied-jobs')}}" class="ss-applied-btn {{ ( request()->route()->getName() == 'applied-jobs' ) ? 'active' :'' }}">Applied</a></li>
                <li><a href="{{route('offered-jobs')}}" class="ss-offered-btn {{ ( request()->route()->getName() == 'offered-jobs' ) ? 'active' :'' }}">Offered</a></li>
                <li><a href="{{route('hired-jobs')}}" class="ss-hired-btn {{ ( request()->route()->getName() == 'hired-jobs' ) ? 'active' :'' }}">Hired</a></li>
                <li><a href="{{route('past-jobs')}}" class="ss-past-btn {{ ( request()->route()->getName() == 'past-jobs' ) ? 'active' :'' }}">Past</a></li>
            </ul>
        </div>
      </div>
    </div>
  </div>


  <!--------------my work journey saved---------------->

    <div class="ss-my-work-jorny-sved-div">
        <div class="row">
            <div class="col-lg-5 ss-displ-flex">
                <div class="ss-mywrk-jrny-left-dv">
                    <div class="ss-jb-dtl-icon-ul">
                        <h5>{{ucfirst($type)}}</h5>
                    </div>


                    <!-------->
                    @forelse($jobs as $k=>$j)
                    <div class="ss-job-prfle-sec job-list" data-id="{{$j->id}}" data-type="{{$type}}" onclick="fetch_job_content(this)">
                        <p>{{$j->job_type}} <span>+{{$j->getOfferCount()}} Applied</span></p>
                        <h4>{{$j->job_name}}</h4>
                        <h6>{{$j->facility->name ?? 'NA'}}</h6>
                        <ul>
                        <li><a href="#"><img src="{{URL::asset('public/frontend/img/location.png')}}"> {{$j->job_city}}, {{$j->job_state}}</a></li>
                        <li><a href="#"><img src="{{URL::asset('public/frontend/img/calendar.png')}}"> {{$j->preferred_assignment_duration}} wks</a></li>
                        <li><a href="#"><img src="{{URL::asset('public/frontend/img/dollarcircle.png')}}"> ${{$j->weekly_pay}}/wk</a></li>
                        </ul>
                        <h5>Recently Added</h5>
                        @if($type == 'saved')
                        <a  href="javascript:void(0)" data-id="{{$j->id}}" onclick="save_jobs(this, true)" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('public/frontend/img/bookmark.png')}}" /></a>
                        @endif
                    </div>
                    @empty
                    @endforelse

                    {{-- <div class="ss-job-prfle-sec my-work-sved-job-div2">
                        <p>Travel <span>+50 Applied</span></p>
                        <h4>Manager CRNA - Anesthesia</h4>
                        <h6>Medical Solutions Recruiter</h6>
                        <ul>
                        <li><a href="#"><img src="img/location.png"> Los Angeles, CA</a></li>
                        <li><a href="#"><img src="img/calendar.png"> 10 wks</a></li>
                        <li><a href="#"><img src="img/dollarcircle.png"> 2500/wk</a></li>
                        </ul>
                        <h5>Recently Added</h5>
                        <a href="#" class="ss-jb-prfl-save-ico"><img src="img/bookmark.png" /></a>
                    </div> --}}

                </div>
            </div>

            <!-----JOB CONTENT---->
            <div class="col-lg-7">

                <div class="ss-journy-svd-jbdtl-dv job-content"></div>

            </div>
      </div>
    </div>

  <!--------------my work journey saved---------------->

    </div>

  </main>
@stop

@section('js')

<script>
     function myFunction(e) {
      if (document.querySelector('#navList a.active') !== null) {
        document.querySelector('#navList a.active').classList.remove('active');
      }
      e.target.className = "active";
    }
</script>

<script>
    $(document).ready(function(){
        $('.job-list')[0].click('active');
    });

    function fetch_job_content(obj)
    {
        if (!$(obj).hasClass('active')) {
            if ($(obj).data('type') !== 'counter') {
                $('.job-list').removeClass('active')
            }
            ajaxindicatorstart();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: full_path+'fetch-job-content',
                type: 'POST',
                dataType: 'json',
                // processData: false,
                // contentType: false,
                data: {
                    jid: $(obj).data('id'),
                    type: $(obj).data('type')
                },
                success: function (resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                    if (resp.success) {

                        $('.job-content').html(resp.content);
                        $(obj).addClass('active')
                    }
                },
                error: function (resp) {
                    console.log(resp);
                    ajaxindicatorstop();
                }
            });
        }
    }
</script>
@stop

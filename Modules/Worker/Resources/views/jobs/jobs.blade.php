@extends('worker::layouts.main')
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
                        <li><a href="#"><img src="{{URL::asset('frontend/img/location.png')}}"> {{$j->job_city}}, {{$j->job_state}}</a></li>
                        <li><a href="#"><img src="{{URL::asset('frontend/img/calendar.png')}}"> {{$j->preferred_assignment_duration}} wks</a></li>
                        <li><a href="#"><img src="{{URL::asset('frontend/img/dollarcircle.png')}}"> ${{$j->weekly_pay}}/wk</a></li>
                        </ul>
                        <h5>Recently Added</h5>
                        @if($type == 'saved')
                        <a  href="javascript:void(0)" data-id="{{$j->id}}" onclick="save_jobs(this, true)" class="ss-jb-prfl-save-ico"><img src="{{URL::asset('frontend/img/bookmark.png')}}" /></a>
                        @endif
                    </div>
                    @empty
                    @endforelse

                    

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



    <div class="modal fade ss-jb-dtl-pops-mn-dv" id="stripe_modal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="ss-pop-cls-vbtn">
                        <button type="button" class="btn-close" data-target="#stripe_modal" onclick="close_modal(this)"
                            aria-label="Close"></button>
                    </div>
                    <div class="d-flex justify-content-center align-items-center" ><h3 class="col-6">Payment Method</h3></div>
                    <div class="modal-body" style="padding:0px !important;">

                        <div
                        class="ss-prsn-form-btn-sec row col-12 d-flex justify-content-center align-items-center">
                        
                        <button type="text" class="ss-prsnl-save-btn" id="AddStripe">
                            Add Stripe
                        </button>
                    </div>
                    </div>

                </div>
            </div>
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

    const AddStripe = document.getElementById("AddStripe");

AddStripe.addEventListener("click", function(event) {
            event.preventDefault();

            $('#loading').removeClass('d-none');
            $('#send_ticket').addClass('d-none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/worker/add-stripe-account',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    access: true,
                }),
                success: function(resp) {
                    console.log(resp);
                    if (resp.status) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Redirecting ...',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        window.location.href = resp.account_link;
                    } else {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Client Exists',
                            time: 5
                        });
                        $('#loading').addClass('d-none');
                        $('#send_ticket').removeClass('d-none');
                        close_stripe_modal();
                        //window.location.href = resp.portal_link;
                    }
                }
            });
        });

        function open_stripe_modal() {
            let name, title, modal;

            name = 'stripe';
            title = 'add payment method';
            modal = '#stripe_modal';
            
           

            $(modal).modal('show');
        }


        function close_stripe_modal() {
            let target = '#stripe_modal';
            $(target).modal('hide');
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
                url: full_path + 'fetch-job-content',
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

    function accept_job_offer(obj){
       
            ajaxindicatorstart();
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: full_path+'worker/accept-offer',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        offer_id: $(obj).data('offer_id')
                    },
                    success: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                        if(resp.success == true){
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i>' + resp.message,
                                time: 5
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }else{
                            open_stripe_modal();
                            notie.alert({
                                type: 'error',
                                text: '<i class="fa fa-times"></i>' + resp.message,
                                time: 5
                            });

                        }

                    },
                    error: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                    }
                });

        }


        function reject_job_offer(obj){
            ajaxindicatorstart();
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: full_path+'worker/reject-offer',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        offer_id: $(obj).data('offer_id')
                    },
                    success: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                            notie.alert({
                                type: 'success',
                                text: '<i class="fa fa-check"></i>' + resp.msg,
                                time: 5
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        
                    },
                    error: function (resp) {
                        console.log(resp);
                        ajaxindicatorstop();
                    }
                });

        }
</script>
@stop

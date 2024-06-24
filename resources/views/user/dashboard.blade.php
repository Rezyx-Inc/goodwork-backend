@extends('layouts.dashboard')
@section('mytitle', ' Dashboard')
@section('content')
<main style="padding-top: 170px" class="ss-main-body-sec">
    <div class="container">

      <!--------Ghraph area------->
        <div class="ss-home-graph-main-sec">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ss-home-graph-div1">
                        <img src="{{URL::asset('frontend/img/home-graph-1.png')}}" />
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ss-home-graph-div2">
                        <img src="{{URL::asset('frontend/img/home-graph-2.png')}}" />
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="ss-home-graph-div3">
                        <img src="{{URL::asset('frontend/img/home-graph-3.png')}}" />
                    </div>
                </div>

            </div>
        </div>

    </div>

</main>
@stop

@section('js')

@stop

@extends('admin::layouts.master')

@section('content')

<!-- top tiles -->
<div class="ss-dash-cont-mn-div">
<div class="container">
<div class="row">
        <div class="col-md-4 col-sm-4">
            <div class="ss-dash-small-cntdvs">
            <ul>
            <li><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/dash-user-nurse.png"/></li>
             <li>   
            <span class="count_top"> Total Workers</span>
            <div class="count">{{$total_workers_active}}</div>
            <span class="count_bottom"><i class="green"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-up.png"/> 4% </i> From last Week</span>
        </li>
    </ul>
        </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <div class="ss-dash-small-cntdvs">
            <ul>
            <li class="ss-dash-blue-bg"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/total-recruiters-icon.png"/></li>

           <li> <span class="count_top">  Total Recruiters</span>
            <div class="count">{{$total_recruiters_active}}</div>
            <span class="count_bottom"><i class="green"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-up.png"/>3% </i> From last Week</span>
        </li>
        </ul>
        </div>
        </div>

        <div class="col-md-4 col-sm-4  tile_stats_count">
        <div class="ss-dash-small-cntdvs">
            <ul>
            <li class="ss-dash-green-bg"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/total-jobs-icon.png"/></li>
            <li><span class="count_top"> Total Jobs</span>
            <div class="count green">{{$total_job_active}}</div>
            <span class="count_bottom"><i class="green"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-up.png"/>34% </i> From last Week</span></li>
        </ul>
        </div>
        </div>

        <div class="col-md-4 col-sm-4">
        <div class="ss-dash-small-cntdvs">
            <ul>
                <li><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/dash-user-nurse.png"/></li>
            <li><span class="count_top"> Total Females</span>
            <div class="count">4,567</div>
            <span class="count_bottom"><i class="red"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-down.png"/>12% </i> From last Week</span>
        </li>
        </ul>
        </div>
        </div>
        <div class="col-md-4 col-sm-4 ">
             <div class="ss-dash-small-cntdvs">
                <ul>
                    <li class="ss-dash-blue-bg"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/total-collections-icon.png"/></li>
            <li><span class="count_top"> Total Collections</span>
            <div class="count">2,315</div>
            <span class="count_bottom"><i class="green"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-up.png"/>34% </i> From last Week</span></li>
        </ul>
        </div>
        </div>
        <div class="col-md-4 col-sm-4">
             <div class="ss-dash-small-cntdvs">
                <ul>
                    <li class="ss-dash-green-bg"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/total-facility-icon.png"/></li>
           <li> <span class="count_top"> Total Connections</span>
            <div class="count">7,325</div>
            <span class="count_bottom"><i class="green"><img src="http://localhost/Goodwork-admin-newtheme/public\backend\assets\images/direct-up.png"/>34% </i> From last Week</span></li>
        </ul>
        </div>
        </div>
    </div>
</div>
</div>
<!-- /top tiles -->
@stop
@section('js')
@stop

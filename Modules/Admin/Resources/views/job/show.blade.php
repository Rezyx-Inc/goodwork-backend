@extends('admin::layouts.master')
@section('css')
    <style>
        .show-tag {
            display: block;
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            /* border: 1px solid #ced4da;
                border-radius: 0.25rem;
                transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out; */
        }
    </style>
@stop
@section('content')

    <!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>{{ $model->job_name ?? 'N/A' }}</h3>
            </div>

            {{-- <div class="title_right">
            <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div> --}}
        </div>
        <div class="clearfix"></div>

        <div class="col-md-12">

            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
                        aria-controls="details" aria-selected="true">Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#offers" role="tab"
                        aria-controls="offers" aria-selected="false">Offers ({{ $model->offers->count() }})</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_content">
                                    <br />
                                    <form class="form-horizontal form-label-left">

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Job Name :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->job_name ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Type :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ !empty($model->type) ? $model->type : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Terms</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ !empty($model->terms) ? $model->terms : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Recruiter :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ !empty($model->recruiter) ? $model->recruiter->getFullNameAttribute() : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Profession :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->profession ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Speciality and
                                                Experience</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                @php
                                                    $specialty = [];
                                                    $experience = [];
                                                    if (
                                                        !empty($model->preferred_specialty) &&
                                                        !empty($model->preferred_experience)
                                                    ) {
                                                        $specialty = explode(',', $model->preferred_specialty);
                                                        $experience = explode(',', $model->preferred_experience);
                                                    }
                                                @endphp
                                                @if (!count($specialty))
                                                    <p class="show-tag">N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if (count($specialty))
                                            <div class="item form-group d-flex justify-content-center">
                                                <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                <div class="col-md-1 col-sm-1">
                                                    <label class="col-form-label label-align"><b>#</b> </label>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <label class="col-form-label label-align"> <b>Speciality</b></label>
                                                </div>
                                                <div class="col-md-2 col-sm-2">
                                                    <label class="col-form-label label-align"> <b>Experience</b> </label>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="speciality-content">
                                            @for ($i = 0; $i < count($specialty); $i++)
                                                <div class="item form-group d-flex justify-content-center">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                    <div class="col-md-1 col-sm-1">
                                                        <label class="col-form-label label-align">
                                                            {{ $i + 1 }}</label>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="col-form-label label-align">
                                                            {{ $specialty[$i] }}</label>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2">
                                                        <label class="col-form-label label-align">{{ $experience[$i] }}
                                                            (Years) </label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Professional
                                                Licensure</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->jobLocation->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Is Compact :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->compact == '1' ? 'Yes' : 'No' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Vaccination &
                                                Immunization: </label>
                                            <div class="col-md-6 col-sm-6 ">
                                                @php
                                                    $vaccination = [];
                                                    if (!empty($model->vaccinations)) {
                                                        $vaccination = explode(',', $model->vaccinations);
                                                    }
                                                @endphp
                                                @if (!count($vaccination))
                                                    <p class="show-tag">N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if (count($vaccination))
                                            <div class="item form-group d-flex justify-content-center">
                                                <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                <div class="col-md-1 col-sm-1">
                                                    <label class="col-form-label label-align"><b>#</b> </label>
                                                </div>
                                                <div class="col-md-5 col-sm-5">
                                                    <label class="col-form-label label-align"> <b>Name of
                                                            Vaccination</b></label>
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            @for ($i = 0; $i < count($vaccination); $i++)
                                                <div class="item form-group d-flex justify-content-center">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                    <div class="col-md-1 col-sm-1 ">
                                                        <label class="col-form-label label-align"> {{ $i + 1 }}.
                                                        </label>
                                                    </div>
                                                    <div class="col-md-5 col-sm-5">
                                                        <label class="col-form-label label-align">
                                                            {{ $vaccination[$i] }}</label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Number of
                                                refrences</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->number_of_references ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Min Title of
                                                Reference :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->min_title_of_reference ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Recency of
                                                Reference :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->recency_of_reference ? $model->recency_of_reference . ' Month' : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>


                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Certifications:
                                            </label>
                                            <div class="col-md-6 col-sm-6 ">
                                                @php
                                                    $certifications = [];
                                                    if (!empty($model->certificate)) {
                                                        $certifications = explode(',', $model->certificate);
                                                    }
                                                @endphp
                                                @if (!count($certifications))
                                                    <p class="show-tag">N/A</p>
                                                @endif
                                            </div>
                                        </div>

                                        @if (count($certifications))
                                            <div class="item form-group d-flex justify-content-center">
                                                <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                <div class="col-md-1 col-sm-1">
                                                    <label class="col-form-label label-align"><b>#</b> </label>
                                                </div>
                                                <div class="col-md-5 col-sm-5">
                                                    <label class="col-form-label label-align"> <b>Name of
                                                            Certification</b></label>
                                                </div>
                                            </div>
                                        @endif

                                        <div>
                                            @for ($i = 0; $i < count($certifications); $i++)
                                                <div class="item form-group d-flex justify-content-center">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align"></label>
                                                    <div class="col-md-1 col-sm-1 ">
                                                        <label class="col-form-label label-align"> {{ $i + 1 }}.
                                                        </label>
                                                    </div>
                                                    <div class="col-md-5 col-sm-5">
                                                        <label class="col-form-label label-align">
                                                            {{ $certifications[$i] }}</label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Skills :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ str_replace(',', ', ', $model->skills) ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Urgency</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->urgency ? $model->urgency : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"># Of Positions
                                                Available :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->position_available ? $model->position_available : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">MSP :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->msp ? $model->msp : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">VMS</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->vms ? $model->vms : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> # Of Submissions
                                                In VMS :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->submission_of_vms ? $model->submission_of_vms : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Block
                                                Scheduling:</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->block_scheduling ? $model->block_scheduling : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Float Requirements
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->float_requirement ? $model->float_requirement : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Shift
                                                Cancellation Policy :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->facility_shift_cancelation_policy ? $model->facility_shift_cancelation_policy : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Contract
                                                termination policy</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->contract_termination_policy ? $model->contract_termination_policy : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Traveler Distance
                                                from Facility :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->traveler_distance_from_facility ? $model->traveler_distance_from_facility : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Facility :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->facility_id ? $model->facility_id : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility's Parent
                                                System :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->facilitys_parent_system ? $model->facilitys_parent_system : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Facility Average
                                                Rating</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->facility_average_rating ? $model->facility_average_rating : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Recruiter Average
                                                Rating :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->recruiter_average_rating ? $model->recruiter_average_rating : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Organization
                                                Average
                                                Rating</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->organization_average_rating ? $model->organization_average_rating : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Clinical Setting
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->clinical_setting ? $model->clinical_setting : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Patient Ratio
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->Patient_ratio ? $model->Patient_ratio : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">FieldLabel</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->job_name ? $model->job_name : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">EMR :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->emr ? $model->emr : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Unit</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->unit ? $model->unit : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Department
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->Department ? $model->Department : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align"> Bed Size :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->Bed_Size ? $model->Bed_Size : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Trauma
                                                Level</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->Trauma_Level ? $model->Trauma_Level : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Scrub Color
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->scrub_color ? $model->scrub_color : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">RTO</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->rto ? $model->rto : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Prefered Shift
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->preferred_shift ? $model->preferred_shift : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Guaranteed Hours
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->guaranteed_hours ? $model->guaranteed_hours : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Hours/Shift</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->hours_shift ? $model->hours_shift : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Shifts/Week
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->weeks_shift ? $model->weeks_shift : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label
                                                class="col-form-label col-md-3 col-sm-3 label-align">Weeks/Assignment</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->preferred_assignment_duration ? $model->preferred_assignment_duration : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Referral Bonus($)
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->referral_bonus ? $model->referral_bonus : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Sign On Bonus($)
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->sign_on_bonus ? $model->sign_on_bonus : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Completion
                                                Bonus($)</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->completion_bonus ? $model->completion_bonus : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Extension Bonus($)
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->extension_bonus ? $model->extension_bonus : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Other Bonus</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->other_bonus ? $model->other_bonus : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">401K :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->four_zero_one_k ? $model->four_zero_one_k : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Health Insurance
                                                :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->health_insaurance ? $model->health_insaurance : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Dental</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->dental ? $model->dental : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Vision :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->vision ? $model->vision : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Actual Hourly
                                                Rate($)</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->actual_hourly_rate ? $model->actual_hourly_rate : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Feels Like $/Hour
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->feels_like_per_hour ? $model->feels_like_per_hour : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Overtime :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->overtime ? $model->overtime : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Holiday</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->holiday ? $model->holiday : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">On Call :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->on_call ? $model->on_call : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Orientation
                                                Rate($)</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->orientation_rate ? $model->orientation_rate : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Taxable
                                                Amount($) :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->weekly_taxable_amount ? $model->weekly_taxable_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Weekly Non-Taxable
                                                Amount($) :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->weekly_non_taxable_amount ? $model->weekly_non_taxable_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Organization Weekly
                                                Amount($)abel</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->organization_weekly_amount ? $model->organization_weekly_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Goodwork Weekly
                                                Amount($) :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->goodwork_weekly_amount ? $model->goodwork_weekly_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Organization
                                                Amount($)</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->total_organization_amount ? $model->total_organization_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Goodwork
                                                Amount($) :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->total_goodwork_amount ? $model->total_goodwork_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Total Contract
                                                Amount($) :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ $model->total_contract_amount ? $model->total_contract_amount : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Job State</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ !empty($model->jobState->name) ? $model->jobState->name : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Job City :</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">
                                                    {{ !empty($model->jobCity->name) ? $model->jobCity->name : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Description</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->description ? $model->description : 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Starting date
                                                :</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">
                                                    {{ $model->start_date ? Carbon\Carbon::parse($model->start_date)->format('d M Y') : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">As Soon As</label>
                                            <div class="col-md-6 col-sm-6 ">
                                                <p class="show-tag">{{ $model->job_name == '1' ? 'Yes' : 'No' }}</p>
                                            </div>
                                        </div>

                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status</label>
                                            <div class="col-md-6 col-sm-6">
                                                <p class="show-tag">{{ $model->active == '1' ? 'Active' : 'Inactive' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>
                                        <div class="item form-group">
                                            <div class="col-md-6 col-sm-6 offset-md-3">
                                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">Back</a>
                                                <a href="{{ route('jobs.edit', ['id' => $model->id]) }}"
                                                    class="btn btn-success">Edit</a>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="offers" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="x_panel">
                                <div class="x_content">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-box table-responsive">
                                                <p class="text-muted font-13 m-b-30">
                                                </p>
                                                <table id="listing-table"
                                                    class="table table-striped table-bordered bulk_action text-center"
                                                    style="width:100%">
                                                    <button class="text-danger float-right" id="delete"
                                                        type="button">Delete</button>
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="check_all">
                                                            </th>
                                                            <th>ID</th>
                                                            <th>Worker</th>
                                                            <th>Status</th>
                                                            <th>Applied on</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



    </div>
    <!-- /page content -->

@stop
@section('js')
    <!-- Datatable -->
    <script>
        $(function() {
            $('#listing-table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [
                    [4, "desc"]
                ],
                bSortable: true,
                bRetrieve: true,
                "aoColumnDefs": [{
                        "bSortable": false,
                        "aTargets": [0, 1, 2, 3, 5]
                    },
                    {
                        "bSearchable": false,
                        "aTargets": [0, 5]
                    }
                ],
                ajax: "{!! route('jobs.show', ['id' => $model->id]) !!}",
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false
                    },
                    // { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false},
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'worker',
                        name: 'worker'
                    },
                    {
                        data: 'active',
                        name: 'active',
                        render: function(data, type, row) {
                            if (data == '0') {
                                return '<span class="label label-sm text-warning">Inactive</span>';
                            } else if (data == '1') {
                                return '<span class="label label-sm text-success">Active</span>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });
        });
    </script>
    <script>
        // check all
        $("#check_all").click(function() {
            if ($("#check_all").prop("checked")) {
                $("input[type=checkbox]").prop("checked", true);
                //or $(":checkbox").prop("checked",true);
            } else {
                $("input[type=checkbox]").prop("checked", false);
            }
        })
    </script>

    <!-- deleting offer -->
    <script>
        $(document).ready(function() {
            $('#delete').click(function() {
                var input = [];
                $(".ids:checked").each(function() {
                    input.push($(this).val());
                });
                if (input.length) {
                    $.confirm({
                        title: 'Delete Offer',
                        content: 'Are you sure?',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            confirm: {
                                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                                btnClass: 'btn-red',
                                action: function() {
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                                .attr('content')
                                        }
                                    });
                                    $.ajax({
                                        url: '{{ route('delete-job-offer') }}',
                                        // url:url,
                                        type: 'POST',
                                        dataType: 'json',
                                        // processData: false,
                                        // contentType: false,
                                        data: {
                                            ids: input
                                        },
                                        success: function(data) {
                                            console.log(data);
                                            if (data.success) {
                                                notie.alert({
                                                    type: 'success',
                                                    text: '<i class="fa fa-check"></i> ' +
                                                        data.msg,
                                                    time: 3
                                                });
                                                $('#listing-table').DataTable().ajax
                                                    .reload(null, false);
                                            } else {
                                                notie.alert({
                                                    type: 'error',
                                                    text: '<i class="fa fa-check"></i> ' +
                                                        data.msg,
                                                    time: 5
                                                });
                                            }
                                        },
                                        error: function(resp) {
                                            console.log(resp);
                                        }
                                    });
                                }
                            },
                            cancel: function() {}
                        }
                    })
                } else {
                    notie.alert({
                        type: 'error',
                        text: '<i class="fa fa-check"></i> Please select at least one record.',
                        time: 5
                    });
                }
            });

        });
    </script>

@stop

@extends('recruiter::layouts.main')

@section('content')
<main style="padding-top: 120px" class="ss-main-body-sec">
    <div class="container">
        <div class="ss-opport-mngr-mn-div-sc">
            <div class="ss-opport-mngr-hedr">
                <h4>Create Opportunity</h4>
            </div>
            <div class="ss-account-form-lft-1 border-0 p-0 m-0 bg-transparent">
                <div class="row">
                    <form class="ss-emplor-form-sec" id="create-new-job">
                        <div class="row">
                            <div class="ss-form-group col-md-4">
                                <label>Job Name</label>
                                <input type="text" name="job_id" id="job_id" placeholder="Enter job id" class="d-none">
                                <input type="text" name="job_name" id="job_name" placeholder="Enter job name">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Type</label>
                                <select name="type" id="type">
                                    <option value="">Select Type</option>
                                    @if(isset($allKeywords['Type']))
                                    @foreach ($allKeywords['Type'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Terms</label>
                                <select name="terms" id="term">
                                    <option value="">Select Terms</option>
                                    @if(isset($allKeywords['Terms']))
                                    @foreach ($allKeywords['Terms'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                    <!-- <option value="contract">Contract</option>
                                    <option value="perm">Perm</option>
                                    <option value="shift">Shift</option>
                                    <option value="contract-to-prem">Contract to Prem</option> -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-12">
                                <label>Description</label>
                                <textarea name="description" id="description" placeholder="Enter Job Description" cols="30" rows="2"></textarea>
                                <!-- <input type="text" name="description" id="description" placeholder="Enter Job Description"> -->
                            </div>
                            <div class="ss-form-group col-md-6">
                                <label>Profession</label>
                                <select name="profession" id="profession" onchange="getSpecialitiesByProfession(this)">
                                    <option value="">Select Profession</option>
                                    @if(isset($allKeywords['Profession']))
                                    @foreach ($allKeywords['Profession'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                    <!-- <option value="rn">RN</option>
                                    <option value="allied-health-professional">Allied Health Professional</option>
                                    <option value="therapy">Therapy</option>
                                    <option value="lpn-lvn">LPN/LVN</option>
                                    <option value="nurse-practitioner">Nurse Practitioner</option> -->
                                </select>
                            </div>
                            <!-- job_state -->
                            <div class="ss-form-group col-md-6">
                                <label>Professional Licensure</label>
                                <select name="job_location" id="professional-licensure">
                                    <option value="">Select Professional Licensure</option>
                                    @if(isset($allusstate))
                                    @foreach ($allusstate as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                    @endif
                                    <!-- <option value="georgia">Georgia(GA)</option>
                                    <option value="california">California(CA)</option>
                                    <option value="kentucky">Kentucky(KY)</option> -->
                                </select>
                            </div>
                            <!-- <div class="ss-form-group col-md-12">
                                <label>Specialty and Experience</label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div id="all-experience">
                                            <div class="experience-inputs">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <select name="preferred_specialty" class="specialty mb-3" id="preferred_specialty">
                                                            <option value="">Select Specialty</option>
                                                            @if(isset($allKeywords['Speciality_old']))
                                                                @foreach ($allKeywords['Speciality_old'] as $value)
                                                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                                                @endforeach 
                                                            @endif 
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" class="mb-3" name="preferred_experience" id="preferred_experience" placeholder="Enter Experience in years">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" class="add-more-experience ss-bell-sec px-3 py-2 rounded">+</a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                <label>Specialty and Experience</label>
                                <div class="ss-speilty-exprnc-add-list speciality-content">

                                </div>
                                <ul>
                                    <li class="row w-100 p-0 m-0">
                                        <div class="col-md-6 ps-0">
                                            <select name="preferred_specialty" class="m-0" id="preferred_specialty">
                                                <option value="">Select Specialty</option>
                                                <!-- @if(isset($allKeywords['Speciality_old']))
                                                @foreach ($allKeywords['Speciality_old'] as $value)
                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                @endforeach
                                                @endif -->
                                            </select>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="number" name="preferred_experience" id="preferred_experience" placeholder="Enter Experience in years">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="add_speciality(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- <li>
                                    </li> -->
                                </ul>
                            </div>
                            <!-- <div class="ss-form-group col-md-12" id="add-more-vacc-immu">
                                <label>Vaccinations & Immunizations name</label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <select name="vaccinations" class="vaccinations mb-3" id="vacc-immu">
                                            <option value="">Enter Vaccinations & Immunizations name</option>
                                            @if(isset($allKeywords['Vaccinations']))
                                            @foreach ($allKeywords['Vaccinations'] as $value)
                                            <option value="{{$value->id}}">{{$value->title}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" onclick="addvacc()" class="add-more-vacc-immu ss-bell-sec px-3 py-2 rounded">+</a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                <label>Vaccinations & Immunizations name</label>
                                <div class="ss-speilty-exprnc-add-list vaccinations-content">

                                </div>
                                <ul>
                                    <li class="row w-100 p-0 m-0">
                                        <div class="ps-0">
                                            <select name="vaccinations" class="m-0" id="vaccinations">
                                                <option value="">Enter Vaccinations & Immunizations name</option>
                                                @if(isset($allKeywords['Vaccinations']))
                                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addvacc(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Number of references</label>
                                <input type="number" name="number_of_references" placeholder="Enter Number of Reference" id="number_of_references" maxlength="9">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Min title of reference</label>
                                <input type="text" name="min_title_of_reference" placeholder="Enter Min Title of Reference">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Recency of reference</label>
                                <input type="number" name="recency_of_reference" placeholder="Enter Recency of Reference">
                            </div>
                            <!-- <div class="ss-form-group col-md-12" id="add-more-certifications">
                                <label>Certifications</label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <select name="certificate" class="certificate mb-3" id="certificate">
                                            <option value="">Enter Certification</option>
                                            @if(isset($allKeywords['Certification']))
                                                @foreach ($allKeywords['Certification'] as $value)
                                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                                @endforeach 
                                            @endif 
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:void(0)" onclick="addcertifications()" class=" ss-bell-sec px-3 py-2 rounded">+</a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="ss-form-group ss-prsnl-frm-specialty">
                                <label>Certifications</label>
                                <div class="ss-speilty-exprnc-add-list certificate-content">

                                </div>
                                <ul>
                                    <li class="row w-100 p-0 m-0">
                                        <div class="ps-0">
                                            <select name="certificate" class="m-0" id="certificate">
                                                <option value="">Select Certification</option>
                                                @if(isset($allKeywords['Certification']))
                                                @foreach ($allKeywords['Certification'] as $value)
                                                <option value="{{$value->id}}">{{$value->title}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)" onclick="addcertifications(this)"><i class="fa fa-plus" aria-hidden="true"></i></a></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Skills checklist</label>
                                <select name="skills" class="skills mb-3" id="skills">
                                    <option value="">Enter Skills</option>
                                    @if(isset($allKeywords['Skills']))
                                    @foreach ($allKeywords['Skills'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="text" name="skills" placeholder="Enter Skills"> -->
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Urgency</label>
                                <input type="text" name="urgency" placeholder="Enter Urgency">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label># of Positions Available</label>
                                <input type="number" name="position_available" placeholder="Enter # of Positions Available">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>MSP</label>
                                <!-- <input type="text" name="msp" placeholder="Enter MSP"> -->
                                <select name="msp" class="msp mb-3" id="msp">
                                    <option value="">Enter MSP</option>
                                    @if(isset($allKeywords['MSP']))
                                    @foreach ($allKeywords['MSP'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>VMS</label>
                                <!-- <input type="text" name="vms" placeholder="Enter VMS"> -->
                                <select name="vms" class="vms mb-3" id="vms">
                                    <option value="">Enter VMS</option>
                                    @if(isset($allKeywords['VMS']))
                                    @foreach ($allKeywords['VMS'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label># of Submissions in VMS</label>
                                <input type="text" name="submission_of_vms" placeholder="Enter # of Submissions in VMS">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Block scheduling</label>
                                <!-- <input type="text" name="block_scheduling" placeholder="Enter Block scheduling"> -->
                                <select name="block_scheduling" class="block_scheduling mb-3" id="block_scheduling">
                                    <option value="">Enter Block scheduling</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    <!-- @if(isset($allKeywords['NSchedulingSystem']))
                                    @foreach ($allKeywords['NSchedulingSystem'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Float requirements</label>
                                <!-- <input type="text" name="float_requirement" placeholder="Enter Float requirements"> -->
                                <select name="float_requirement" class="float_requirement mb-3" id="float_requirement">
                                    <option value="">Float requirements</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility Shift Cancellation Policy</label>
                                <!-- <input type="text" name="facility_shift_cancelation_policy" placeholder="Enter Facility Shift Cancellation Policy"> -->
                                <select name="facility_shift_cancelation_policy" class="facility_shift_cancelation_policy mb-3" id="facility_shift_cancelation_policy">
                                    <option value="">Facility Shift Cancellation Policy</option>
                                    @if(isset($allKeywords['AssignmentDuration']))
                                    @foreach ($allKeywords['AssignmentDuration'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Contract Termination Policy</label>
                                <!-- <input type="text" name="contract_termination_policy" placeholder="Enter Contract Termination Policy"> -->
                                <select name="contract_termination_policy" class="contract_termination_policy mb-3" id="contract_termination_policy">
                                    <option value="">Enter Contract Termination Policy</option>
                                    @if(isset($allKeywords['ContractTerminationPolicy']))
                                    @foreach ($allKeywords['ContractTerminationPolicy'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Traveler Distance From Facility</label>
                                <input type="number" name="traveler_distance_from_facility" placeholder="Enter Traveler Distance From Facility">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility</label>
                                <!-- <input type="text" name="facility" placeholder="Enter Facility"> -->
                                <select name="facility" class="facility mb-3" id="facility">
                                    <option value="">Enter Facility</option>
                                    @if(isset($allKeywords['FacilityName']))
                                    @foreach ($allKeywords['FacilityName'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility's Parent System</label>
                                <input type="text" name="facilitys_parent_system" placeholder="Enter Facility's Parent System">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility Average Rating</label>
                                <input type="number" name="facility_average_rating" placeholder="No Ratings yet">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Recruiter Average Rating</label>
                                <input type="number" name="recruiter_average_rating" placeholder="No Ratings yet">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Employer Average Rating</label>
                                <input type="number" name="employer_average_rating" placeholder="No Ratings yet">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Clinical Setting</label>
                                <!-- <input type="text" name="clinical_setting" placeholder="What setting do you prefer?"> -->
                                <select name="clinical_setting" class="clinical_setting mb-3" id="clinical_setting">
                                    <option value="">What setting do you prefer?</option>
                                    @if(isset($allKeywords['SettingType']))
                                    @foreach ($allKeywords['SettingType'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Patient ratio</label>
                                <input type="number" name="patient_ratio" placeholder="How many patients can you handle?">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>EMR</label>
                                <!-- <input type="text" name="emr" placeholder="What EMRs have you used?"> -->
                                <select name="emr" class="emr mb-3" id="emr">
                                    <option value="">Enter EMR</option>
                                    @if(isset($allKeywords['EMR']))
                                    @foreach ($allKeywords['EMR'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Unit</label>
                                <input type="number" name="Unit" placeholder="Enter Unit">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Department</label>
                                <input type="text" name="Department" placeholder="Enter Department">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Bed Size</label>
                                <input type="number" name="Bed_Size" placeholder="Enter Bed Size">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Trauma Level</label>
                                <input type="number" name="Trauma_Level" placeholder="Enter Trauma Level">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Scrub Color</label>
                                <input type="text" name="scrub_color" placeholder="Enter Scrub Color">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility State Code</label>
                                <select name="job_state" id="facility-state-code" onchange="searchCity(this)">
                                    <option value="">Select Facility State Code</option>
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Facility City</label>
                                <select name="job_city" id="facility-city">
                                    <option value="">Select Facility City</option>
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Start Date</label>
                                <input type="date" min="{{ date('Y-m-d') }}" name="start_date" placeholder="Select Date">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>RTO</label>
                                <input type="text" name="rto" placeholder="Enter RTO">
                            </div>
                            <!-- <div class="ss-form-group col-md-4">
                                <label>Scrub Color</label>
                                <input type="text" name="scrub_color" placeholder="Enter Scrub Color">
                            </div> -->
                            <div class="ss-form-group col-md-4">
                                <label>Shift Time of Day</label>
                                <select name="preferred_shift" id="shift-of-day">
                                    <option value="">Select Shift of Day</option>
                                    @if(isset($allKeywords['PreferredShift']))
                                    @foreach ($allKeywords['PreferredShift'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif
                                    <!-- <option value="3x12 Nights or Days">3x12 Nights or Days</option>
                                    <option value="4x10 Days">4x10 Days</option>
                                    <option value="5x8 Days">5x8 Days</option> -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Hours/Week</label>
                                <input type="number" name="hours_per_week" placeholder="Enter Hours/Week">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Guaranteed Hours</label>
                                <input type="number" name="guaranteed_hours" placeholder="Enter Guaranteed Hours">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Hours/Shift</label>
                                <input type="number" name="hours_shift" placeholder="Enter Hours/Shift">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Weeks/Assignment</label>
                                <input type="number" name="preferred_assignment_duration" placeholder="Enter Weeks/Assignment">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Shifts/Week</label>
                                <input type="number" name="weeks_shift" placeholder="Enter Shifts/Week">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Sign-On Bonus</label>
                                <input type="number" name="sign_on_bonus" placeholder="Enter Sign-On Bonus">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Completion Bonus</label>
                                <input type="number" name="completion_bonus" placeholder="Enter Completion Bonus">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Extension Bonus</label>
                                <input type="number" name="extension_bonus" placeholder="Enter Extension Bonus">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Other Bonus</label>
                                <input type="number" name="other_bonus" placeholder="Enter Other Bonus">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Referral Bonus</label>
                                <input type="number" name="referral_bonus" placeholder="Enter Referral Bonus">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>401K</label>
                                <select name="four_zero_one_k" id="401k">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    <!-- @if(isset($allKeywords['401k']))
                                    @foreach ($allKeywords['401k'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Health Insurance</label>
                                <select name="health_insaurance" id="health-insurance">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    <!-- @if(isset($allKeywords['HealthInsurance']))
                                    @foreach ($allKeywords['HealthInsurance'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Dental</label>
                                <select name="dental" id="dental">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    <!-- @if(isset($allKeywords['Dental']))
                                    @foreach ($allKeywords['Dental'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Vision</label>
                                <select name="vision" id="vision">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                    <!-- @if(isset($allKeywords['Vision']))
                                    @foreach ($allKeywords['Vision'] as $value)
                                    <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                    @endif -->
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Actual Hourly rate</label>
                                <input type="number" name="actual_hourly_rate" placeholder="Enter Actual Hourly rate">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Feels Like $/hr</label>
                                <input type="number" name="feels_like_per_hour" placeholder="---">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Overtime</label>
                                <!-- <input type="number" name="overtime" placeholder="Enter Overtime"> -->
                                <select name="overtime" class="overtime mb-3" id="overtime">
                                    <option value="">Enter Overtime</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Holiday</label>
                                <input type="date" name="holiday" placeholder="Select Dates">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>On Call</label>
                                <!-- <input type="text" name="on_call" placeholder="Enter On Call"> -->
                                <select name="on_call" class="on_call mb-3" id="on_call">
                                    <option value="">Enter On Call</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Orientation Rate</label>
                                <input type="number" name="orientation_rate" placeholder="Enter Orientation Rate">
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Weekly Taxable amount</label>
                                <input type="number" name="weekly_taxable_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Weekly non-taxable amount</label>
                                <input type="number" name="weekly_non_taxable_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Employer Weekly Amount</label>
                                <input type="text" name="employer_weekly_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Goodwork Weekly Amount</label>
                                <input type="number" name="goodwork_weekly_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Total Employer Amount</label>
                                <input type="number" name="total_employer_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Total Goodwork Amount</label>
                                <input type="number" name="total_goodwork_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Total Contract Amount</label>
                                <input type="number" name="total_contract_amount" placeholder="---" disabled>
                            </div>
                            <div class="ss-form-group col-md-4">
                                <label>Goodwork Number</label>
                                <input type="text" name="goodwork_number" id="goodwork_number" placeholder="Unique Key" disabled>
                            </div>
                        </div>
                        <div class="ss-crt-opper-buttons">
                            <a href="javascript:void(0)" class="ss-reject-offer-btn text-center w-50" onclick="createDraft()">Save As Draft</a>
                            <a href="javascript:void(0)" class="ss-counter-button text-center w-50" onclick="createJob()">Publish Now</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // var addmoreexperience = document.querySelector('.add-more-experience');

    // addmoreexperience.onclick = function() {
    //     var allExperienceDiv = document.getElementById('all-experience');
    //     var newExperienceDiv = document.querySelector('.experience-inputs').cloneNode(true);
    //     newExperienceDiv.querySelector('select.specialty').selectedIndex = 0;
    //     newExperienceDiv.querySelector('input[type="number"]').value = '';
    //     allExperienceDiv.appendChild(newExperienceDiv);
    // }

    function editJob(type) {
        $('#' + type).focus();
    }
    // function addvacc() {
    //     var formfield = document.getElementById('add-more-vacc-immu');
    //     var newField = document.createElement('input');
    //     newField.setAttribute('type', 'text');
    //     newField.setAttribute('name', 'vaccinations');
    //     newField.setAttribute('onfocusout', "editJob('vacc-immu')"); 
    //     newField.setAttribute('class', 'mb-3');
    //     newField.setAttribute('placeholder', 'Enter Vacc. or Immu. name');
    //     formfield.appendChild(newField);
    // }
    function addvacc() {
        var container = document.getElementById('add-more-vacc-immu');

        var newSelect = document.createElement('select');
        newSelect.name = 'vaccinations';
        newSelect.className = 'vaccinations mb-3';

        var originalSelect = document.getElementById('vacc-immu');
        var options = originalSelect.querySelectorAll('option');
        for (var i = 0; i < options.length; i++) {
            var option = options[i].cloneNode(true);
            newSelect.appendChild(option);
        }
        container.querySelector('.col-md-11').appendChild(newSelect);
    }

    // function addcertifications() {
    //     var formfieldcertificate = document.getElementById('add-more-certifications');
    //     var newField = document.createElement('input');
    //     newField.setAttribute('type', 'text');
    //     newField.setAttribute('onfocusout', 'editJob("certifications")'); 
    //     newField.setAttribute('name', 'certificate');
    //     newField.setAttribute('class', 'mb-3');
    //     newField.setAttribute('placeholder', 'Enter Certification');
    //     formfieldcertificate.appendChild(newField);
    // }

    $(document).ready(function() {
        $('#create-new-job').on('submit', function(event) {

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {

                event.preventDefault();

                var formData = $(this).serialize();
                formData += '&active=' + encodeURIComponent(1);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    // url: '{{ url("recruiter/recruiter-create-opportunity/create") }}',
                    url: "{{ route('recruiter-create-opportunity-store', ['check_type' => 'create']) }}",

                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                console.error('CSRF token not found.');
            }

        });
    });

    function createJob() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            event.preventDefault();
            let check_type = "published";
            if (document.getElementById('job_id').value) {
                let formData = {
                    'job_id': document.getElementById('job_id').value
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    type: 'POST',
                    url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> ' + data.message,
                            time: 5
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        } else {
            console.error('CSRF token not found.');
        }
    }

    function createDraft() {
        if (document.getElementById('job_name').value.trim() !== '' && document.getElementById('type').value.trim() !== '') {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Draft created successfully',
                time: 5
            });
        } else {
            notie.alert({
                type: 'success',
                text: '<i class="fa fa-check"></i> Please enter Job Name and Type',
                time: 5
            });
        }
    }
    document.addEventListener('DOMContentLoaded', function() {

        var myForm = document.getElementById('create-new-job');

        var inputFields = myForm.querySelectorAll('input, select, textarea');
        inputFields.forEach(function(input) {
            input.addEventListener('blur', function() {
                if (document.getElementById('job_name').value.trim() !== '' && document.getElementById('type').value.trim() !== '') {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    if (csrfToken) {
                        event.preventDefault();
                        var formData = "";
                        var check_type = "";
                        if (document.getElementById('job_id').value.trim() !== '') {
                            formData = $(this).serialize();
                            formName = $(this).attr('name')
                            if (formName == "vaccinations" || formName == "preferred_specialty" || formName == "preferred_experience" || formName == "certificate") {
                                var inputFields = document.querySelectorAll(formName == "vaccinations" ? 'select[name="vaccinations"]' : formName == "preferred_specialty" ? 'select[name="preferred_specialty"]' : formName == "preferred_experience" ? 'input[name="preferred_experience"]' : 'select[name="certificate"]');
                                var data = [];
                                inputFields.forEach(function(input) {
                                    data.push(input.value);
                                });
                                formData = formName + '=' + data.join(',');
                            }
                            formData += '&job_id=' + encodeURIComponent(document.getElementById('job_id').value.trim());
                            check_type = "update";
                        } else {
                            formData = {
                                'job_name': document.getElementById('job_name').value.trim(),
                                'type': document.getElementById('type').value.trim(),
                            };
                            check_type = "create";
                        }
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            type: 'POST',
                            // url: "{{ route('recruiter-create-opportunity-store', ['check_type' => '" + urlencode(check_type) + "']) }}",
                            url: "{{ url('recruiter/recruiter-create-opportunity') }}/" + check_type,

                            data: formData,
                            dataType: 'json',
                            success: function(data) {

                                if (document.getElementById('job_id').value.trim() == '' || document.getElementById('job_id').value.trim() == 'null' || document.getElementById('job_id').value.trim() == null) {
                                    document.getElementById("job_id").value = data.job_id;
                                    document.getElementById("goodwork_number").value = data.goodwork_number;
                                }
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    } else {
                        console.error('CSRF token not found.');
                    }
                }
            });
        });
    });
</script>
<script>
    var speciality = {};

    function add_speciality(obj) {
        if (!$('#preferred_specialty').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the speciality please.',
                time: 3
            });
        } else if (!$('#preferred_experience').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Enter total year of experience.',
                time: 3
            });
        } else {
            if (!speciality.hasOwnProperty($('#preferred_specialty').val())) {
                speciality[$('#preferred_specialty').val()] = $('#preferred_experience').val();
                $('#preferred_experience').val('');
                $('#preferred_specialty').val('');
                list_specialities();
            }
        }
    }

    function remove_speciality(obj, key) {
        if (speciality.hasOwnProperty($(obj).data('key'))) {

            var element = document.getElementById("remove-speciality");
            // var dataKey = element.getAttribute("data-key");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'specialty': key,
                    }
                    let removetype = 'specialty';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            // notie.alert({
                            //     type: 'success',
                            //     text: '<i class="fa fa-check"></i> ' + data.message,
                            //     time: 5
                            // });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
                delete speciality[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_specialities();
        }
    }

    function list_specialities() {
        var str = '';
        for (const key in speciality) {
            let specialityname = "";
            @php
            $allKeywordsJSON = json_encode($allKeywords['Speciality_old']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (speciality.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);
                data.forEach(function(item) {
                    if (key == item.id) {
                        specialityname = item.title;
                    }
                });
                const value = speciality[key];
                str += '<ul>';
                str += '<li>' + specialityname + '</li>';
                str += '<li>' + value + ' Years</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-speciality" data-key="' + key + '" onclick="remove_speciality(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.speciality-content').html(str);
    }
</script>
<script>
    var certificate = {};

    function addcertifications() {

        // var container = document.getElementById('add-more-certifications');

        // var newSelect = document.createElement('select');
        // newSelect.name = 'certificate';
        // newSelect.className = 'mb-3';

        // var originalSelect = document.getElementById('certificate');
        // var options = originalSelect.querySelectorAll('option');
        // for (var i = 0; i < options.length; i++) {
        //     var option = options[i].cloneNode(true);
        //     newSelect.appendChild(option);
        // }
        // container.querySelector('.col-md-11').appendChild(newSelect);

        if (!$('#certificate').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the certificate please.',
                time: 3
            });
        } else {
            if (!certificate.hasOwnProperty($('#certificate').val())) {
                console.log($('#certificate').val());

                var select = document.getElementById("certificate");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                certificate[$('#certificate').val()] = optionText; 
                $('#certificate').val('');
                list_certifications();
            }
        }
    }

    function list_certifications() {
        var str = '';
        console.log(certificate);

        for (const key in certificate) {
            console.log(certificate);

            let certificatename = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Certification']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (certificate.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        certificatename = item.title;
                    }
                });
                const value = certificate[key];
                str += '<ul>';
                str += '<li class="w-50">' + certificatename + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-certificate" data-key="' + key + '" onclick="remove_certificate(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.certificate-content').html(str);
    }

    function remove_certificate (obj, key){
        if (certificate.hasOwnProperty($(obj).data('key'))) {

            var element = document.getElementById("remove-certificate");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                console.log('1');
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'certificate': key,
                    }
                    let removetype = 'certificate';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            // notie.alert({
                            //     type: 'success',
                            //     text: '<i class="fa fa-check"></i> ' + data.message,
                            //     time: 5
                            // });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
                delete certificate[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_certifications();
            }
    }
</script>
<script>
    var vaccinations = {};

    function addvacc() {

        // var container = document.getElementById('add-more-certifications');

        // var newSelect = document.createElement('select');
        // newSelect.name = 'certificate';
        // newSelect.className = 'mb-3';

        // var originalSelect = document.getElementById('certificate');
        // var options = originalSelect.querySelectorAll('option');
        // for (var i = 0; i < options.length; i++) {
        //     var option = options[i].cloneNode(true);
        //     newSelect.appendChild(option);
        // }
        // container.querySelector('.col-md-11').appendChild(newSelect);

        if (!$('#vaccinations').val()) {
            notie.alert({
                type: 'error',
                text: '<i class="fa fa-check"></i> Select the vaccinations please.',
                time: 3
            });
        } else {
            if (!vaccinations.hasOwnProperty($('#vaccinations').val())) {
                console.log($('#vaccinations').val());

                var select = document.getElementById("vaccinations");
                var selectedOption = select.options[select.selectedIndex];
                var optionText = selectedOption.textContent;

                vaccinations[$('#vaccinations').val()] = optionText; 
                $('#vaccinations').val('');
                list_vaccinations();
            }
        }
    }

    function list_vaccinations() {
        var str = '';
        console.log(vaccinations);

        for (const key in vaccinations) {
            console.log(vaccinations);

            let vaccinationsname = "";
            @php
                $allKeywordsJSON = json_encode($allKeywords['Vaccinations']);
            @endphp
            let allspcldata = '{!! $allKeywordsJSON !!}';
            if (vaccinations.hasOwnProperty(key)) {
                var data = JSON.parse(allspcldata);

                data.forEach(function(item) {
                    if (key == item.id) {
                        vaccinationsname = item.title;
                    }
                });
                const value = vaccinations[key];
                str += '<ul>';
                str += '<li>' + vaccinationsname + '</li>';
                str += '<li class="w-50 text-end pe-3"><button type="button"  id="remove-vaccinations" data-key="' + key + '" onclick="remove_vaccinations(this, ' + key + ')"><img src="{{URL::asset("frontend/img/delete-img.png")}}" /></button></li>';
                str += '</ul>';
            }
        }
        $('.vaccinations-content').html(str);
    }

    function remove_vaccinations (obj, key){
        if (vaccinations.hasOwnProperty($(obj).data('key'))) {

            var element = document.getElementById("remove-vaccinations");
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                event.preventDefault();
                if (document.getElementById('job_id').value) {
                    let formData = {
                        'job_id': document.getElementById('job_id').value,
                        'vaccinations': key,
                    }
                    let removetype = 'vaccinations';
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        type: 'POST',
                        url: "{{ url('recruiter/remove') }}/" + removetype,
                        data: formData,
                        dataType: 'json',
                        success: function(data) {
                            // notie.alert({
                            //     type: 'success',
                            //     text: '<i class="fa fa-check"></i> ' + data.message,
                            //     time: 5
                            // });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
                delete vaccinations[$(obj).data('key')];
            } else {
                console.error('CSRF token not found.');
            }
            list_vaccinations();
            }
    }
    
    const numberOfReferencesField = document.getElementById('number_of_references');
    numberOfReferencesField.addEventListener('input', function() {
        if (numberOfReferencesField.value.length > 9) {
            numberOfReferencesField.value = numberOfReferencesField.value.substring(0, 9);
        }
    });

    $(document).ready(function () {
        let formData = {
            'country_id': '233',
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-states') }}",
            data: formData,
            dataType: 'json',
            success: function(data) {
                var stateSelect = $('#facility-state-code'); 
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility State Code"
                }));
                $.each(data.data, function(index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    function searchCity(e){
        var selectedValue = e.value;
        console.log("Selected Value: " + selectedValue);
        let formData = {
            'state_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-cities') }}",
            data: formData,
            dataType: 'json',
            success: function(data) {
                var stateSelect = $('#facility-city'); 
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Facility City"
                }));
                $.each(data.data, function(index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    function getSpecialitiesByProfession(e){
        var selectedValue = e.value;
        let formData = {
            'profession_id': selectedValue,
            'api_key': '123',
        }
        $.ajax({
            type: 'POST',
            url: "{{ url('api/get-profession-specialities') }}",
            data: formData,
            dataType: 'json',
            success: function(data) {
                var stateSelect = $('#preferred_specialty'); 
                stateSelect.empty();
                stateSelect.append($('<option>', {
                    value: "",
                    text: "Select Specialty"
                }));
                $.each(data.data, function(index, state) {
                    stateSelect.append($('<option>', {
                        value: state.state_id,
                        text: state.name
                    }));
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>
@endsection
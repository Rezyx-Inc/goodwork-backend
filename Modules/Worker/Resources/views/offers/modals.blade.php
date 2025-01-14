<!----------------job-detls popup form----------->

<!-----------Did you really graduate?------------>
<!-- nursing_license_state_file  Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="nursing_license_state_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#nursing_license_state_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="nursing_license_state" method="post" action="{{ route('worker-upload-files') }}"
                        id="StateCode_modal_form" class="modal-form" enctype="multipart/form-data">
                        @csrf
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        {{-- StateCode --}}
                        <div class="container-multiselect" id="certificate">
                            <div class="select-btn">
                                <span class="btn-text">Select Professional Licensure</span>
                                <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                            <ul class="list-items">
                                @if (isset($allKeywords['StateCode']))
                                    @foreach ($allKeywords['StateCode'] as $value)
                                        <li class="item" value="{{ $value->title }}">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">{{ $value->title }}</span>
                                        </li>
                                        <input name="nursing_license_state" displayName="{{ $value->title }}"
                                            type="file" id="upload-{{ $loop->index }}" class="files-upload"
                                            style="display: none;" />
                                    @endforeach
                                @endif
                            </ul>
                            <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'file')">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Certification  Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="certification_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#certification_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="certification" method="post" action="{{ route('worker-upload-files') }}"
                        id="certification_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        {{-- certification --}}
                        <div class="container-multiselect" id="certificate">
                            <div class="select-btn">
                                <span class="btn-text">Select Certification</span>
                                <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                            <ul class="list-items">
                                @if (isset($allKeywords['Certification']))
                                    @foreach ($allKeywords['Certification'] as $value)
                                        <li class="item" value="{{ $value->title }}">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">{{ $value->title }}</span>
                                        </li>
                                        <input name="certification" displayName="{{ $value->title }}" type="file"
                                            id="upload-{{ $loop->index }}" class="files-upload"
                                            style="display: none;" />
                                    @endforeach
                                @endif
                            </ul>
                            <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'file')">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- Vaccination  Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="vaccination_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#vaccination_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="vaccination" method="post" action="{{ route('worker-upload-files') }}"
                        id="vaccination_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        <div class="container-multiselect" id="vaccinations">
                            <div class="select-btn">
                                <span class="btn-text">Select Vaccinations</span>
                                <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                            <ul class="list-items">
                                @if (isset($allKeywords['Vaccinations']))
                                    @foreach ($allKeywords['Vaccinations'] as $value)
                                        <li class="item" value="{{ $value->title }}">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">{{ $value->title }}</span>
                                        </li>
                                        <input name="vaccination" displayName="{{ $value->title }}" type="file"
                                            class="files-upload" style="display: none;" />
                                    @endforeach
                                @endif
                            </ul>
                            <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'file')">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- References Modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="reference_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#reference_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="references" method="post" action="{{ route('worker-upload-files') }}"
                        id="reference_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        @csrf
                        {{-- reference --}}
                        <div class="container-multiselect" id="references">
                            <h4>Who are your References?</h4>
                            <div class="ss-form-group">
                                <label>Reference Name</label>
                                <input type="text" name="name" placeholder="Name of Reference">
                                <span class="help-block"></span>
                            </div>
                            <div class="ss-form-group">
                                <label>Phone Number</label>
                                <input id="ref_phone_number" type="tel" name="phone"
                                    placeholder="Phone Number of Reference">
                                <span class="help-block"></span>
                            </div>

                            <div class="ss-form-group">
                                <label>Email</label>
                                <input type="text" name="reference_email" placeholder="Email of Reference">
                                <span class="help-block"></span>
                            </div>

                            <div class="ss-form-group">
                                <label>Date Referred</label>
                                <input type="date" name="date_referred">
                                <span class="help-block"></span>
                            </div>

                            <div class="ss-form-group">
                                <label>Min Title of Reference</label>
                                <input id="min_title_of_reference" type="text" name="min_title_of_reference"
                                    placeholder="Min Title of Reference">
                                <span class="help-block"></span>
                            </div>

                            <div class="ss-form-group">
                                <label>Is this from your last assignment?</label>

                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary focus active" data-toggle-class="btn-primary"
                                        data-toggle-passive-class="btn-default">
                                        <input type="radio" name="recency_of_reference" value="1"
                                            class="join-btn" checked> Yes
                                    </label>
                                    <label class="btn btn-secondary" type="button" data-toggle-class="btn-primary">
                                        <input type="radio" name="recency_of_reference" value="0"
                                            class="join-btn"> &nbsp; No &nbsp;
                                    </label>
                                </div>


                                <span class="help-block"></span>
                            </div>

                            <div class="ss-form-group fileUploadInput"
                                style="display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            ">
                                <label class="upload_label">Upload Image <span
                                        cass="text-danger">(optional)</span></label>
                                <input hidden type="file" name="image">
                                <button type="button" onclick="open_file(this)">Choose File</button>
                                <span class="help-block"></span>
                            </div>
                            <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'file')">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Skills Modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="skills_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#skills_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="skills" method="post" action="{{ route('worker-upload-files') }}"
                        id="skills_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        {{-- skills --}}
                        <div class="container-multiselect" id="skills_checklists">
                            <div class="select-btn">
                                <span class="btn-text">Select Skills</span>
                                <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                            <ul class="list-items">
                                @if (isset($allKeywords['Speciality']))
                                    @foreach ($allKeywords['Speciality'] as $value)
                                        <li class="item" value="{{ $value->title }}">
                                            <span class="checkbox">
                                                <i class="fa-solid fa-check check-icon"></i>
                                            </span>
                                            <span class="item-text">{{ $value->title }}</span>
                                        </li>
                                        <input name="skills" displayName="{{ $value->title }}" type="file"
                                            id="upload-{{ $loop->index }}" class="files-upload"
                                            style="display: none;" />
                                    @endforeach
                                @endif
                            </ul>
                            <button onclick="saveData(event,'file')" class="ss-job-dtl-pop-sv-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- driving_licence Modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="driving_license_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#driving_license_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="driving_license" method="post" action="{{ route('worker-upload-files') }}"
                        id="driving_license_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        @csrf
                        {{-- driving license --}}
                        <div id="driving_license">
                            <div class="container-multiselect"
                                style="justify-content: center;display: flex;margin-bottom: 30px;">
                                <div class="ss-form-group fileUploadInput"
                                    style="
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                    ">
                                    <input name="driving_license" displayName="Driving Licence" type="file"
                                        class="files-upload" style="padding: 0px;">
                                    <div class="list-items">
                                        <input hidden type="text" name="type" value="driving licence"
                                            class="item">
                                    </div>
                                    <button type="button" onclick="open_file(this)">Choose File</button>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <button onclick="saveData(event,'file')" class="ss-job-dtl-pop-sv-btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



{{-- resume Modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="resume_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#resume_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="resume" method="post" action="{{ route('worker-upload-files') }}"
                        id="resume_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        
                        {{-- Resume Upload Not Working yet--}}
                            <div class="container-multiselect"
                                style="justify-content: center;display: flex;margin-bottom: 30px;">
                                <div class="ss-form-group fileUploadInput"
                                    style="
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                    ">
                                    <input name="resume" displayName="Resume" type="file"
                                        class="files-upload" style="padding: 0px;">
                                    <div class="list-items">
                                        <input hidden type="text" name="type" value="resume"
                                            class="item">
                                    </div>
                                    <button type="button" onclick="open_file(this)">Choose File</button>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <button onclick="saveData(event,'file')" class="ss-job-dtl-pop-sv-btn">Save</button>
                        
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- diploma Modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="diploma_file_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#diploma_file_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-jb-dtl-pop-chos-dv">
                    <form name="diploma" method="post" action="{{ route('worker-upload-files') }}"
                        id="diploma_file_modal_form" class="modal-form" enctype="multipart/form-data">
                        <div class="ss-job-dtl-pop-frm-sml-dv"></div>
                        <h4></h4>
                        @csrf
                        {{-- driving license --}}
                        <div id="diploma">

                            <div class="container-multiselect"
                                style="justify-content: center;display: flex;margin-bottom: 30px;">
                                <div class="ss-form-group fileUploadInput"
                                    style="
                                                                display: flex !important;
                                                                justify-content: center !important;
                                                                align-items: center !important;
                                                            ">
                                    <input name="diploma" displayName="Diploma" type="file"
                                        class="d-none files-upload">
                                    <div class="list-items">
                                        <input hidden type="text" name="type" value="diploma" class="item">
                                    </div>
                                    <button type="button" onclick="open_file(this)">Choose File</button>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'file')">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



{{-- number model --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_number_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#input_number_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="input_number_modal_form"
                        class="modal-form">
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <div class="ss-form-group">
                            <input type="number" name="" step='0.01' placeholder="">
                            <span class="help-block"></span>
                        </div>
                        <button type="submit" class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'input_number')"
                            >Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- end number model --}}



<!-----------Are you sure you never worked here as staff?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="binary_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#binary_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="binary_modal_form"
                        class="modal-form">
                        
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <ul class="ss-jb-dtlpop-chck">
                            <li>
                                <label>
                                    <input type="radio" name="radio" name="binary" value="1">
                                    <span>Yes</span>
                                </label>
                            </li>

                            <li>
                                <label>
                                    <input type="radio" name="radio" name="binary" value="0">
                                    <span>No</span>
                                </label>
                            </li>
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'binary')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
{{-- beneftis modal --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="benefits_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#benefits_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('my-profile.store') }}" id="benefits_modal_form"
                        class="modal-form">
                        @csrf
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <ul class="row d-flex justify-content-center ss-jb-dtlpop-chck">
                            <li>
                                <label>
                                    <input type="radio" name="worker_benefits" value="1">
                                    <span>Yes, Please</span>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="worker_benefits" value="2">
                                    <span>Preferable</span>
                                </label>
                            </li>

                            <li>
                                <label>
                                    <input type="radio" name="worker_benefits" value="0">
                                    <span>No, Thanks</span>
                                </label>
                            </li>
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'binary')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- end beneftis modal --}}

{{-- rto modal  --}}
<!-----------Are you sure you never worked here as staff?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="rto_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#rto_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="rto_modal_form"
                        class="modal-form">
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <ul class="ss-jb-dtlpop-chck">
                            <li>
                                <label>
                                    <input type="radio" name="rto" value="allowed">
                                    <span>Allowed</span>
                                </label>
                            </li>

                            <li>
                                <label>
                                    <input type="radio" name="rto" value="not allowed">
                                    <span>Not Allowed</span>
                                </label>
                            </li>
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'rto')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<!-----------Yes we need your SS# to submit you------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="input_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#input_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="input_modal_form"
                        class="modal-form">
                        
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <div class="ss-form-group">
                            <input type="text" name="" placeholder="">
                            <span class="help-block"></span>
                        </div>
                        <button type="submit" class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'input')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- date modal --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="date_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#date_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="date_modal_form"
                        class="modal-form">
                        <div class="ss-form-group">
                            {{-- date format "yyyy-mm-dd" --}}
                            <input type="date" name="worker_start_date" placeholder="yyyy/mm/dd">
                        </div>
                        <button type="submit" class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'date')">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-----------What's your specialty?------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-specialty" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form>
                        @csrf
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4>Yes we need your SS# to submit you</h4>
                        <div class="ss-form-group">
                            <select name="cars"></select>
                        </div>
                        <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1" name="vehicle1"
                                value="Bike">
                            <label for="vehicle1"> This is a compact license</label>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'input')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Dropdown modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="dropdown_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#dropdown_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('my-profile.store') }}" id="dropdown_modal_form"
                        class="modal-form">
                        @csrf
                        <h4></h4>
                        <div class="ss-form-group">
                            <select name=""></select>
                        </div>
                        {{-- <div class="ss-jb-dtl-pop-check"><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1"> This is a compact license</label>
                    </div> --}}
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'dropdown')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- worker_pay_frequency  all keywords pay frequency --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="pay_frequency_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#pay_frequency_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('my-profile.store') }}" id="pay_frequency_modal_form"
                        class="modal-form">
                        <h4></h4>
                        <div class="ss-form-group">
                            <select name="worker_pay_frequency">
                                @if (isset($allKeywords['PayFrequency']))
                                    @foreach ($allKeywords['PayFrequency'] as $value)
                                        <option value="{{ $value->title }}">{{ $value->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event,'pay_frequency')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{--  facility_shift_cancelation_policy all keywords FacilityShiftCancellationPolicy --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="facility_shift_cancelation_policy_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#facility_shift_cancelation_policy_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}"
                        id="facility_shift_cancelation_policy_modal_form" class="modal-form">
                        <h4></h4>
                        <div class="ss-form-group">
                            <select name="facility_shift_cancelation_policy">
                                @if (isset($allKeywords['FacilityShiftCancellationPolicy']))
                                    @foreach ($allKeywords['FacilityShiftCancellationPolicy'] as $value)
                                        <option value="{{ $value->title }}">{{ $value->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'facility_shift_cancelation_policy')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- contract_termination_policy ContractTerminationPolicy --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="contract_termination_policy_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#contract_termination_policy_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}"
                        id="contract_termination_policy_modal_form" class="modal-form">
                        <h4></h4>
                        <div class="ss-form-group">
                            <select name="contract_termination_policy">
                                @if (isset($allKeywords['ContractTerminationPolicy']))
                                    @foreach ($allKeywords['ContractTerminationPolicy'] as $value)
                                        <option value="{{ $value->title }}">{{ $value->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'contract_termination_policy')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>




<!-----------Upload your latest skills checklist------------>
<!-- Modal -->

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-checklist" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
                    <form>
                        @csrf
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4>Upload your latest skills checklist</h4>
                        <div class="ss-form-group">
                            <label>Skills Name</label>
                            <input type="text" name="Name of Reference" placeholder="Phone Number of Reference">
                        </div>


                        <div class="ss-form-group">
                            <label>Completion Date</label>
                            <input type="date" id="birthday" name="birthday">
                        </div>


                        <div class="ss-form-group fileUploadInput">
                            <label>Completion Date</label>
                            <input type="file">
                            <button>Choose File</button>
                        </div>

                        <div class="ss-add-more-se"><a href="#">Add More</a></div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="saveData(event)">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>






<div class="modal fade ss-jb-dtl-pops-mn-dv" id="multi_select_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#multi_select_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('my-profile.store') }}" id="multi_select_modal_form"
                        class="modal-form">
                        @csrf
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <div class="ss-form-group ss-prsnl-frm-specialty">
                            <label>EMR</label>
                            <div class="ss-speilty-exprnc-add-list Emr-content">
                            </div>
                            <ul>
                                <li class="row w-100 p-0 m-0">
                                    <div class="ps-0">
                                        <select class="m-0" id="Emr">
                                            <option value="" disabled selected hidden>Select an emr
                                            </option>
                                            @if (isset($allKeywords['EMR']))
                                                @foreach ($allKeywords['EMR'] as $value)
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="text" hidden id="EmrAllValues" name="worker_emr">
                                    </div>
                                </li>
                                <li>
                                    <div class="ss-prsn-frm-plu-div"><a href="javascript:void(0)"
                                            onclick="addEmr('from_add')"><i class="fa fa-plus"
                                                aria-hidden="true"></i></a></div>
                                </li>
                            </ul>
                        </div>

                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'multi-select')">Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- multi number values --}}

<div class="modal fade ss-jb-dtl-pops-mn-dv" id="multi_input_number_modal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#multi_input_number_modal"
                    onclick="close_modal(this)" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form">
                    <form method="post" action="{{ route('worker-update-information') }}" id="multi_input_number_modal_form"
                        class="modal-form">
                        <div class="ss-job-dtl-pop-frm-sml-dv">
                            <div></div>
                        </div>
                        <h4></h4>
                        <div class="ss-form-group">
                            <input value="" type="text" name="" placeholder="Enter numbers separated by commas">
                            <span class="help-block"></span>
                        </div>
                        <div class="pt-3">
                            <span class="helper help-block-multi_input_number_modal"></span>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="saveData(event,'multi_input_number')"
                            >Save</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- end multi number values --}}






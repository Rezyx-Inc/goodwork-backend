{{-- uploading documents modal --}}
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="job-dtl-Dcouments" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="Dcouments-modal-form-btn"></button>
            </div>
            <div class="modal-body">
                <div class="ss-job-dtl-pop-form ss-job-dtl-pop-form-refrnc">
                    <div class="ss-job-dtl-pop-frm-sml-dv">
                        <div></div>
                    </div>
                    <h4>Add Your Dcouments?</h4>
                    <div class="ss-form-group">
                        <label>Document Type</label>
                        <select name="type_documents" onChange="controlInputsFiles(this)">
                            <option value="" disabled selected hidden>Select</option>
                            <option value="skills_checklists">Skills checklist</option>
                            <option value="certificate">Certificate</option>
                            <option value="driving_license">Drivers License</option>
                            {{-- <option value="ss_number">Ss Document</option> --}}
                            <option value="other">Others</option>
                            <option value="vaccinations">Vaccinations</option>
                            <option value="references">References</option>
                            <option value="diploma">Diploma</option>
                            <option value="nursing_license_state">Professional License</option>
                        </select>
                        <span class="help-block"></span>
                    </div>
                    {{-- documents forms --}}
                    {{-- skills --}}
                    <div class="container-multiselect d-none" id="skills_checklists">
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
                                    <input displayName="{{ $value->title }}" type="file"
                                        id="upload-{{ $loop->index }}" class="files-upload" style="display: none;"
                                        accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx" />
                                @endforeach
                            @endif
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn">Save</button>
                    </div>

                    {{-- certification --}}
                    <div class="container-multiselect d-none" id="certificate">
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
                                    <input displayName="{{ $value->title }}" type="file"
                                        id="upload-{{ $loop->index }}" class="files-upload" style="display: none;"
                                        accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx" />
                                @endforeach
                            @endif
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="sendMultipleFiles('certification')">Save</button>
                    </div>

                    {{-- professional license --}}
                    <div class="container-multiselect d-none" id="nursing_license_state">
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
                                    <input displayName="{{ $value->title }}" type="file"
                                        id="upload-{{ $loop->index }}" class="files-upload" style="display: none;" />
                                @endforeach
                            @endif
                        </ul>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="sendMultipleFiles('nursing_license_state')">Save</button>
                    </div>

                    {{-- old professional license --}}
                    {{-- <div class="d-none" id="professional_license">
                                        <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                                        </div>
                                        <div class="container-multiselect">
                                            <div class="ss-form-group fileUploadInput"
                                                style="
                                                                        display: flex !important;
                                                                        justify-content: center !important;
                                                                        align-items: center !important;
                                                                    ">
                                                <input hidden displayName="Professional License" type="file"
                                                    class="files-upload">
                                                <div class="list-items">
                                                    <input hidden type="text" name="type"
                                                        value="Professional License" class="item">
                                                </div>
                                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                                    File</button>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="sendMultipleFiles('professional_license')">Save</button>
                                    </div> --}}


                    {{-- driving license --}}
                    <div class="d-none" id="driving_license">
                        <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                        </div>
                        <div class="container-multiselect">
                            <div class="ss-form-group fileUploadInput"
                                style="
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                        ">
                                <input hidden displayName="Driving Licence" type="file" class="files-upload"
                                    accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx">
                                <div class="list-items">
                                    <input hidden type="text" name="type" value="driving licence"
                                        class="item">
                                </div>

                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                    File</button>
                                <span class="help-block"></span>

                            </div>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="sendMultipleFiles('driving_license')">Save</button>
                    </div>
                    {{-- ss number --}}
                    {{-- <div class="d-none" id="ss_number">
                                        <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                                        </div>
                                        <div class="container-multiselect">
                                            <div class="ss-form-group fileUploadInput"
                                                style="
                                                                        display: flex;
                                                                        justify-content: center;
                                                                        align-items: center;
                                                                    ">
                                                <input hidden displayName="Ss number file" type="file"
                                                    class="files-upload" accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx">
                                                <div class="list-items">
                                                    <input hidden type="text" name="type" value="ss number file"
                                                        class="item">
                                                </div>
                                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                                    File</button>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <button class="ss-job-dtl-pop-sv-btn"
                                            onclick="sendMultipleFiles('ss_number')">Save</button>
                                    </div> --}}

                    {{-- other --}}
                    <div class="d-none" id="other">
                        <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                        </div>
                        <div class="container-multiselect">
                            <div class="ss-form-group fileUploadInput"
                                style="
                                                                        display: flex;
                                                                        justify-content: center;
                                                                        align-items: center;
                                                                    ">
                                <input hidden displayName="Other" type="file" class="files-upload">
                                <div class="list-items"
                                    accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx">
                                    <input hidden type="text" name="type" value="other" class="item">
                                </div>
                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                    File</button>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn">Save</button>
                    </div>

                    {{-- vaccinations --}}
                    <div class="d-none" id="vaccinations">
                        <div class="container-multiselect">
                            <div class="select-btn">
                                <span class="btn-text">Select Vaccinations</span>
                                <span class="arrow-dwn">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>
                            <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
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
                                        <input displayName="{{ $value->title }}" type="file" class="files-upload"
                                            accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx" />
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="sendMultipleFiles('vaccination')">Save</button>
                    </div>

                    {{-- references --}}

                    <div class="container-multiselect d-none" id="references">
                        <h4>Who are your References?</h4>
                        <div class="ss-form-group">
                            <label>Reference Name</label>
                            <input type="text" name="name" placeholder="Name of Reference">
                            <span class="help-block"></span>
                        </div>
                        <div class="ss-form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" placeholder="Phone Number of Reference">
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
                            <select name="min_title_of_reference">
                                <option value="" disabled selected hidden>Select a min title</option>
                                @if (isset($allKeywords['MinTitleOfReference']))
                                    @foreach ($allKeywords['MinTitleOfReference'] as $value)
                                        <option value="{{ $value->title }}">{{ $value->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            <span class="help-block"></span>
                        </div>

                        <div class="ss-form-group">
                            <label>Is this from your last assignment?</label>
                            <select name="recency_of_reference">
                                <option value="" disabled selected hidden>Select a recency period
                                </option>
                                @if (isset($allKeywords['RecencyOfReference']))
                                    @foreach ($allKeywords['RecencyOfReference'] as $value)
                                        <option value="{{ $value->title }}">{{ $value->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="help-block"></span>
                        </div>

                        <div class="ss-form-group fileUploadInput"
                            style="display: flex;
                                                                        justify-content: center;
                                                                        align-items: center;
                                                                        ">
                            <label>Upload Image</label>
                            <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                            </div>
                            <input type="file" name="image"
                                accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx">
                            <button type="button" onclick="open_file(this)">Choose File</button>
                            <span class="help-block"></span>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="sendMultipleFiles('references')">Save</button>
                    </div>

                    {{-- diploma --}}
                    <div class="d-none" id="diploma">
                        <div class="container-multiselect">
                            <div class="ss-form-group fileUploadInput"
                                style="
                                                                        display: flex !important;
                                                                        justify-content: center !important;
                                                                        align-items: center !important;
                                                                    ">
                                <input hidden displayName="Diploma" type="file"
                                    accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx"
                                    class="files-upload">
                                <div class="list-items">
                                    <input hidden type="text" name="type" value="diploma" class="item">
                                </div>
                                <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                                </div>
                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                    File</button>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn" onclick="sendMultipleFiles('diploma')">Save</button>
                    </div>

                    {{-- professional license --}}
                    <div class="d-none" id="professional_license">
                        <div style="margin-bottom:60px;" class="row" id="uploaded-files-names">
                        </div>
                        <div class="container-multiselect">
                            <div class="ss-form-group fileUploadInput"
                                style="
                                                                        display: flex !important;
                                                                        justify-content: center !important;
                                                                        align-items: center !important;
                                                                    ">
                                <input hidden displayName="Professional License" type="file" class="files-upload"
                                    accept="image/heic, image/png, image/jpeg, application/pdf, .doc, .docx">
                                <div class="list-items">
                                    <input hidden type="text" name="type" value="Professional License"
                                        class="item">
                                </div>
                                <button class="col-5" type="button" onclick="open_file(this)">Choose
                                    File</button>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <button class="ss-job-dtl-pop-sv-btn"
                            onclick="sendMultipleFiles('professional_license')">Save</button>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
{{-- end uploading documents modal --}}

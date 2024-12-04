<div class="row justify-content-center">
    {{-- Upload Document --}}
    {{-- <div class="ss-form-group">
                <label>Upload Document</label>
                <input type="file" id="document_file" name="files" multiple
                    required><br><br>
                <label class="mt-2" for="file">Choose a file</label>
            </div>
            <span class="help-block-file"></span> --}}
    <div class="ss-form-group "
        style="
                display: flex;
                justify-content: right;
                align-items: center;
            ">
        <span style="margin:0px; margin-right:20px;">Add your documents here
            !</span>
        <a href="#" onclick="open_modal(this)" class="ss-opr-mngr-plus-sec"
            style="
                    background: #3d2c39;
                    width: 40px;
                    height: 40px;
                    line-height: 40px;
                    text-align: center;
                    border-radius: 100px;
                    color: #52DEC1 !important;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    "
            data-bs-toggle="modal" data-bs-target="#job-dtl-Dcouments"><i class="fas fa-plus"></i></a>

        <br><br>

    </div>
    {{-- manage file table --}}
    <table style="font-size: 16px;" class="table row">
        <thead>
            <tr class="row">
                <th class="col-3">Document Name</th>
                <th class="col-3">Type</th>
                <th class="col-3">View</th>
                <th class="col-3">Delete</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

@extends('organization::layouts.main')

@section('content')
@php
$faker = app('Faker\Generator');
@endphp
<main style="padding-top: 130px" class="ss-main-body-sec">
    <div class="row">
        <div class="col-12 rules-manage-tl">
            <div class="ss-dash-wel-div">
                <h5><span class="title-span" >Rule</span> Management</h5>
            </div>
        </div>
    </div>
    <form method="post" onsubmit="return false">
        @csrf
        <table style="font-size: 16px;" class="table">
            <thead>
                <tr>
                    <th scope="col">Fields Name</th>
                    <th scope="col">Required To Apply</th>
                    <th scope="col">Required To Publish</th>
                </tr>
            </thead>
            <tbody>
                @foreach($columns as $item)
                <tr>
                    <th scope="row">{{$item["displayName"]}}</th>
                    <td>
                        <div class="form-check">
                            <input id="apply_{{$item["fieldID"]}}" onclick="triggerSaveKeysChanges()" class="form-check-input"
                            @if($item['applyDisabled']) disabled @endif   type="checkbox" name="requiredToApply[]" value="{{$item["fieldID"]}}">
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input id="submit_{{$item["fieldID"]}}" onclick="triggerSaveKeysChanges()" class="form-check-input"
                            @if ($item['publishDisabled']) disabled @endif  type="checkbox" name="requiredToSubmit[]" value="{{$item["fieldID"]}}">
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div class="ss-nojob-dv-hed">
            <button onclick="get_requiredfields()" type="submit" name="action" value="save" id="save_key" class="save d-none">Save changes</button>
        </div>
    </form>
</main>
<script>

    function get_requiredfields(){
        let requiredToApply = Array.from(document.querySelectorAll('input[name="requiredToApply[]"]:checked')).map(input => input.value);
        let requiredToSubmit = Array.from(document.querySelectorAll('input[name="requiredToSubmit[]"]:checked')).map(input => input.value);
        let preferences = {
            requiredToApply: requiredToApply,
            requiredToSubmit: requiredToSubmit
        };

        $.ajax({
                    url: '/organization/add-preferences',
                    type: 'POST',
                    data: {
                        preferences: preferences,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Rules Saved Successfully',
                            time: 2
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    }
        });


        console.log("requiredToApply", requiredToApply);
        console.log("requiredToSubmit", requiredToSubmit);

    }

    var requiredFields = @json($requiredFields);
    console.log('requiredFields', requiredFields);
    var requiredFieldsToApply = requiredFields.requiredToApply;
    var requiredFieldsToSubmit = requiredFields.requiredToSubmit;

    for (const column of requiredFieldsToSubmit) {
       
        console.log(column);
    var checkbox = document.getElementById('submit_' + column);
    if (checkbox) {
        checkbox.checked = true;
    }
    }
    
    for (const column of requiredFieldsToApply) {
        console.log(column);
        
    var checkbox = document.getElementById('apply_' + column);
    if (checkbox) {
        checkbox.checked = true;
    }

    }

    function triggerSaveKeysChanges() {
        document.getElementById("save_key").classList.remove('d-none');
    }


</script>
<style>
    .title-span {
            color: #b5649e;
        }
    .rules-manage-tl {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    .ss-main-body-sec table td
        {
            text-align: center;
        }
        .ss-main-body-sec table th
        {
            text-align: center;
        }
    .save {
        border: 1px solid #3D2C39 !important;
        color: #3D2C39 !important;
        border-radius: 100px;
        padding: 10px;
        text-align: center;
        width: fit-content;
        margin-top: 25px;
        background: transparent !important;
        margin-right: 6px;
    }

    .form-check-input[type=checkbox] {
        border-radius: .25rem;
        margin-top: .19em;
        margin-right: 6px;
        
    }

    .form-check-input[type=checkbox]:focus:after {
        content: "";
        position: absolute;
        width: .875rem;
        height: .875rem;
        z-index: 1;
        display: block;
        border-radius: 0;
        background-color: #fff
    }

    .form-check-input[type=checkbox]:checked {
        background-image: none;
        background-color: black;
    }
    .form-check{
        display: flex;
        justify-content: center;
    }

    .form-check-input[type=checkbox]:checked:after {
        display: block;
        transform: rotate(45deg)
            /*!rtl:ignore*/
        ;
        width: .375rem;
        height: .8125rem;
        border: .125rem solid #fff;
        border-top: 0;
        border-left: 0
            /*!rtl:ignore*/
        ;
        margin-left: .25rem;
        margin-top: -1px;
        background-color: transparent
    }

    .form-check-input[type=checkbox]:checked:focus {
        background-color: black
    }

    .form-check-input[type=checkbox]:indeterminate {
        border-color: black
    }

    .form-check-input:checked {
        border-color: black;
    }

    .form-check-input:checked:focus {
        border-color: black;
    }

    .delete {
        border-radius: 57px;
        border: 1px solid var(--border, #111011);
        background: var(--light-bg-purple, #FFF8FD);
        width: 170px;
        height: 32px;
        color: var(--darkpurple, #3D2C39);
        text-align: center;
        font-family: Poppins;
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        text-transform: capitalize;
    }
</style>
@endsection

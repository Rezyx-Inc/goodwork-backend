@extends('employer::layouts.main')

@section('content')
@php
$faker = app('Faker\Generator');
@endphp
<main style="padding-top: 130px" class="ss-main-body-sec">
<form action="{{route('getApiKey')}}">
    <table style="font-size: 16px;" class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Key</th>
                <th scope="col">Active</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keys as $item)
            <tr>
                <th scope="row">{{$item->id}}</th>
                <td>{{$item->name}}</td>
                <td>{{$item->key}}</td>
                <td>
                    <div class="form-check">
                        <input id="{{$item->id}}" onclick="triggerSaveKeysChanges()" class="form-check-input" type="checkbox" name="keyCheckboxes[]" value="{{$item->id}}">
                    </div>
                </td>
            </tr>
            <tr>
            @endforeach

        </tbody>
    </table>
    <div class="ss-nojob-dv-hed">
    <button type="submit" name="action" value="add" id="add_key" class="add">Add New Api Key</button>
    <button type="submit" name="action" value="save" id="save_key" class="save d-none">Save changes</button>
    </div>
    </form>
</main>
<script>

const keys = @json($keys);
console.log(keys);
for(const key of keys) {
    if (key.active == 1){
        console.log(key.active);
        document.getElementById(key.id).checked = true;
    }
}

function triggerSaveKeysChanges(){
document.getElementById("save_key").classList.remove('d-none');
}


</script>
<style>
    .add {
        border: 1px solid #3D2C39 !important;
    color: #fff !important;
    border-radius: 100px;
    padding: 10px;
    text-align: center;
    width:fit-content;
    margin-top: 25px;
    background: #3D2C39 !important;
    margin-right: 6px;
}
    .save {
    border: 1px solid #3D2C39 !important;
    color: #3D2C39 !important;
    border-radius: 100px;
    padding: 10px;
    text-align: center;
    width:fit-content;
    margin-top: 25px;
    background: transparent !important;
    margin-right: 6px;
}
    .form-check-input[type=checkbox] {
        border-radius: .25rem;
        margin-top: .19em;
        margin-right: 6px
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
</style>
@endsection

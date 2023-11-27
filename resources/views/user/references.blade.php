@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
<form method="post" action="{{route('references')}}" class="no-redirect-form">
    <div class="ss-persnl-frm-hed">
        <p><span><img src="{{URL::asset('frontend/img/my-per--con-refren.png')}}" /></span>References</p>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>


       <div class="ss-form-group">
         <label>number of references </label>
            <select name="worker_number_of_references" onchange="references(this)">
                <option value="">Select</option>
                @for($i=1;$i<10;$i++)
                <option value="{{$i}}" {{( $references->count() == $i) ? 'selected': ''}}>{{$i}}</option>
                @endfor
            </select>
       </div>
       <div class="job-references" id="job-references">
            @foreach($references as $k=>$ref)
            @php
            $suffix = '';
            if ( $k == 0) {
                $suffix = 'st';
            }elseif($k == 1)
            {
                $suffix = 'nd';
            }
            elseif($k == 2)
            {
                $suffix = 'rd';
            }
            else{
                $suffix = 'th';
            }
            @endphp
            <div class="reference-details">
                <input type="hidden" value={{$ref->id}} name="old_ids[]">
                <div class="ss-refre-deta-hed">
                    <h6>Details of {{($k+1).$suffix}} Reference</h6>
                </div>

                <div class="ss-form-group">
                    <label>Name </label>
                    <input type="text" name="old_name[]" value="{{$ref->name}}" placeholder="Enter Referred Name">
                </div>

                <div class="ss-form-group">
                    <label>Phone </label>
                    <input type="text" name="old_phone[]" value="{{$ref->phone}}" placeholder="Enter Referred Phone">
                </div>

                <div class="ss-form-group">
                    <label>Email </label>
                <input type="email" name="old_email[]" value="{{$ref->email}}" placeholder="Enter Referred Email">
                </div>

                <div class="ss-form-group">
                    <label>Date referred </label>
                    <input type="date" name="old_date_referred[]" value="{{$ref->date_referred}}">
                </div>

                <div class="ss-form-group">
                    <label>min title of reference </label>
                    <input type="text" name="old_min_title_of_reference[]" value="{{$ref->min_title_of_reference}}" placeholder="What was their title?">
                </div>

                <div class="ss-form-group">
                    <label>recency of reference </label>
                    <select name="old_recency_of_reference[]">
                        <option value="1" {{( $ref->recency_of_reference == '1') ? 'selected': ''}}>Yes</option>
                        <option value="0" {{( $ref->recency_of_reference == '0') ? 'selected': ''}}>No</option>
                    </select>
                </div>
                <div class="ss-file-choose-sec">
                    <div class="ss-form-group fileUploadInput">
                        <label>Upload Doc</label>
                        <input type="file" name="old_image[]">
                        <button>Choose File</button>
                    </div>
                </div>
            </div>
            @endforeach
       </div>

     <div class="ss-prsn-form-btn-sec">
       <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{route('certification')}}" onclick="redirect_to(this)"> Skip </button>
       <button type="submit" class="ss-prsnl-save-btn"> Save </button>
     </div>

      <div>
    </div>
</form>
@stop

@push('form-js')
<script>
    $(document).ready(function(){
        $('input[name="old_phone[]"]').mask('(999) 999-9999');
    });
    /* Job references count */
    var old_references_count;
    old_references_count = $('.job-references input[name="old_name[]"]').length;
    console.log('Number: '+ old_references_count);

    function references(obj)
    {
        let value = $(obj).val();
        let parent = $('.job-references');
        let childElements = parent.children();
        // old_references_count = childElements.length;
        let content = '';
        let suffix;
        let index = 1;
        console.log('Number: '+ old_references_count);
        console.log('Value: '+ value);
        if (old_references_count > 0) {
            index = old_references_count+1;
        }
        if(old_references_count > value)
        {
            total_to_delete = old_references_count - value;
            // Delete the last two elements
            for (let i = 0; i < total_to_delete ; i++) {
                childElements[old_references_count - 1].remove();
                old_references_count--;
            }
        }else{
            console.log('Index: '+ index);
            for (index ; index <= value; index++) {
                content += '<div class="reference-details">';
                if (index == 1) {
                    suffix = 'st';
                }else if(index == 2)
                {
                    suffix = 'nd';
                }else if(index == 3)
                {
                    suffix = 'rd';
                }
                else {
                    suffix = 'th';
                }
                content += '<div class="ss-refre-deta-hed">';
                content += '<h6>Details of '+index+suffix+' Reference</h6>';
                content += '</div>';

                content += '<div class="ss-form-group">';
                content += '<label>Name </label>';
                content += '<input type="text" name="name[]" placeholder="Enter Referred Name">';
                content += '</div>';

                content += '<div class="ss-form-group">';
                content += '<label>Phone </label>';
                content +=  '<input type="text" name="phone[]" placeholder="Enter Referred Phone">';
                content += '</div>';

                content +='<div class="ss-form-group">';
                content += '<label>Email </label>';
                content +='<input type="email" name="email[]" placeholder="Enter Referred Email">';
                content +='</div>';

                content +='<div class="ss-form-group">';
                content +='<label>Date referred </label>';
                content +='<input type="date" name="date_referred[]">';
                content +='</div>';

                content +='<div class="ss-form-group">';
                content +='<label>min title of reference </label>';
                content += '<input type="text" name="min_title_of_reference[]" placeholder="What was their title?">';
                content +='</div>';

                content += '<div class="ss-form-group">';
                content +='<label>recency of reference </label>';
                content +='<select name="recency_of_reference[]">';
                content +='<option value="1">Yes</option>';
                content +='<option value="0">No</option>';
                content +='</select>';
                content += '</div>';

                content +=  '<div class="ss-file-choose-sec">';
                content +=  '<div class="ss-form-group fileUploadInput">';
                content += '<label>Upload Doc</label>';

                content +='<input type="file" name="image[]">';
                content +='<button>Choose File</button>';
                content += '</div>';
                content +='</div>';
                content +='</div>';
                old_references_count++;
            }
            parent.append(content);
            $('input[name="phone[]"]').mask('(999) 999-9999');
            console.log('Number after: '+ old_references_count);
        }

    }
</script>
@endpush

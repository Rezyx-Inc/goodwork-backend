@php
    use Illuminate\Support\Str;
    $uniqueId = $id . '-' . Str::random(8);
@endphp

<span class="container-multiselect" id="{{ $uniqueId }}-container">
    <label for="">{{ isset($label) ? $label : '' }}</label>
    <div class="select-btn" aria-expanded="false">
        <span class="btn-text input-placeholder">{{ isset($placeholder) ? $placeholder : 'Select value' }}</span>
        <span class="arrow-dwn">
            <i class="fa-solid fa-chevron-down"></i>
        </span>
    </div>
    <ul class="list-items" role="listbox" aria-hidden="true">
        @if ($options)
            @foreach ($options as $key => $value)
                @php
                    $title = isset($option_attribute) ? $value->$option_attribute : $value;
                @endphp
                <li class="item-elem" role="option" value="{{ $title }}">
                    <input type="checkbox" id="{{ $id }}-{{ $key }}" name="{{ $name }}-check"
                        value="{{ $title }}"
                        {{ isset($selected) && !empty($selected) && in_array($title, explode(', ', $selected)) ? 'checked' : '' }} />
                    <label for="{{ $id }}-{{ $key }}"
                        style="display: inline; width: 100%;">{{ $title }}</label>
                </li>
            @endforeach
        @endif
    </ul>

    {{-- Hidden input to store selected values --}}
    <input name="{{ $name }}" id="{{ $uniqueId }}-hidden" value="{{ $selected }}" type="hidden">

    <button class="ss-job-dtl-pop-sv-btn" onclick="save_multi_select(this)" data-target="{{ $uniqueId }}"
        data-value="{{ $selected }}" data-name="{{ $name }}" class="ss-btn ss-btn-primary">Save</button>

</span>

<script defer>
    function save_multi_select(obj) {
        let target = $(obj).data('target');
        let value = document.getElementById(target + '-hidden').value;
        let name = $(obj).data('name');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajax({
                url: "{{ route('worker-update-information') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    [name]: value
                },
                success: function(response) {
                    console.log('response', response);
                    if (response.success == true) {
                        notie.alert({
                            type: 'success',
                            text: '<i class="fa fa-check"></i> Information updated successfully',
                            time: 3
                        });
                        $('#' + name + '_modal').modal('hide');
                    } else {
                        notie.alert({
                            type: 'error',
                            text: '<i class="fa fa-times"></i> Something went wrong',
                            time: 3
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('error', xhr, status, error);
                    toastr.error('Something went wrong');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('{{ $uniqueId }}-container');
        const listItems = container.querySelectorAll('.item-elem');
        const placeholder = container.querySelector('.input-placeholder');
        const hiddenInput = container.querySelector(`#{{ $uniqueId }}-hidden`);
        const dropdownBtn = container.querySelector('.select-btn');
        const dropdownList = container.querySelector('.list-items');

        // Function to update hidden input value and placeholder
        function updateHiddenInput() {
            const selectedValues = [];
            container.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                selectedValues.push(checkbox.value);
            });

            // put joined vals to a variable if length > 0 or null
            const joinedVals = selectedValues.length > 0 ? selectedValues.join(', ') : null;

            // Update the hidden input and placeholder
            hiddenInput.value = joinedVals;
            placeholder.textContent = joinedVals ?
                joinedVals :
                '{{ $placeholder ?? 'Select value' }}';


            // Execute the custom onChange function if provided
            const onChangeFunctionName = "{{ $onChange ?? '' }}";
            if (onChangeFunctionName && typeof window[onChangeFunctionName] === 'function') {
                window[onChangeFunctionName]({
                    id: '{{ $id }}',
                    name: '{{ $name }}',
                    value: joinedVals
                });
            }
        }

        // Add change listener to checkboxes to handle selection changes
        container.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateHiddenInput);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!dropdownList.contains(event.target) && !dropdownBtn.contains(event.target)) {
                dropdownBtn.classList.remove('open');
            }
        });

        // Initialize hidden input on page load
        updateHiddenInput();
    });
</script>

<style>
    .container-multiselect .list-items.open {
        display: block;
    }

    .list-items {
        display: none;
        list-style: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        background: #fff;
        max-height: 150px;
        overflow-y: auto;
    }

    .select-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 8px 10px;
        border: 1px solid #ccc;
    }

    .input-placeholder {
        color: #aaa;
    }

    .ss-pers-info-form-mn-dv .ss-form-group .item-elem input {
        width: 15px !important;
        box-shadow: 0px 0px 0px 0px !important;
    }
</style>

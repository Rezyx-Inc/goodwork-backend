{{-- implementation example  --}}
{{-- 
    @include('worker::components.custom_multiple_select_input', [
            'id' => 'worker_job_type',
            'label' => 'Type',
            'placeholder' => 'Select Type',
            'name' => 'worker_job_type',
            'options' => $allKeywords['Type'],
            'option_attribute' => 'title',
            'selected' => old('worker_job_type', $worker->worker_job_type),
        ]) 
--}}


    <span class="container-multiselect" id="{{ $id }}">
        <label for="">{{ isset($label) ? $label : "" }}</label>
        <div class="select-btn">
            <span class="btn-text input-placeholder">{{ isset($placeholder) ? $placeholder : "Select value" }}</span>
            <span class="arrow-dwn">
                <i class="fa-solid fa-chevron-down"></i>
            </span>
        </div>
        <ul class="list-items">
            @if ($options)
                @foreach ($options as $key => $value)
                    @php
                        if (isset($option_attribute)) {
                            $title = $value->$option_attribute;
                        } else {
                            $title = $value;
                        }
                    @endphp
                    
                    <li class="item-elem" value="{{ $title }}">
                        {{-- Input checkbox --}}
                        <input type="checkbox" id="{{ $id }}-{{ $key }}"
                               name="{{ $name }}-check" value="{{ $title }}"
                               {{ isset($selected) && !empty($selected) && in_array($title, explode(', ', $selected)) ? 'checked' : '' }} />
                        <label for="{{ $id }}-{{ $key }}"
                               style="display: inline; width: 100%;">{{ $title }}</label>
                    </li>
                @endforeach
            @endif
        </ul>
    
        {{-- Add an input hidden to store selected values --}}
        <input name="{{ $name }}" id="{{ $id }}-hidden" value="{{ $selected }}" type="hidden">
    </span>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('{{ $id }}');
            const listItems = container.querySelectorAll('.item-elem');
            const placeholder = container.querySelector('.input-placeholder');
            const hiddenInput = container.querySelector(`#{{ $id }}-hidden`);
            const dropdownBtn = container.querySelector('.select-btn');
            const dropdownList = container.querySelector('.list-items');
    
            // Function to update hidden input value
            function updateHiddenInput() {
                const selectedValues = [];
                container.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                    selectedValues.push(checkbox.value);
                });
    
                hiddenInput.value = selectedValues.join(', ');
                placeholder.textContent = selectedValues.length > 0 
                                            ? selectedValues.join(', ') 
                                            : '{{ $placeholder ?? "Select value" }}';
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
        .ss-pers-info-form-mn-dv .ss-form-group .item-elem input {
            width: 15px !important;
            box-shadow: 0px 0px 0px 0px !important;
        }
    </style>
    

{{-- implementation example  --}}
{{-- @include('worker::components.custom_multiple_select_input', [
        'id' => 'Type',
        'label' => 'Select Type',
        'name' => 'Type',
        'options' => $allKeywords['Type'],
        'selected' => old('Type', $allKeywords['Type'][0]->title),
    ]) --}}


<div class="container-multiselect" id="{{ $id }}">
    <div class="select-btn">
        <span class="btn-text">{{ $label }}</span>
        <span class="arrow-dwn">
            <i class="fa-solid fa-chevron-down"></i>
        </span>
    </div>
    <ul class="list-items">
        @if ($options)

            {{-- @dump($options); --}}
            @foreach ($options as $key => $value)
                <li class="item-elem" value="{{ $value->title }}">
                    {{-- input checkbox --}}
                    
                    <input type="checkbox" id="{{ $id }}-{{ $key }}"
                        name="{{ $name }}-check" value="{{ $value->title }}"
                        {{ in_array($value->title, explode(',', $selected)) ? 'checked' : '' }} />
                    <label for="{{ $id }}-{{ $key }}"
                        style="display: inline">{{ $value->title }}</label>

                </li>
            @endforeach
        @endif
    </ul>

    {{-- add an input hidden to store selected values --}}
    <input name="{{ $name }}" id="{{ $id }}-hidden" value="{{ $selected }}" type="hidden">
</div>


<script>
    // Get the container element
    const container = document.getElementById('{{ $id }}');

    // Get all the list items inside the container
    const listItems = container.querySelectorAll('.item-elem');

    // Get the hidden input element
    const hiddenInput = document.getElementById('{{ $id }}-hidden');
    // Add a click event listener to each list item
    listItems.forEach(item => {
        item.addEventListener('click', ( event) => {
            // Toggle the 'active' class on the clicked list item
            item.classList.toggle('active');
            console.log("*********************** item ------- ", item);
            
            // if checked add value to hidden input
            // if (item.classList.contains('active')) {
            //     hiddenInput.value += ',' + item.getAttribute('value');
            //     console.log("*********************** if ----------- ", hiddenInput.value);
            // } else {
            //     hiddenInput.value = hiddenInput.value.replace(',' + item.getAttribute('value'), '');
            //     console.log("*********************** if not ------- ", hiddenInput.value);
            // }
        });
    })
</script>


<style>
    .ss-pers-info-form-mn-dv .ss-form-group .item-elem input {
        width: 15px !important;
        box-shadow: 0px 0px 0px 0px !important;
    }
</style>

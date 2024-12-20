<!-- resources/views/components/modal.blade.php -->
<div class="modal fade ss-jb-dtl-pops-mn-dv" id="{{ $id }}_modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="ss-pop-cls-vbtn">
                <button type="button" class="btn-close" data-target="#{{ $id }}_modal" onclick="close_modal(this)"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body mt-3">
                <div class="ss-form-group">
                    @include('worker::offers.components.custom_multiple_select_input', [
                        'id' => $id,
                        'label' => $label,
                        'placeholder' => $placeholder,
                        'name' => $name,
                        'options' => $options,
                        'option_attribute' => 'title',
                        'selected' => $selected,
                        'onChange' => 'multi_select_change',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
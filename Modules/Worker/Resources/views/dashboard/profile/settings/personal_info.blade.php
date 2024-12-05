<div class="col-md-12 mb-4 collapse-container">
    <p>
        <a class="btn first-collapse" data-toggle="collapse" href="#collapse-1" role="button" aria-expanded="false"
            aria-controls="collapseExample">
            Identity & Tax Info
        </a>
    </p>
</div>

<div class="row collapse" id="collapse-1">
    <div class="row justify-content-center">
        
        {{-- nurse_classification --}}
        <div class="ss-form-group">
            <label>Classifications</label>
            <select name="nurse_classification" id="nurse_classification">

                <option value="" {{ empty($worker->nurse_classification) ? 'selected' : '' }} disabled hidden>
                    Select Nurse Classification
                </option>
                @foreach ($allKeywords['NurseClassification'] as $value)
                    <option value="{{ $value->title }}"
                        {{ !empty($worker->nurse_classification) && $worker->nurse_classification == $value->title ? 'selected' : '' }}>
                        {{ $value->title }}
                    </option>
                @endforeach
            </select>
            <span class="help-block-nurse_classification"></span>
        </div>
        {{-- End nurse_classification  --}}

        {{-- First Name --}}
        <div class="ss-form-group">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="Please enter your first name"
                value="{{ isset($user->first_name) ? $user->first_name : '' }}">
        </div>
        <span class="help-block-first_name"></span>

        {{-- Last Name --}}
        <div class="ss-form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Please enter your last name"
                value="{{ isset($user->last_name) ? $user->last_name : '' }}">
        </div>
        <span class="help-block-last_name"></span>

        {{-- State Information --}}
        <div class="ss-form-group">
            <label>State</label>
            <select name="state" id="job_state">
                <option value="{{ !empty($worker->state) ? $worker->state : '' }}" disabled selected hidden>
                    {{ !empty($worker->state) ? $worker->state : 'What State are you located in?' }}
                </option>
                @foreach ($states as $state)
                    <option id="{{ $state->id }}" value="{{ $state->name }}">
                        {{ $state->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <span class="help-block-state"></span>
        
        {{-- City Information --}}
        <div class="ss-form-group">
            <label>City</label>
            <select name="city" id="job_city">
                <option value="{{ !empty($worker->city) ? $worker->city : '' }}" disabled selected hidden>
                    {{ !empty($worker->city) ? $worker->city : 'What City are you located in?' }}
                </option>
            </select>
        </div>
        <span class="help-block-city"></span>
        <span class="help-city">Please select a state first</span>

        {{-- Zip Code Information --}}
        <div class="ss-form-group">
            <label>Zip Code</label>
            <input type="number" name="zip_code" placeholder="Please enter your Zip Code"
                value="{{ isset($user->zip_code) ? $user->zip_code : '' }}">
        </div>
        <span class="help-block-zip_code"></span>

        {{-- Email --}}
        <div class="ss-form-group">
            <label>Email</label>
            <input id="email" type="text" name="email" placeholder="Please enter your Email"
                value="{{ isset($user->email) ? $user->email : '' }}">
        </div>

        {{-- Phone Number --}}
        <div class="ss-form-group">
            <label>Phone Number</label>
            <input id="mobile" type="text" name="mobile" placeholder="Please enter your phone number"
                value="{{ isset($user->mobile) ? $user->mobile : '' }}">
        </div>
        <span class="help-block-mobile"></span>
        {{-- Address Information --}}
        <div class="ss-form-group">
            <label>Permanent Tax Home (Address)</label>
            <input type="text" name="address" placeholder="Please enter your address"
                value="{{ isset($worker->address) ? $worker->address : '' }}">
        </div>
        <span class="help-block-address"></span>

    </div>
</div>

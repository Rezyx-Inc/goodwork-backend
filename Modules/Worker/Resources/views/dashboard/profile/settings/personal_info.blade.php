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
        {{-- Phone Number --}}
        <div class="ss-form-group">
            <label>Phone Number</label>
            <input id="contact_number" type="text" name="mobile" placeholder="Please enter your phone number"
                value="{{ isset($user->mobile) ? $user->mobile : '' }}">
        </div>
        <span class="help-block-mobile"></span>
        {{-- Address Information --}}
        <div class="ss-form-group">
            <label>Address</label>
            <input type="text" name="address" placeholder="Please enter your address"
                value="{{ isset($worker->address) ? $worker->address : '' }}">
        </div>
        <span class="help-block-address"></span>
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

    </div>
</div>

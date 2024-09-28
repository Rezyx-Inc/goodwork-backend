@extends('layouts.profile')
@section('mytitle', 'My Profile')

@section('form')
    <form action="{{ route('my-profile.store') }}" class="redirect-after-submit">
        <div class="ss-persnl-frm-hed">
            <p><span><img src="{{ URL::asset('frontend/img/my-per--con-user.png') }}" /></span>Professional Information</p>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Overtime </label>
                <select name="worker_overtime" class="form-control">
                    <option value="">Select</option>
                    <option value="yes" {{ $model->worker_overtime == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ $model->worker_overtime == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>


        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Holiday</label>
                <input type="date" name="worker_holiday" value="{{ $model->worker_holiday }}">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>On Call</label>
                <select name="worker_on_call" class="form-control">
                    <option value="">Will you do call?</option>
                    <option value="yes" {{ $model->worker_on_call == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ $model->worker_on_call == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Call Back</label>
                {{-- <input type="text" name="worker_call_back" placeholder="Is this rate reasonable?"> --}}
                <select name="worker_call_back" class="form-control">
                    <option value="">Is this rate reasonable?</option>
                    <option value="yes" {{ $model->worker_call_back == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ $model->worker_call_back == 'No' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>


        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Orientation Rate</label>
                <input type="text" name="worker_orientation_rate" value="{{ $model->worker_orientation_rate }}"
                    placeholder="-">
            </div>
        </div>


        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Weekly Taxable amount</label>
                <input type="text" name="worker_weekly_taxable_amount"
                    value="{{ $model->worker_weekly_taxable_amount }}" placeholder="Are you going to duplicate expenses?">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Weekly non-taxable amount</label>
                <input type="text" name="worker_weekly_non_taxable_amount"
                    value="{{ $model->worker_weekly_non_taxable_amount }}"
                    placeholder="Are you going to duplicate expenses?">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Organization Weekly Amount</label>
                <input type="text" name="worker_organization_weekly_amount"
                    value="{{ $model->worker_organization_weekly_amount }}" placeholder="What range is reasonable?">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Goodwork Weekly Amount</label>
                <input type="text" name="worker_goodwork_weekly_amount"
                    value="{{ $model->worker_goodwork_weekly_amount }}"
                    placeholder="you only have (count down time) left before your rate drops from 5% to 2%">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Total Organization Amount</label>
                <input type="text" name="worker_total_organization_amount"
                    value="{{ $model->worker_total_organization_amount }}" placeholder="-">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Total Goodwork Amount</label>
                <input type="text" name="worker_total_goodwork_amount"
                    value="{{ $model->worker_total_goodwork_amount }}"
                    placeholder="How would you spend those extra funds?">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Total Contract Amount</label>
                <input type="text" name="worker_total_contract_amount"
                    value="{{ $model->worker_total_contract_amount }}" placeholder="--">
            </div>
        </div>

        <div class="ss-form-live-location">
            <div class="ss-form-group">
                <label>Goodwork Number</label>
                <input type="text" name="text" placeholder="---">
            </div>
        </div>

        <div class="ss-prsn-form-btn-sec">
            <button type="button" class="ss-prsnl-skip-btn" id="skip-button" data-href="{{ route('vaccination') }}"
                onclick="redirect_to(this)"> Skip </button>
            <button type="submit" class="ss-prsnl-save-btn"> Save & Next </button>
        </div>

    </form>
@stop

@push('form-js')
    <script></script>
@endpush

<?php

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OffersTableSeeder extends Seeder
{
    public function run()
    {
        Offer::create([
            'id' => Str::uuid(),
            'job_name' => 'Nurse Practitioner',
            'type' => 'Clinical',
            'status' => 'Apply',
            'terms' => 'Permanent',
            'profession' => 'Nursing',
            'block_scheduling' => true,
            'float_requirement' => false,
            'facility_shift_cancelation_policy' => '24 hours notice',
            'contract_termination_policy' => '30 days notice',
            'traveler_distance_from_facility' => '10 miles',
            'job_id' => 'GWJ000001',
            'recruiter_id' => 'GWU000006',
            'worker_user_id' => 'GWW000001',
            'clinical_setting' => 'Hospital',
            'Patient_ratio' => 4.0,
            'emr' => 'Epic',
            'Unit' => 'ICU',
            'scrub_color' => 'Blue',
            'start_date' => '2022-01-01',
            'as_soon_as' => true,
            'rto' => 'RTO 1',
            'hours_per_week' => 40.0,
            'guaranteed_hours' => 40.0,
            'hours_shift' => 8.0,
            'weeks_shift' => 5.0,
            'preferred_assignment_duration' => 3.0,
            'referral_bonus' => 100.0,
            'sign_on_bonus' => 500.0,
            'completion_bonus' => 500.0,
            'extension_bonus' => 100.0,
            'other_bonus' => 50.0,
            'four_zero_one_k' => true,
            'health_insaurance' => true,
            'dental' => true,
            'vision' => true,
            'actual_hourly_rate' => 20.0,
            'overtime' => 30.0,
            'holiday' => 40.0,
            'on_call' => 10.0,
            'orientation_rate' => 20.0,
            'weekly_non_taxable_amount' => 100.0,
            'description' => 'Nurse Practitioner for ICU',
            'weekly_taxable_amount' => 500.0,
            'employer_weekly_amount' => 1000.0,
            'goodwork_weekly_amount' => 1500.0,
            'total_employer_amount' => 2000.0,
            'total_goodwork_amount' => 2500.0,
            'total_contract_amount' => 3000.0,
            'weekly_pay' => 3500.0,
            'is_draft' => false,
            'is_counter' => false,
            'created_by' => 'GWU000005'
        ]);



        Offer::create([
            'id' => Str::uuid(),
            'job_name' => 'Physician Assistant',
            'type' => 'Non clinical',
            'status' => 'Apply',
            'terms' => 'Contract',
            'profession' => 'Medical',
            'block_scheduling' => false,
            'float_requirement' => true,
            'facility_shift_cancelation_policy' => '48 hours notice',
            'contract_termination_policy' => '60 days notice',
            'traveler_distance_from_facility' => '15 miles',
            'job_id' => 'GWJ000001',
            'recruiter_id' => 'GWU000006',
            'worker_user_id' => 'GWW000001',
            'clinical_setting' => 'Clinic',
            'Patient_ratio' => 6.0,
            'emr' => 'Cerner',
            'Unit' => 'ER',
            'scrub_color' => 'Green',
            'start_date' => '2022-02-01',
            'as_soon_as' => false,
            'rto' => 'RTO 2',
            'hours_per_week' => 20.0,
            'guaranteed_hours' => 20.0,
            'hours_shift' => 4.0,
            'weeks_shift' => 5.0,
            'preferred_assignment_duration' => 6.0,
            'referral_bonus' => 200.0,
            'sign_on_bonus' => 600.0,
            'completion_bonus' => 600.0,
            'extension_bonus' => 200.0,
            'other_bonus' => 100.0,
            'four_zero_one_k' => false,
            'health_insaurance' => false,
            'dental' => false,
            'vision' => false,
            'actual_hourly_rate' => 25.0,
            'overtime' => 35.0,
            'holiday' => 45.0,
            'on_call' => 15.0,
            'orientation_rate' => 25.0,
            'weekly_non_taxable_amount' => 200.0,
            'description' => 'Physician Assistant for ER',
            'weekly_taxable_amount' => 600.0,
            'employer_weekly_amount' => 1200.0,
            'goodwork_weekly_amount' => 1800.0,
            'total_employer_amount' => 2400.0,
            'total_goodwork_amount' => 3000.0,
            'total_contract_amount' => 3600.0,
            'weekly_pay' => 4200.0,
            'is_draft' => true,
            'is_counter' => true,
            'created_by' => 'GWU000005'
        ]);




        // Offer::create([
        //     'id' => Str::uuid(),
        //     'nurse_id' => 'GWW000001',
        //     'created_by' => 'GWU000005',
        //     'status' => 'Pending',
        //     'active' => 1,
        //     'expiration' => now()->addDays(30),
        //     'job_id' => 'GWJ000002',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}


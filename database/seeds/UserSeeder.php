<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Nurse;
use App\Models\Availability;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
        
        // Admin is the first User created in the system, and it has all permissions, its id is "GWU000001"

        $admin = User::create([
			'id' => Str::uuid(),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'fulladmin@nurseify.io',
            'user_name' => 'fulladmin@nurseify.io',
			'password' => Hash::make('IMC_2020_Ankur'),
            'mobile' => '1234657890'
        ]);

        // we seed this employer with the id "GWU000002" just for testing jobs, this record related to default seeding jobs

        $employer = User::create([
			'id' => Str::uuid(),
            'first_name' => 'employer',
            'last_name' => 'emp',
            'email' => 'employer@gmail.com',
            'ROLE'=>'EMPLOYER',
            'user_name' => 'employer',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

        // we seed this workers with the id "GWU000003" & "GWU000004" just for testing messages, this record related to default seeding messages


        $worker1 = User::create([
            'id' => Str::uuid(),
            'first_name' => 'worker',
            'last_name' => 'one',
            'email' => 'worker1@gmail.com',
            'ROLE'=>'WORKER',
            'user_name' => 'worker1',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

        $worker2 = User::create([
            'id' => Str::uuid(),
            'first_name' => 'worker',
            'last_name' => 'two',
            'email' => 'worker2@gmail.com',
            'ROLE'=>'WORKER',
            'user_name' => 'worker2',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

        // we seed this recruiter with the id "GWU000005" just for testing messages, this record related to default seeding messages

        $recruiter = User::create([
            'id' => Str::uuid(),
            'first_name' => 'recruiter',
            'last_name' => 'rec',
            'email' => 'recruiter@gmail.com',
            'ROLE'=>'RECRUITER',
            'user_name' => 'recruiter',
            'mobile' => '+1 (555) 555-55',
            'facility_id' => '1'
        ]);

        // we seed this nurse with the id "GWU000004" just for testing messages, this record related to loading offers


        $nurse = Nurse::create([
            'id' => Str::uuid(),
            'user_id' => 'GWU000004',
            'specialty' => 'Pediatric Nursing',
            'nursing_license_state' => 'CA',
            'nursing_license_number' => 'RN12345678',
            'highest_nursing_degree' => 'nursing degree',
            'serving_preceptor' => 1,
            'serving_interim_nurse_leader' => 0,
            'leadership_roles' => NULL,
            'address' => '123 Main St',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'postcode' => '90001',
            'country' => 'USA',
            'hourly_pay_rate' => '40.00',
            'experience_as_acute_care_facility' => 5.0,
            'experience_as_ambulatory_care_facility' => 3.0,
            'active' => 1,
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'ehr_proficiency_cerner' => null,
            'ehr_proficiency_meditech' => null,
            'ehr_proficiency_epic' => null,
            'ehr_proficiency_other' => null,
            'summary' => 'Experienced pediatric nurse with excellent patient care skills...',
            'nurses_video' => 'http://example.com/video',
            'nurses_facebook' => 'http://facebook.com/nurseprofile',
            'nurses_twitter' => 'http://twitter.com/nurseprofile',
            'nurses_linkedin' => 'http://linkedin.com/in/nurseprofile',
            'nurses_instagram' => 'http://instagram.com/nurseprofile',
            'nurses_pinterest' => 'http://pinterest.com/nurseprofile',
            'nurses_tiktok' => 'http://tiktok.com/@nurseprofile',
            'nurses_sanpchat' => 'http://snapchat.com/add/nurseprofile',
            'nurses_youtube' => 'http://youtube.com/c/nurseprofile',
            'clinical_educator' => 0,
            'is_daisy_award_winner' => 1,
            'employee_of_the_mth_qtr_yr' => 0,
            'other_nursing_awards' => 0,
            'is_professional_practice_council' => 1,
            'is_research_publications' => 0,
            'mu_specialty' => 'Cardiology',
            'additional_photos' => 'http://example.com/photos',
            'languages' => 'English, Spanish',
            'additional_files' => 'http://example.com/files',
            'college_uni_name' => 'University of Health Sciences',
            'college_uni_city' => 'Los Angeles',
            'college_uni_state' => 'CA',
            'college_uni_country' => 'USA',
            'facility_hourly_pay_rate' => '45.00',
            'n_lat' => '34.0522',
            'n_lang' => '-118.2437',
            'resume' => 'http://example.com/resume.pdf',
            'nu_video' => 'http://example.com/nursevideo',
            'nu_video_embed_url' => 'http://example.com/nursevideoembed',
            'is_verified' => 1,
            'gig_account_id' => 'GIG12345',
            'is_gig_invite' => 0,
            'gig_account_create_date' => now(),
            'gig_account_invite_date' => now(),
            'diploma' => $faker->word,
            'driving_license' => $faker->bothify('??######'),
            'worked_at_facility_before' => $faker->word,
            'worker_ss_number' => $faker->bothify('###-##-####'),
            'block_scheduling' => $faker->word,
            'float_requirement' => $faker->boolean,
            'facility_shift_cancelation_policy' => $faker->sentence,
            'contract_termination_policy' => $faker->sentence,
            'distance_from_your_home' => $faker->randomDigitNotNull,
            'clinical_setting_you_prefer' => $faker->word,
            'worker_patient_ratio' => $faker->randomDigitNotNull,
            'worker_emr' => $faker->word,
            'worker_unit' => $faker->word,
            'worker_scrub_color' => $faker->safeColorName,
            'worker_interview_dates' => $faker->date,
            'worker_start_date' => $faker->date,
            'worker_as_soon_as_posible' => $faker->date,
            'worker_shift_time_of_day' => $faker->time,
            'worker_hours_per_week' => $faker->randomDigitNotNull,
            'worker_guaranteed_hours' => $faker->randomDigitNotNull,
            'worker_weeks_assignment' => $faker->randomDigitNotNull,
            'worker_shifts_week' => $faker->randomDigitNotNull,
            'worker_referral_bonus' => $faker->randomDigitNotNull,
            'worker_sign_on_bonus' => $faker->randomDigitNotNull,
            'worker_completion_bonus' => $faker->randomDigitNotNull,
            'worker_extension_bonus' => $faker->randomDigitNotNull,
            'worker_other_bonus' => $faker->randomDigitNotNull,
            'how_much_k' => $faker->randomDigitNotNull,
            'worker_health_insurance' => $faker->boolean,
            'worker_dental' => $faker->boolean,
            'worker_vision' => $faker->boolean,
            'worker_actual_hourly_rate' => $faker->randomFloat(2, 10, 50),
            'worker_overtime' => $faker->randomFloat(2, 10, 50),
            'worker_holiday' => $faker->randomFloat(2, 10, 50),
            'worker_on_call' => $faker->randomFloat(2, 10, 50),
            'worker_call_back' => $faker->randomFloat(2, 10, 50),
            'worker_orientation_rate' => $faker->randomFloat(2, 10, 50),
            'worker_weekly_taxable_amount' => $faker->randomFloat(2, 100, 1000),
            'worker_employer_weekly_amount' => $faker->randomFloat(2, 100, 1000),
            'worker_weekly_non_taxable_amount' => $faker->randomFloat(2, 100, 1000),
        ]);
        $availability = Availability::create([
            'nurse_id' => 'GWW000001',
        ]);
    
    }
}

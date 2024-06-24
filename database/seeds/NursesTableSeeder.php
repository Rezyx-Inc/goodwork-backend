<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Nurse;
class NursesTableSeeder extends Seeder
{
    public function run()
    {

        Nurse::create([
            'id' => Str::uuid(),        
            'user_id' => 'GWU000006',
            'specialty' => 'Pediatric Nursing',
            'nursing_license_state' => 'CA',
            'nursing_license_number' => 'RN12345678',
            'highest_nursing_degree' => 4,
            'serving_preceptor' => 1,
            'serving_interim_nurse_leader' => 0,
            'leadership_roles' => 2,
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
            'gig_account_invite_date' => now()
        ]);
    }
}

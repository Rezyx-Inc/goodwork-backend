<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Job;

class JobsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $nursingSpecialties = [
            'Registered Nurse',
            'Licensed Practical Nurse',
            'Certified Nursing Assistant',
            'Nurse Practitioner',
            'Clinical Nurse Specialist',
            'Nurse Anesthetist',
            'Nurse Midwife',
            'Psychiatric Nurse',
            'Geriatric Nurse',
            'Pediatric Nurse',
            'Orthopedic Nurse',
            'Oncology Nurse',
            'Intensive Care Unit Nurse',
            'Emergency Room Nurse',
            'Surgical Nurse',
            'Labor and Delivery Nurse',
            'Home Health Nurse',
            'Hospice Nurse',
            'Ambulatory Care Nurse',
            'Public Health Nurse'

        ];

        $nursingProfessions = [
            'Nurse Manager',
            'Nurse Educator',
            'Geriatric Care Manager',
            'Clinical Nurse Leader',
            'Nurse Researcher',
            'Public Health Nurse',
            'Travel Nurse',
            'Forensic Nurse',
            'Legal Nurse Consultant',
            'Nurse Entrepreneur',
            'Pediatric Nurse Practitioner',
            'Neonatal Nurse',
            'Critical Care Nurse',
            'Orthopedic Nurse',
            'Hospice Nurse',
            'Dialysis Nurse',
            'Occupational Health Nurse',
            'Ambulatory Care Nurse',
            'Community Health Nurse',
            'Cardiac Nurse',

        ];

        $preferredWorkLocations = [
            'New York, NY',
            'Los Angeles, CA',
            'Chicago, IL',
            'Houston, TX',
            'Phoenix, AZ',
            'Philadelphia, PA',
            'San Antonio, TX',
            'San Diego, CA',
            'Dallas, TX',
            'San Jose, CA',
            'Austin, TX',
            'Jacksonville, FL',
            'San Francisco, CA',
            'Indianapolis, IN',
            'Columbus, OH',
            'Fort Worth, TX',
            'Charlotte, NC',
            'Seattle, WA',
            'Denver, CO',
            'Washington, D.C.',

        ];


        // Adjust the number of jobs you want to create
        $numberOfJobs = 10;

        for ($i = 0; $i < $numberOfJobs; $i++) {
            JOB::create([
                'id' => Str::uuid(),
                'preferred_specialty' => $nursingSpecialties[array_rand($nursingSpecialties)],
                'preferred_assignment_duration' => $faker->numberBetween(1, 10),
                'preferred_shift_duration' => $faker->numberBetween(1, 8),
                'preferred_work_location' => $preferredWorkLocations[array_rand($preferredWorkLocations)],
                'preferred_work_area' => $faker->numberBetween(1, 10),
                'preferred_days_of_the_week' => $faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
                'preferred_hourly_pay_rate' => $faker->randomFloat(2, 10, 50),
                'preferred_experience' => $faker->numberBetween(3, 10),
                'description' => $faker->paragraph,
                'created_by' => 'GWU000002',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => $faker->slug,
                'active' => $faker->boolean,
                'job_video' => $faker->url,
                'seniority_level' => $faker->numberBetween(1, 5),
                'job_function' => $faker->numberBetween(1, 10),
                'responsibilities' => $faker->paragraph,
                'qualifications' => $faker->paragraph,
                'job_cerner_exp' => $faker->numberBetween(1, 5),
                'job_meditech_exp' => $faker->numberBetween(1, 5),
                'job_epic_exp' => $faker->numberBetween(1, 5),
                'job_other_exp' => $faker->text(100),
                'job_photos' => $faker->imageUrl(),
                'video_embed_url' => $faker->url,
                'is_open' => $faker->boolean,
                'recruiter_id' => Str::uuid(),
                'job_name' => $faker->word,
                'proffesion' => $nursingProfessions[array_rand($nursingProfessions)],
                'preferred_shift' => $faker->word,
                'job_city' => $faker->city,
                'job_state' => $faker->state,
                'job_type' => $faker->randomElement(['Full-time', 'Part-time', 'Contract']),
                'weekly_pay' => $faker->randomFloat(2, 500, 1500),
                'start_date' => $faker->date(),
                'end_date' => $faker->date(),
                'hours_shift' => $faker->numberBetween(4, 12),
                'hours_per_week' => $faker->numberBetween(20, 50),
                'auto_offers' => $faker->numberBetween(0, 10),
                'is_hidden' => $faker->boolean,
                'is_closed' => $faker->boolean,
            ]);
        }
    }
}

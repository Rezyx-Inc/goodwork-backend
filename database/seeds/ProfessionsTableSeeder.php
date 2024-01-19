<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfessionsTableSeeder extends Seeder
{
    public function run()
    {
        $professions = [
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

        foreach ($professions as $profession) {
            DB::table('professions')->insert([
                'id' => Str::uuid(),
                'full_name' => $profession,
                'short_name' => strtolower(str_replace(' ', '_', $profession)),
            ]);
        }
    }
}

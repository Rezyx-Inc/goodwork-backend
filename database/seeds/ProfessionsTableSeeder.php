<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfessionsTableSeeder extends Seeder
{
    public function run()
    {
        $professions = [
            'Worker Manager',
            'Worker Educator',
            'Geriatric Care Manager',
            'Clinical Worker Leader',
            'Worker Researcher',
            'Public Health Worker',
            'Travel Worker',
            'Forensic Worker',
            'Legal Worker Consultant',
            'Worker Entrepreneur',
            'Pediatric Worker Practitioner',
            'Neonatal Worker',
            'Critical Care Worker',
            'Orthopedic Worker',
            'Hospice Worker',
            'Dialysis Worker',
            'Occupational Health Worker',
            'Ambulatory Care Worker',
            'Community Health Worker',
            'Cardiac Worker',
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

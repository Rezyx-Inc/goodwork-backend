<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfessionsTableSeeder extends Seeder
{
    public function run()
    {
        $clinical_professions = [
            'RN',
            'CNA',
            'CMA',
            'Tech / Assist',
            'Therapy',
            'Physicians',
            'PA',
            'CRNA',
            'NP',
            'LPN / LVN',
            'Social Work',
            'Other Clinician'
        ];

        foreach ($clinical_professions as $index => $profession) {
            DB::table('professions')->insert([
                'full_name' => $profession,
                'short_name' => strtolower(str_replace(' ', '_', $profession)),
                'is_clinical' => true
            ]);
        }

        $non_clinical_professions = [
            'Academic',
            'Accounting',
            'Clerical',
            'Engineering',
            'Executive',
            'Food Service',
            'Health Sciences',
            'Hr/Payroll',
            'Information Technology',
            'Janitorial',
            'Light Industrial',
            'Management',
            'Medical Coding',
            'Medical Filing and Records Management',
            'Medical Laboratory',
            'Mid-Revenue Cycle Solutions',
            'Security'
        ];

        foreach ($non_clinical_professions as $index => $profession) {
            DB::table('professions')->insert([
                'full_name' => $profession,
                'short_name' => strtolower(str_replace(' ', '_', $profession)),
                'is_clinical' => false
            ]);
        }
    }
}

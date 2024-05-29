<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecialitiesTableSeeder extends Seeder
{
    public function run()
    {
        $specialties = [
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

        foreach ($specialties as $specialty) {
            $professionId = DB::table('professions')->inRandomOrder()->value('id');
            DB::table('specialities')->insert([
                'id' => Str::uuid(),
                'Profession_id' => $professionId,
                'full_name' => $specialty,
                'short_name' => strtolower(str_replace(' ', '_', $specialty)),
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecialitiesTableSeeder extends Seeder
{
    public function run()
    {
        $specialties = [
            'Registered Worker',
            'Licensed Practical Worker',
            'Certified Nursing Assistant',
            'Worker Practitioner',
            'Clinical Worker Specialist',
            'Worker Anesthetist',
            'Worker Midwife',
            'Psychiatric Worker',
            'Geriatric Worker',
            'Pediatric Worker',
            'Orthopedic Worker',
            'Oncology Worker',
            'Intensive Care Unit Worker',
            'Emergency Room Worker',
            'Surgical Worker',
            'Labor and Delivery Worker',
            'Home Health Worker',
            'Hospice Worker',
            'Ambulatory Care Worker',
            'Public Health Worker'
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

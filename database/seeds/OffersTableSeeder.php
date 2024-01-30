<?php

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OffersTableSeeder extends Seeder
{
    public function run()
    {
        Offer::create([
            'id' => Str::uuid(),
            'nurse_id' => 'GWW000001',
            'created_by' => 'GWU000002',
            'status' => 'Pending',
            'active' => 1,
            'expiration' => now()->addDays(30),
            'job_id' => 'GWJ000001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Offer::create([
            'id' => Str::uuid(),
            'nurse_id' => 'GWW000001',
            'created_by' => 'GWU000005',
            'status' => 'Pending',
            'active' => 1,
            'expiration' => now()->addDays(30),
            'job_id' => 'GWJ000002',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboredgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $credentials = [
            // [
            //     "UWU975541",
            //     'Newemp1!',
            //     'kirsten@qualityclinicians.com',
            //     'Quality',
            //     'nexus'
            // ],
            [
                "UWU445837",
                'API_VITALINK_GOODWORK_12262024',
                'api_vitalink_goodwork',
                'vitalink',
                'nexus'
            ]
        ];

        foreach ($credentials as $credential) {
            DB::table('laboredge')->insert([
                'user_id' => $credential[0],
                'le_password' => $credential[1],
                'le_username' => $credential[2],
                'le_organization_code' => $credential[3],
                'le_client_id' => $credential[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
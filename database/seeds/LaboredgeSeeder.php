<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboredgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('laboredge')->insert([
            'user_id' => "UWU975541",
            'le_password' => 'Newemp1!',
            'le_username' => 'kirsten@qualityclinicians.com',
            'le_organization_code' => 'Quality',
            'le_client_id' => 'nexus'
        ]);
    }
}
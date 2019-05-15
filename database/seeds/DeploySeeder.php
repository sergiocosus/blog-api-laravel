<?php

use Illuminate\Database\Seeder;

class DeploySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UpdateLaratrustSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}

<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\PageSetting::$validConfigs as $config) {
            $pageSetting = \App\Models\PageSetting::whereName($config['name'])
                ->first();
            if (!$pageSetting) {
                $pageSetting = new \App\Models\PageSetting();
            }

            $pageSetting->fill($config)->save();
        }
    }
}

<?php

use App\Core\PageSetting;
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
        foreach (PageSetting::$validConfigs as $config) {
            $pageSetting = PageSetting::whereName($config['name'])
                ->first();
            if (!$pageSetting) {
                $pageSetting = new PageSetting();
            }

            $pageSetting->fill($config)->save();
        }
    }
}

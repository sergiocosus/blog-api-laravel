<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SetPageSettingRequest;
use App\Models\PageSetting;

class PageSettingsController extends Controller {

    public function get() {
        $page_settings = PageSetting::get();

        return compact('page_settings');
    }

    public function update(SetPageSettingRequest $request) {
        foreach ($request->settings as $setting) {
            $pageSetting = PageSetting::whereName($setting['name'])->first();
            if (!$pageSetting) {
                $pageSetting = new PageSetting();
            }

            $pageSetting->fill($setting)->save();
        }

        return $this->get();
    }
}

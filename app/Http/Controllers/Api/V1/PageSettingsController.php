<?php


namespace App\Http\Controllers\Api\V1;


use App\Core\PageSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SetPageSettingRequest;
use Arr;

class PageSettingsController extends Controller {

    public function get() {
        $page_settings = PageSetting::with('media')
            ->get();

        return compact('page_settings');
    }

    public function update(SetPageSettingRequest $request) {
        foreach ($request->settings as $setting) {
            $pageSetting = PageSetting::whereName($setting['name'])->first();
            if (!$pageSetting) {
                $pageSetting = new PageSetting();
            }

            $pageSetting->fill($setting)->save();

            if ($picture = Arr::get($setting, 'picture')) {
                $pageSetting->addMediaFromBase64($picture['base64'])
                    ->setFileName($picture['name'])
                    ->preservingOriginal()
                    ->toMediaCollection('main');
            }
        }



        return $this->get();
    }
}

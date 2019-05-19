<?php

namespace App\Http\Requests\Settings;

use App\Core\PageSetting;
use Arr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetPageSettingRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->user()->can('update-setting');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'settings.*.name'    => Rule::in(Arr::pluck(PageSetting::$validConfigs, 'name')),
            'settings.*.type'    => Rule::in(Arr::pluck(PageSetting::$validConfigs, 'type')),
            'settings.*.content' => '',
        ];
    }
}

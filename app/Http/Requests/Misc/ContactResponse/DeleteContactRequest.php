<?php

namespace App\Http\Requests\Misc\ContactResponse;

use App\Core\Security\PermissionsConst;
use Illuminate\Foundation\Http\FormRequest;

class DeleteContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can(PermissionsConst::deleteContactResponse);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

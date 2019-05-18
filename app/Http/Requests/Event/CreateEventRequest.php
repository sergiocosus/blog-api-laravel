<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create-event');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required',
            'begin_at' => 'required',
            'end_at' => 'required',
            'notify_at' => '',
            'description' => 'required',
            'picture' => '',
            'address' => 'required',
            'latitude' => '',
            'longitude' => '',
        ];
    }
}

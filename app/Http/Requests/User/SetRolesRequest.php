<?php

namespace App\Http\Requests\User;

use App\Core\Security\PermissionsConst;
use App\Core\Security\Role;
use Illuminate\Foundation\Http\FormRequest;

class SetRolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        logger($this->user()->roles()->first()->permissions()->pluck('name'));

        foreach ($this->role_names as $roleName) {
            if (! $this->user()->can('set-'.$roleName.'-user-role')) {
                logger('set-'.$roleName.'-user-role');

                return false;
            }
        }

        if ($this->user()->is($this->user)) {
            $unasignableRoles = [
                Role::ADMINISTRATOR,
                Role::SUPER_ADMINISTRATOR,
            ];
            foreach ($unasignableRoles as $role) {
                if ($this->user()->can('set-'.$role.'-user-role') &&
                    ! in_array($role, $this->role_names)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [//
        ];
    }
}

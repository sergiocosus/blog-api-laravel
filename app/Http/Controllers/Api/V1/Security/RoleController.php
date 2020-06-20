<?php

namespace App\Http\Controllers\Api\V1\Security;

use App\Core\Security\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function get()
    {
        $roles = Role::get();

        return compact('roles');
    }
}

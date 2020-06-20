<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20/03/19
 * Time: 12:56 PM
 */

namespace App\Core\Security;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    const SUPER_ADMINISTRATOR = 'super-administrator';
    const ADMINISTRATOR = 'administrator';
    const USER = 'user';
}

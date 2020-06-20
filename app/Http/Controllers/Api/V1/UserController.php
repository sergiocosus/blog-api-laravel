<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\User\SetRolesRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    /**
     * Return the users.
     */
    public function index(Request $request) {
        $paginated_users = User::withCount([
            'comments',
            'posts',
        ])->with('roles')->latest()
            ->paginate($request->get('limit', 20));

        return compact('paginated_users');
    }

    /**
     * Return the specified resource.
     */
    public function show(User $user) {
        return compact('user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request) {
        $user = $request->user();

        if ($request->filled('password')) {
            $request->merge([
                'password' => Hash::make($request->input('password')),
            ]);
        }

        $user->update($request->only([
            'name',
            'email',
        ]));

        if ($request->profile) {
            $user->addMediaFromBase64($request->profile['base64'])
                ->preservingOriginal()
                ->setFileName($request->profile['name'])
                ->toMediaCollection('profile');;
        }

        return compact('user');
    }

    public function setRoles(User $user, SetRolesRequest $request)
    {
        $user->syncRoles($request->role_names);
        $roles = $user->roles()->get();

        return compact('roles');
    }
}

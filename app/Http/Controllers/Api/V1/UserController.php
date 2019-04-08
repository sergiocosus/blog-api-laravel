<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    /**
     * Return the users.
     */
    public function index(Request $request): ResourceCollection {
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
            'last_name',
            'email',
            'password',
        ]));

        logger($request->profile);
        logger($request);

        if ($request->profile) {
            $user->addMediaFromBase64($request->profile['base64'])
                ->preservingOriginal()
                ->setName($request->profile['name'])
                ->toMediaCollection('profile');;
        }

        return compact('user');
    }
}

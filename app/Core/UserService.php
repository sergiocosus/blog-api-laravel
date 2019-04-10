<?php


namespace App\Core;


use App\User;
use Hash;

class UserService {

    public function createUser($data) {
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function createForSocial($data) {
        return \DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);
            logger('created!');

            $this->addProfilePictureFromUrl($user, $data);

            return $user;
        });

    }

    public function addProfilePictureFromUrl(User $user, $data) {

        logger($data);
        logger('sdf');
        if (isset($data['picture_url'])) {
            logger($data['picture_url']);

            $user->addMediaFromUrl($data['picture_url'])
                ->preservingOriginal()
                ->toMediaCollection('profile');;
        }
    }
}

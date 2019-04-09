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
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }
}

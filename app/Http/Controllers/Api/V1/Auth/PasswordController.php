<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        if ($request->user()->password) {
            $this->validate($request, [
                'current_password' => 'required',
            ]);

            if (! Hash::check($request->current_password, $request->user()->password)) {
                return response()->json([
                    'errors' => [
                        'current_password' => [__('The given password does not match our records.')]
                    ]
                ], 422);
            }
        }



        $request->user()->forceFill([
            'password' => bcrypt($request->password)
        ])->save();
    }
}

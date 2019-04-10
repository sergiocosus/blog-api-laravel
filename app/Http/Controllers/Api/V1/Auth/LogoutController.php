<?php


namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Lcobucci\JWT\Parser;
use Yadahan\AuthenticationLog\AuthenticationLog;

class LogoutController extends Controller {

    public function logout(Request $request) {
        $user  = $request->user();
        $value = $request->bearerToken();
        $id    = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens()->find($id);
        $token->revoke();


        $ip                = $request->ip();
        $userAgent         = $request->userAgent();
        $authenticationLog = $user->authentications()
            ->whereIpAddress($ip)->whereUserAgent($userAgent)
            ->where('login_at', $token->created_at)
            ->first();

        if ( ! $authenticationLog) {
            $authenticationLog = new AuthenticationLog([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }

        $authenticationLog->logout_at = Carbon::now();
        $user->authentications()->save($authenticationLog);
    }
}

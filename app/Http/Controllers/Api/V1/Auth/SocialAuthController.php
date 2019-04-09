<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 9/07/18
 * Time: 10:00 AM
 */

namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GetSocialRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller {
    /*
       |--------------------------------------------------------------------------
       | Login Controller
       |--------------------------------------------------------------------------
       |
       | This controller handles authenticating users for the application and
       | redirecting them to your home screen. The controller uses a trait
       | to conveniently provide its functionality to your applications.
       |
       */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function getSocial(GetSocialRequest $request) {
        $driver      = $request->get('driver');
        $redirectUrl = $request->get('redirect_url');

        logger($driver);
        $redirect_url = tap(Socialite::driver($driver), function ($socialite) use ($redirectUrl) {
            if ($redirectUrl) {
                $socialite->redirectUrl($redirectUrl);
            }
        })->stateless()->redirect()->getTargetUrl();

        return compact('redirect_url');
    }

}

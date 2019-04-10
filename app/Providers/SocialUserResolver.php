<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 14/09/17
 * Time: 12:40 PM
 */

namespace App\Providers;

use Adaojunior\Passport\SocialGrantException;
use Adaojunior\Passport\SocialUserResolverInterface;
use App\Core\UserService;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialUserResolver implements SocialUserResolverInterface {
    /**
     * @var Request
     */
    private $request;
    /**
     * @var UserService
     */
    private $userService;

    /**
     * SocialUserResolver constructor.
     */
    public function __construct(
        Request $request,
        UserService $userService
    ) {
        $this->request     = $request;
        $this->userService = $userService;
    }

    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function resolve($network, $accessToken, $accessTokenSecret = null) {
        $redirect_url = $this->request->get('redirect_url');
        $this->request->merge(['code' => $accessToken]);

        if ($network == 'coinbase') {
            $coinbaseTokenResponse = $this->getCoinbaseTokenResponse($accessToken);
            $social_user           = $this->getCoinbaseUserData($coinbaseTokenResponse['access_token']);
        } else {
            $social_user = $this->getSocialUserData($network, $redirect_url);
        }

        logger($social_user);

        switch ($network) {
            case 'facebook':
            case 'google':
                $user = User::whereEmail($social_user['email'])->first();

                if ( ! $user) {
                    $this->userService->createForSocial([
                        'email'    => $social_user['email'],
                        'name'     => $social_user['first_name'],
                        'last_name' => $social_user['last_name'],
                    ]);

                    $user = User::whereEmail($social_user['email'])->first();
                }

                return $user;
            default:
                throw SocialGrantException::invalidNetwork();
                break;
        }


    }

    private function getSocialUserData($driver, $redirectUrl) {
        $socialite = Socialite::driver($driver)
            ->stateless();


        if ($redirectUrl) {
            $socialite->redirectUrl($redirectUrl);
            logger($redirectUrl);
        }

        if ($driver === 'facebook') {
            $socialite->fields([
                'name',
                'email',
                'gender',
                'verified',
                'link',
                'first_name',
                'last_name',
            ]);
        }
        /** @var User $userSocial */
        $userSocial = $socialite->user();
        logger((array) $userSocial);

        $social_user = [
            'name'  => $userSocial->getName(),
            'email' => $userSocial->getEmail(),
            'token' => $userSocial->token,
        ];

        switch ($driver) {
            case 'facebook':
                $social_user['first_name'] = $userSocial->user['first_name'];
                $social_user['last_name']  = $userSocial->user['last_name'];
                break;
            case 'google':
                logger((array)$userSocial);
                $social_user['first_name'] = $userSocial->user['given_name'];
                $social_user['last_name']  = $userSocial->user['family_name'];
                break;
        }

        return $social_user;
    }

    function split_name($name) {
        $name       = trim($name);
        $last_name  = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));

        return [$first_name, $last_name];
    }
}

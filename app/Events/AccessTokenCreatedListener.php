<?php

namespace App\Events;

use App\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Events\AccessTokenCreated;
use Yadahan\AuthenticationLog\AuthenticationLog;
use Yadahan\AuthenticationLog\Notifications\NewDevice;

class AccessTokenCreatedListener {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var AccessTokenCreated
     */
    private $accessTokenCreated;
    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

        $this->request = $request;
    }

    public function handle(AccessTokenCreated $accessTokenCreated) {
        $this->accessTokenCreated = $accessTokenCreated;
        $user = User::find($accessTokenCreated->userId);
        $ip = $this->request->ip();
        $userAgent = $this->request->userAgent();
        $known = $user->authentications()->whereIpAddress($ip)->whereUserAgent($userAgent)->first();

        $authenticationLog = new AuthenticationLog([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => Carbon::now(),
        ]);

        $user->authentications()->save($authenticationLog);

        if (! $known && config('authentication-log.notify')) {
            $user->notify(new NewDevice($authenticationLog));
        }
    }
}

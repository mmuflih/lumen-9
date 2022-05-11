<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\AuthController;

use App\Context\Handler;
use App\Context\UserController\RegisterUserHandler;
use App\Models\User;
use App\Models\UserEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginSocialHandler implements Handler
{
    private $socialUser;

    public function __construct(Request $request)
    {
        $this->socialUser = Socialite::driver($request->get('social_type'))
            ->userFromToken($request->get("social_token"));
    }

    public function handle()
    {
        $email = $this->socialUser->email;
        if (is_null($email) || $email == "") {
            throw new \Exception("Invalid social token", 422);
        }
        $emailCheckHandler = new CheckEmailHandler(new Request());
        $registred = $emailCheckHandler->checkEmail($email);

        if ($registred['is_registered']) {
            $userEmail = UserEmail::where('email', $email)
                ->first();
            $user = User::find($userEmail->user_id);
            $user->email = $email;
            $registred['user'] = $user;
        } else {
            $socialUser = $this->socialUser->user;
            $handler = new RegisterUserHandler(
                $socialUser['name'],
                $socialUser['email'],
                '',
                '',
                '',
                '',
                $socialUser['picture'],
                'google',
                '',
                false,
                true,
                true,
            );
            $user = $handler->handle();
            $usrData['email'] = $email;
            $registred['user'] = $usrData;
        }
        $registred['access_token'] = Auth::login($user);
        return $registred;
    }
}

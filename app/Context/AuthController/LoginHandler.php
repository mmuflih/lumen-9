<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\AuthController;

use App\Context\ContextException;
use App\Context\Handler;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Illuminate\Http\Request;

class LoginHandler implements Handler
{
    private $email;
    private $password;

    public function __construct(Request $request)
    {
        $this->email = $request->email;
        $this->password = $request->password;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function handle()
    {
        $email = $this->validateEmail($this->email);
        if (is_null($email)) {
            throw new \Exception("Email and password not match", 422);
        }
        $password = $this->validatePassword($email, $this->password);
        if (is_null($password)) {
            throw new \Exception("Email and password not match", 422);
        }

        $user = User::find($password->user_id);
        if (is_null($user)) {
            throw new \Exception("Email and password not match", 422);
        }
        $token = auth()->login($user);

        if (!$token) {
            throw new \Exception("Email and password not match", 422);
        }

        return TokenResponseHelper::generateResponse($token);
    }

    /**
     * @param $email
     * @return UserEmail
     */
    private function validateEmail($email)
    {
        return UserEmail::where('email', $email)
            ->where('active', true)
            ->first();
    }

    /**
     * @param UserEmail $email
     * @param $password
     * @return UserPassword
     * @throws \Exception
     */
    private function validatePassword(UserEmail $email, $password)
    {
        $userPassword = UserPassword::where('user_id', $email->user_id)
            ->where('active', true)
            ->first();

        if (!app('hash')->check($password, $userPassword->password)) {
            throw new \Exception("Email and password not match", 422);
        }
        return $userPassword;
    }
}

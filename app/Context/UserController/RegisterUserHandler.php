<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Context\OtpController\CreateOtp;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterUserHandler implements Handler
{
    private $email;
    private $name;
    private $password;
    private $domain;
    private $phone;

    public function __construct($email, $name, $password, $phone, $domain)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->domain = $domain;
        $this->phone = $phone;
    }

    public static function fromRequest(Request $request)
    {
        return new static(
            $request->get('email'),
            $request->get('name'),
            $request->get('password'),
            $request->get('phone'),
            $request->get('domain')
        );
    }

    public function handle()
    {
        $user = new User();
        $user->name = $this->name;
        $user->phone = $this->phone;
        DB::transaction(function () use ($user) {
            $user->save();
            $this->setPassword($user, $this->password);
            $userEmail = $this->setEmail($user, $this->email, $this->domain);
            CreateOtp::SendOTP($user, $userEmail);
        });
        return $user;
    }

    private function setPassword(User $user, $password)
    {
        $userPass = new UserPassword();
        $userPass->user_id = $user->id;
        $userPass->setPassword($password);
        $userPass->active = false;
        $userPass->save();
    }

    private function setEmail(User $user, $email, $domain = null)
    {
        $userEmail = new UserEmail();
        $userEmail->user_id = $user->id;
        $userEmail->email = $email;
        $userEmail->domain = $domain;
        $userEmail->raw_input = null;
        $userEmail->primary = true;
        $userEmail->active = false;
        $userEmail->save();
        return $userEmail;
    }
}

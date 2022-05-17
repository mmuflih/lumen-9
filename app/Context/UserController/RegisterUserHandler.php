<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Jobs\SendEmailJob;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterUserHandler implements Handler
{
    private $email;
    private $name;
    private $password;
    private $domain;

    public function __construct($email, $name, $password, $domain)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->domain = $domain;
    }

    public static function fromRequest(Request $request)
    {
        return new static(
            $request->get('email'),
            $request->get('name'),
            $request->get('password'),
            $request->get('domain')
        );
    }

    public function handle()
    {
        $user = new User();
        $user->name = $this->name;
        DB::transaction(function () use ($user) {
            $user->save();
            $this->setPassword($user, $this->password);
            $userEmail = $this->setEmail($user, $this->email, $this->domain);
            $this->sendOTP($user, $userEmail);
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

    private function sendOTP($user, $userEmail)
    {
        if (is_null($userEmail)) {
            return;
        }
        $otpCode = $this->generateOTP($user->id);
        $mailJob = new SendEmailJob(
            "mail.register.otp",
            "Hi " . $user->name . " - OTP",
            $userEmail->email,
            $user->name,
            [
                'name' => $user->name,
                'title' => 'One Time Password',
                'code' => $otpCode,
            ]
        );
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($mailJob);
    }

    private function generateOTP($userId)
    {
        $code = rand(100000, 999999);
        $otp = new Otp();
        $otp->fill([
            'user_id' => $userId,
            'code' => $code,
            'expired_at' => Carbon::now(env('APP_TIMEZONE'))->addMinutes(5)
        ]);
        $otp->save();
        return $code;
    }
}

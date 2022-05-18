<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use App\Jobs\SendEmailJob;
use App\Models\Otp;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyOTPHandler implements Handler
{
    private $valid;

    public function __construct(Request $request)
    {
        $this->valid = $request->all();
    }

    public function handle()
    {
        $now = Carbon::now(env("APP_TIMEZONE"));
        $otp = Otp::where([
            'to' => $this->valid['email'],
            'code' => $this->valid['code']
        ])
            ->first();
        if (is_null($otp)) {
            throw new \Exception("OTP Data not Found", 422);
        }
        if ($otp->expired_at <= $now) {
            throw new \Exception("OTP is expired", 422);
        }
        if (!is_null($otp->verified_at)) {
            throw new \Exception("OTP already verified before", 422);
        }

        DB::beginTransaction();
        $otp->verified_at = $now;
        $otp->save();

        $userEmail = UserEmail::where('user_id', $otp->user_id)->first();
        if (is_null($userEmail)) {
            throw new \Exception("Error, user verification", 422);
        }

        $userPassword = UserPassword::where('user_id', $otp->user_id)->first();
        if (is_null($userEmail)) {
            throw new \Exception("Error, update user data", 422);
        }

        $userEmail->active = true;
        $userEmail->save();

        $userPassword->active = true;
        $userPassword->save();

        DB::commit();
        $this->sendEmailNotif($userEmail);
        return "Success";
    }

    private function sendEmailNotif($userEmail)
    {
        if (is_null($userEmail)) {
            return;
        }
        $user = $userEmail->user;
        $mailJob = new SendEmailJob(
            "mail.register.success",
            "Hi " . $user->name . " - Welcome",
            $userEmail->email,
            $user->name,
            [
                'name' => $user->name,
                'title' => 'Selamat, Registrasi Berhasil',
            ]
        );
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($mailJob);
    }
}

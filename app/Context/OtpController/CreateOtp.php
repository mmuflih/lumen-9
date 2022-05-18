<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Jobs\SendEmailJob;
use App\Models\Otp;
use Carbon\Carbon;

class CreateOtp
{
    public static function SendOTP($user, $userEmail)
    {
        if (is_null($userEmail)) {
            return;
        }
        $otpCode = self::generateOTP($user->id);
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

    public static function GenerateOTP($userId)
    {
        $now = Carbon::now(env('APP_TIMEZONE'));

        $otp = Otp::where('user_id', $userId)
            ->where('expired_at', '>', $now)
            ->whereNull('verified_at')
            ->first();
        if (is_null($otp)) {
            $code = rand(100000, 999999);
            $otp = new Otp();
            $otp->fill([
                'user_id' => $userId,
                'code' => $code,
                'expired_at' => $now->copy()->addMinutes(5),
                'attempts' => 1
            ]);
            $otp->save();
            return $otp->code;
        }
        $otp->attempts += 1;
        $otp->save();
        return $otp->code;
    }
}

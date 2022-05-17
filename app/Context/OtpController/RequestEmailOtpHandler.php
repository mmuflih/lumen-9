<?php

/**
 * Created by Muhammad Muflih Kholidin
 * at 2021-01-18 13:55:31
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use App\Jobs\SendEmailJob;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestEmailOtpHandler implements Handler
{
    private $valid;

    public function __construct(Request $request)
    {
        $this->valid = $request->all();
    }

    public function handle()
    {
        $email = $this->valid['email'];
        $ue = UserEmail::where('email', $email)
            ->first();
        if (is_null($ue)) {
            throw new \Exception("Email belum terdaftar", 422);
        }

        $user = User::find($ue->user_id);

        $this->sendOTP($user, $ue);

        return [
            'Silahkan check email kamu',
        ];
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

    public function generateOTP($userId)
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

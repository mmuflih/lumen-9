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

        CreateOtp::SendOTP($user, $ue);

        return [
            'Silahkan check email kamu',
        ];
    }
}

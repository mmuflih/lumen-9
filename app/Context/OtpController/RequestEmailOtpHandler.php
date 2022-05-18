<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use App\Models\User;
use App\Models\UserEmail;
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

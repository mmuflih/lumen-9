<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use Illuminate\Http\Request;

class PhoneOtpReq implements Handler
{
    private $valid;

    public function __construct(Request $request)
    {
        $this->valid = $request->all();
    }

    public function handle()
    {
        $user = auth()->user();
        CreateOtp::SendPhoneOTP($user, $this->valid['phone']);
        return "Check Whatsapp message";
    }
}

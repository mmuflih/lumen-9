<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Http\Controllers;

use App\Context\EmailVerificationController\RequestEmailVerification;
use App\Context\EmailVerificationController\VerifyEmail;
use Illuminate\Http\Request;

class EmailVerificationController extends ApiController
{
    public function request(Request $request)
    {
        $validator = [
            'email' => 'required',
        ];
        return $this->responseHandler(new RequestEmailVerification($request), $request, $validator);
    }

    public function verify(Request $request)
    {
        $validator = [
            'token' => 'required|exists:email_verifications,token',
        ];
        return $this->responseHandler(new VerifyEmail($request), $request, $validator);
    }
}

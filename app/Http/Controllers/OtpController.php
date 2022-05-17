<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Http\Controllers;

use App\Context\OtpController\RequestEmailOtpHandler;
use App\Context\OtpController\VerifyOTPHandler;
use Illuminate\Http\Request;

class OtpController extends ApiController
{
    public function request(Request $request)
    {
        $rules = [
            'email' => 'required',
        ];
        return $this->responseHandler(new RequestEmailOtpHandler($request), $request, $rules);
    }

    public function verify(Request $request)
    {
        $rules = [
            'code' => 'required'
        ];
        return $this->responseHandler(new VerifyOTPHandler($request), $request, $rules);
    }
}

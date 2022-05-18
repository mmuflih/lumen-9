<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use App\Jobs\SendWaJob;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyPhoneHandler implements Handler
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
            'to' => $this->valid['phone'],
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

        /** @var /App/Models/User */
        $user = auth()->user();
        if (is_null($user)) {
            throw new \Exception("Error, user verification", 422);
        }

        $user->phone = $this->valid['phone'];
        $user->save();

        DB::commit();
        $this->sendNotif($this->valid['phone']);
        return "Success";
    }

    private function sendNotif($phone)
    {
        $message = "Phone number was updated";
        $mailJob = new SendWaJob($phone, $message);
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($mailJob);
    }
}

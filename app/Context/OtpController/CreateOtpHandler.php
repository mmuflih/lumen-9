<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\OtpController;

use App\Context\Handler;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Illuminate\Http\Request;

class CreateOtpHandler implements Handler
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

        $up = UserPassword::where('user_id', $ue->user_id)
            ->where('password', '')
            ->first();
        if (is_null($up)) {
            $up = new UserPassword();
            $up->user_id = $ue->user_id;
        }
        $up->active = false;
        $up->password = "";
        $up->reset_token = rand(1000, 9999);
        $up->save();

        $u = User::find($up->user_id);

        $mailJob = new SendEmailJob(
            "mail.password.reset",
            "Hi " . $u->name . " - One Time Password",
            $email,
            $u->name,
            [
                'reset_token' => $up->reset_token, 'title' => 'One Time Password',
                'name' => $u->name
            ]
        );
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($mailJob);
        return [
            'Silahkan check email kamu',
        ];
    }
}

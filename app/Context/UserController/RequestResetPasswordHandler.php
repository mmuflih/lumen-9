<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Nonstandard\UuidV6;

class RequestResetPasswordHandler implements Handler
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
        $now = Carbon::now(env('APP_TIMEZONE'))->addMinutes(10);
        $up->active = false;
        $up->password = "";
        $up->reset_token = UuidV6::uuid6()->toString();
        $up->token_expired_at = $now->copy()->unix();
        $up->save();

        $u = User::find($up->user_id);

        $mailJob = new SendEmailJob(
            "mail.password.reset",
            "Hi " . $u->name . " - Reset Password",
            $email,
            $u->name,
            [
                'reset_token' => $up->reset_token,
                'title' => 'Reset Password',
                'name' => $u->name,
                'reset_page' => $this->valid['reset_page'],
                'expired_at' => $now->format('Y-m-d H:i:s')
            ]
        );
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($mailJob);
        return [
            'Silahkan check email kamu',
        ];
    }
}

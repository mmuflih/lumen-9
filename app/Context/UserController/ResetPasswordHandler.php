<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Models\UserPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResetPasswordHandler implements Handler
{
    private $valid;
    public function __construct(Request $request)
    {
        $this->valid = $request->all();
    }

    public function handle()
    {
        $now = Carbon::now(env('APP_TIMEZONE'))->unix();
        $up = UserPassword::where('reset_token', $this->valid['reset_token'])
            ->where('password', '')
            ->first();
        if (is_null($up)) {
            throw new \Exception("Invalid reset token", 422);
        }

        if ($up->token_expired_at <= $now) {
            throw new \Exception("Token expired", 422);
        }

        if ($up->active) {
            throw new \Exception("Password already updated", 422);
        }

        $up->setPassword($this->valid['password']);
        $up->active = true;
        $up->save();

        /** disable oldes password */
        UserPassword::where('user_id', $up->user_id)
            ->where('id', '<>', $up->id)
            ->update(['active' => false]);

        return ['Password telah berhasil direset'];
    }
}

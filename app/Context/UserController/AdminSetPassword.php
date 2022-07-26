<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Models\User;
use App\Models\UserPassword;
use Illuminate\Http\Request;

class AdminSetPassword implements Handler
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $user = User::find($this->request->user_id);
        $newPassword = $this->request->get('password');

        $password = $user->password;

        /** check old password */
        if (!app('hash')->check($this->request->get('old_password'), $password->password)) {
            throw new \Exception("Password lama tidak cocok", 422);
        }

        /** update old password data */
        UserPassword::where('user_id', $user->id)
            ->update(['active' => false]);

        $newPasswordData = $password->replicate();
        $newPasswordData->setPassword($newPassword);
        $newPasswordData->save();

        unset($user->password);
        return $user;
    }
}

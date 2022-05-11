<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\Handler;
use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterUserHandler implements Handler
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $user = new User();
        $user->fill($this->request->all());
        DB::transaction(function () use ($user) {
            $user->save();
            $this->setPassword($user, $this->request);
            $this->setEmail($user, $this->request);
        });
        return $user;
    }

    public function setPassword(User $user, Request $request)
    {
        $userPass = new UserPassword();
        $userPass->user_id = $user->id;
        $userPass->setPassword($request->get('password'));
        $userPass->active = true;
        $userPass->save();
    }

    public function setEmail(User $user, Request $request)
    {
        $userEmail = new UserEmail();
        $userEmail->user_id = $user->id;
        $userEmail->email = $request->get('email');
        $userEmail->domain = $request->get('domain');
        $userEmail->raw_input = $request->get('raw_input');
        $userEmail->primary = true;
        $userEmail->active = true;
        $userEmail->save();
    }
}

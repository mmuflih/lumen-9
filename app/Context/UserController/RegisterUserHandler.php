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
    private $email;
    private $name;
    private $password;
    private $domain;

    public function __construct($email, $name, $password, $domain)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->domain = $domain;
    }

    public static function fromRequest(Request $request)
    {
        return new static(
            $request->get('email'),
            $request->get('name'),
            $request->get('password'),
            $request->get('domain')
        );
    }

    public function handle()
    {
        $user = new User();
        $user->name = $this->name;
        DB::transaction(function () use ($user) {
            $user->save();
            $this->setPassword($user, $this->password);
            $this->setEmail($user, $this->email, $this->domain);
        });
        return $user;
    }

    private function setPassword(User $user, $password)
    {
        $userPass = new UserPassword();
        $userPass->user_id = $user->id;
        $userPass->setPassword($password);
        $userPass->active = true;
        $userPass->save();
    }

    private function setEmail(User $user, $email, $domain = null)
    {
        $userEmail = new UserEmail();
        $userEmail->user_id = $user->id;
        $userEmail->email = $email;
        $userEmail->domain = $domain;
        $userEmail->raw_input = null;
        $userEmail->primary = true;
        $userEmail->active = true;
        $userEmail->save();
    }
}

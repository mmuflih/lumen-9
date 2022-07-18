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

class AddByAdmin implements Handler
{
    private $registUser;

    public function __construct(Request $request)
    {
        $this->registUser = new RegisterUserHandler(
            $request->get('email'),
            $request->get('name'),
            $request->get('password'),
            $request->get('phone'),
            $request->get('domain')
        );
    }

    public function handle()
    {
        return $this->registUser->handle();
    }
}

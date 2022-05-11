<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\AuthController;

use App\Context\Handler;

class LogoutHandler implements Handler
{
    /**
     * @return string
     */
    public function handle()
    {
        auth()->logout();
        return "Successfully logged out";
    }
}

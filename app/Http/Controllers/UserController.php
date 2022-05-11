<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Http\Controllers;

use App\Context\UserController\RegisterUserHandler;
use App\Context\UserController\SetPasswordHandler;
use Illuminate\Http\Request;
use Validator;

class UserController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|unique:user_emails',
                'password' => 'required|confirmed',
                'name' => 'required',
            ]);
            $handler = new RegisterUserHandler($request);
            $data = $handler->handle();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    public function setPassword(Request $request)
    {
        try {
            $this->validate($request, [
                'old_password' => 'required',
                'password' => 'sometimes|required|confirmed',
            ]);
            $handler = new SetPasswordHandler($request);
            $data = $handler->handle();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }
}

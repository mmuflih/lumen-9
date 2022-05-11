<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Http\Controllers;

use App\Context\AuthController\LoginHandler;
use App\Context\AuthController\GetMeReader;
use App\Context\AuthController\LoginSocialHandler;
use App\Context\AuthController\LogoutHandler;
use App\Context\AuthController\RefreshReader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'social']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required'
            ]);
            $handler = new LoginHandler($request);
            $data = $handler->handle();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    public function social(Request $request)
    {
        $rules = [
            'social_token' => 'required',
            'social_type' => [
                'required',
                Rule::in(['google', 'facebook']),
            ]
        ];

        $this->validate($request, $rules);
        return $this->responseHandler(
            new LoginSocialHandler($request),
            $request,
            $rules
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $reader = new GetMeReader();
            $data = $reader->read();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $handler = new LogoutHandler();
            $data = $handler->handle();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $reader = new RefreshReader();
            $data = $reader->read();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }
}

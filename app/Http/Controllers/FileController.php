<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 * at: 16/08/20 21.12
 **/

namespace App\Http\Controllers;

use App\Context\FileController\UploadHandler;
use Illuminate\Http\Request;

class FileController extends ApiController
{
    public function upload(Request $request)
    {
        try {
            $this->validate($request, [
                'file' => 'required',
                'slug' => 'sometimes|required|max:100'
            ]);
            $handler = new UploadHandler($request);
            $data = $handler->handle();
            return $this->responseData($data);
        } catch (\Exception $e) {
            return $this->responseException($e);
        }
    }
}

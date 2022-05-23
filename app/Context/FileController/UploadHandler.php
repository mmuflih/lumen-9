<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\FileController;

use App\Context\Handler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadHandler implements Handler
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $extension = $this->request->file->extension();

        $file = $this->request->file('file');
        $slug = $this->request->get('slug');
        $domain = $this->request->get('domain', 'fc');
        $folder = env('DO_SPACES_FOLDER');
        $fileName = $this->generateFileName($slug, $extension);

        $uri = Storage::disk('do_space')->putFileAs($folder, $file, "$domain-$fileName");

        return [
            'uri' => $uri,
            'url' => Storage::disk('do_space')->url($uri),
        ];
    }

    private function generateFileName($slug, $extension)
    {
        return $slug . '-' . str_replace('.', '', Carbon::now()->timestamp / 10000) . '.' . $extension;
    }
}

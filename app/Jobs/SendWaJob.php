<?php

/**
 * Generated by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWaJob extends Job implements ShouldQueue
{
    private $phone;
    private $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle()
    {
        $host = env("WA_HOST");
        $code = env("WA_API_CODE");
        $senderNumber = env("WA_SENDER");

        $url = "$host/wa-api/$code/$senderNumber/$this->phone";

        $client = new Client();

        $client->request('POST', $url, [
            'json' => [
                'text' => $this->message
            ]
        ]);
    }
}

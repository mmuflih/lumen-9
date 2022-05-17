<?php

/**
 * Generated by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob extends Job implements ShouldQueue
{
    private $template;
    private $subject;
    private $mailTo;
    private $mailToName;
    private $data;

    public function __construct(
        $template,
        $subject,
        $mailTo,
        $mailToName,
        $data = []
    ) {
        $this->template = $template;
        $this->subject = $subject;
        $this->mailTo = $mailTo;
        $this->mailToName = $mailToName;
        $this->data = $data;
        // $url = "http://api.db2.web.id/api/telegram/message?channel=digiotp&message="
        //     . json_encode([$data, $mailTo, $subject, $mailToName]);
        // Http::get($url);
    }

    public function handle()
    {
        Mail::send($this->template, $this->data, function ($message) {
            $message->to($this->mailTo, $this->mailToName)
                ->subject($this->subject);
            // $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });
        Log::info("rampung bos 333");
    }
}

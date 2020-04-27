<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnyMail extends Mailable
{
    // use Queueable, SerializesModels;
    protected $title;
    protected $body;
    protected $url;

    public function __construct($title, $body, $url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function build()
    {

        return $this
            ->subject($this->title)
            ->view('email')
        ->with([
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
        ]);
    }
}

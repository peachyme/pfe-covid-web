<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateReunionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $info = [];
    public $info_nv = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($info, $info_nv)
    {
        $this->info = $info;
        $this->info_nv = $info_nv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.updateReunionMail');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Replenishment extends Mailable
{
    use Queueable, SerializesModels;
    protected $rep;
    protected $presentationFormatted;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rep, $presentationFormatted)
    {
        $this->rep = $rep;
        $this->presentationFormatted = $presentationFormatted;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reposicion de Inventario | '.env('APP_NAME'))
            ->markdown('emails.replenishment')
            ->with(['rep' => $this->rep, 'presentationFormatted' => $this->presentationFormatted]);
    }
}

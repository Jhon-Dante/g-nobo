<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $products;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Alerta por Umbral de Existencia | '.env('APP_NAME'))
        ->with(['products' => $this->products])
        ->view('emails.stock-alert');
    }
}

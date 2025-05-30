<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PedidoRealizado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    /**
     * Create a new message instance.
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmação do seu pedido #' . $this->pedido->id)
                    ->view('emails.pedido_confirmado')
                    ->with([
                        'pedido' => $this->pedido,
                    ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnulaciónFactura extends Mailable
{
    use Queueable, SerializesModels;

    public $archivoPdfPath;
    public $motivoAnulacion;
    public $numFactura;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($archivoPdfPath, $motivoAnulacion, $numFactura)
    {
        $this->archivoPdfPath = $archivoPdfPath;
        $this->motivoAnulacion = $motivoAnulacion;
        $this->numFactura = $numFactura;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.factura-anulada')
                    ->with([
                        'numFactura' => $this->numFactura
                    ])
                    ->attach($this->archivoPdfPath, [
                        'as' => 'factura.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
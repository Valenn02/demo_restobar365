<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FacturaElectrónica extends Mailable
{
    use Queueable, SerializesModels;

    public $archivoXmlPath;
    public $archivoPdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($archivoXmlPath, $archivoPdfPath)
    {
        $this->archivoXmlPath = $archivoXmlPath;
        $this->archivoPdfPath = $archivoPdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.factura-validada')
                    ->attach($this->archivoXmlPath, [
                        'as' => 'factura.xml',
                        'mime' => 'application/xml',
                    ])
                    ->attach($this->archivoPdfPath, [
                        'as' => 'factura.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
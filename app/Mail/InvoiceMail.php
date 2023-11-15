<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('mail.invoice-mail')->subject('Factura Electronica #' . $this->msg["key"] . ' de ' . Auth::user()->currentTeam->name);
        if (file_exists($this->msg["xml"])) {
            $mail->attach($this->msg["xml"]);
        }
        if (file_exists($this->msg["xmlR"])) {
            $mail->attach($this->msg["xmlR"]);
        }
        if (file_exists($this->msg["pdf"])) {
            $mail->attachData(file_get_contents($this->msg["pdf"]),$this->msg["key"].".pdf");
            file_get_contents($this->msg["pdf"]);
        }
        return $mail;
    }
}

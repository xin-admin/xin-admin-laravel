<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public int $expireMinutes;

    /**
     * Create a new message instance.
     *
     * @param string $code
     * @param int $expireMinutes
     */
    public function __construct(string $code, int $expireMinutes = 30)
    {
        $this->code = $code;
        $this->expireMinutes = $expireMinutes;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification_code',
            with: [
                'code' => $this->code,
                'expireMinutes' => $this->expireMinutes,
            ],
        );
    }
}

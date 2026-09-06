<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $data = [];
    public $tries = 3;

    public function backoff()
    {
        return [60, 300];
    }

    public function failed(\Throwable $exception)
    {
        // Ở đây để xử lý trường hợp mail resend 3 lần không được sẽ chuyển data từ bảng jobs qua fail_jobs
        // Và ở đây sẽ xử lý để gửi thông báo cho dev hoặc admin
    }
    //2. Get data from controller
    public function __construct($data)
    {
        //
        $this->data = $data->load(['items', 'user']);
    }

    // public function build()
    // {
    //     return $this->from('thanminhhy@gmail.com', 'test')
    //         ->subject($this->data['subject'])
    //         ->view('frontend.emails.index')->with('data', $this->data);
    // }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // from: new Address('thanminhhy@gmail.com', 'noreply@s-web.com'),
            subject: $this->data['subject'] ?? 'Mail Notify',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'frontend.emails.index',
            with: [
                'order' => $this->data,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

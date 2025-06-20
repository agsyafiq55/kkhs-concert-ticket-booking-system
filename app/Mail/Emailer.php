<?php

namespace App\Mail;

use App\Models\TicketPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class Emailer extends Mailable
{
    use Queueable, SerializesModels;

    public $ticketPurchases;
    public $isMultiple;
    public $qrCodeImages;

    /**
     * Create a new message instance.
     */
    public function __construct($ticketPurchases, $qrCodeImages = [])
    {
        // Handle both single purchase and multiple purchases
        if ($ticketPurchases instanceof TicketPurchase) {
            $this->ticketPurchases = collect([$ticketPurchases]);
            $this->isMultiple = false;
        } elseif ($ticketPurchases instanceof Collection) {
            $this->ticketPurchases = $ticketPurchases;
            $this->isMultiple = $ticketPurchases->count() > 1;
        } else {
            $this->ticketPurchases = collect($ticketPurchases);
            $this->isMultiple = count($ticketPurchases) > 1;
        }
        
        $this->qrCodeImages = $qrCodeImages;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $firstPurchase = $this->ticketPurchases->first();
        
        // Handle multiple concerts in the email subject
        $concerts = $this->ticketPurchases->map(function($purchase) {
            return $purchase->ticket->concert->title;
        })->unique();
        
        if ($concerts->count() > 1) {
            $subject = $this->isMultiple 
                ? 'Your Concert Tickets - Multiple Events'
                : 'Your Concert Ticket - ' . $firstPurchase->ticket->concert->title;
        } else {
            $subject = $this->isMultiple 
                ? 'Your Concert Tickets - ' . $firstPurchase->ticket->concert->title
                : 'Your Concert Ticket - ' . $firstPurchase->ticket->concert->title;
        }
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.emailer',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

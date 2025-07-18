<?php

namespace App\Mail;

use App\Models\TicketPurchase;
use Illuminate\Bus\Queueable;
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

    public $ticketUrls;

    /**
     * Create a new message instance.
     */
    public function __construct($ticketPurchases)
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

        $this->ticketUrls = [];

        // Generate secure ticket URLs for each purchase
        foreach ($this->ticketPurchases as $purchase) {
            $this->ticketUrls[$purchase->id] = $this->generateSecureTicketUrl($purchase);
        }
    }

    /**
     * Generate secure ticket URL for a purchase
     */
    protected function generateSecureTicketUrl($purchase)
    {
        // Generate secure token
        $token = hash('sha256', $purchase->id.$purchase->qr_code.config('app.key'));

        // Generate URL
        return route('ticket.printable', [
            'id' => $purchase->id,
            'token' => $token,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $firstPurchase = $this->ticketPurchases->first();

        // Default subject in case of any issues
        $defaultSubject = $this->isMultiple ? 'Your Concert Tickets' : 'Your Concert Ticket';

        // Check if we have ticket purchases and valid relationships
        if (!$firstPurchase || !$firstPurchase->ticket || !$firstPurchase->ticket->concert) {
            return new Envelope(
                subject: $defaultSubject,
            );
        }

        // Handle multiple concerts in the email subject
        $concerts = $this->ticketPurchases->map(function ($purchase) {
            return $purchase->ticket && $purchase->ticket->concert ? $purchase->ticket->concert->title : 'Unknown Concert';
        })->unique();

        if ($concerts->count() > 1) {
            $subject = $this->isMultiple
                ? 'Your Concert Tickets - Multiple Events'
                : 'Your Concert Ticket - '.$firstPurchase->ticket->concert->title;
        } else {
            $subject = $this->isMultiple
                ? 'Your Concert Tickets - '.$firstPurchase->ticket->concert->title
                : 'Your Concert Ticket - '.$firstPurchase->ticket->concert->title;
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

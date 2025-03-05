<?php

namespace App\Notifications;

use App\Models\Annonces;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //dd($this->reservation->annouce_id);
        $annouce = Annonces::findOrfail($this->reservation->annonce_id);
        $user = User::findorfail($annouce->user_id);

        return (new MailMessage)
            ->subject('Your Reservation Confirmation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for your reservation.')
            ->line('Your reservation details are as follows:')
            ->line('Reservation ID: ' . $this->reservation->id)
            ->line('Start Date: ' . $this->reservation->start)
            ->line('End Date: ' . $this->reservation->end)
            ->line('Amount: $' . $this->reservation->amount)
            ->line('The Owner Details are as follows: ')
            ->line('name: ' . $user->name)
            ->line('email' . $user->email)
            ->action('View Reservation', url('/reservations/' . $this->reservation->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCodeAuthFactor extends Notification
{
  use Queueable;

  private $code;
  private $user;

  /**
   * Create a new notification instance.
   */
  public function __construct(string $user, string $code)
  {
    $this->code = $code;
    $this->user = $user;
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
    return (new MailMessage)
      ->subject('2FA Code verification')
      ->greeting('Hello ' . $this->user . '!')
      ->line('Your code is:')
      ->line($this->code)
      ->line('Enter the code in the application to verify your account.')
      ->line('If you did not request a code, you can ignore this email.')
      ->line('Link to verify the code:')
      ->action('Verify code', route('auth-factor.verify-code'))
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

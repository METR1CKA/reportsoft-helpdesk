<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendCredsNotification extends Notification
{
  use Queueable;

  private $userCreated;
  private $userRequest;
  private $passwd;

  /**
   * Create a new notification instance.
   */
  public function __construct(User $userCreated, User $userRequest, string $passwd)
  {
    $this->userCreated = $userCreated;
    $this->userRequest = $userRequest;
    $this->passwd = $passwd;
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
      ->subject('Your account has been created')
      ->greeting('Hello ' . $this->userCreated->username . '!')
      ->line('Welcome to ' . env('APP_NAME', 'REPORTSOFT'))
      ->line('The user ' . $this->userRequest->username . ' create your account')
      ->line('if you want to login, we have your credentials:')
      ->line('- Your email is: ' . $this->userCreated->email)
      ->line('- Your deafult password is: ' . $this->passwd)
      ->line('Enter your credentials in the application to verify your account.')
      ->line('Before, you can change your password in the application')
      ->line('If you did not request a code, you can ignore this email.')
      ->line('Link to log-in to the application:')
      ->action('Login', route('login'))
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

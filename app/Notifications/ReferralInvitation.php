<?php

namespace App\Notifications;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReferralInvitation extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Referral Model
     *
     * @var \App\Models\Referral
     */
    public $referral;

    /**
     * Constructor method
     *
     * @param \App\Models\Referral $referral
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via ($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $referrer =  $this->referral->referrer;

        $url = url('/register/?refer=' . optional($referrer)->referral_code);
        $subject = str_replace("%user_name%", optional($referrer)->name, trans('emails.referrals.subject'));
        $line1 = str_replace('%user_name%', optional($referrer)->name, trans('emails.referrals.line1'));

        return (new MailMessage)->subject($subject)
            ->greeting('Hi there,')
            ->line($line1)
            ->line(trans('emails.referrals.link_line1'))
            ->action("Accept invite", $url)
            ->line(trans('emails.referrals.line2'))
            ->line(trans('emails.referrals.line3'))
            ->line(trans('emails.referrals.line4'))
            // ->line(trans('emails.referrals.link_line2'))
            // ->line($url)
            ->line('Sincerely, ')
            ->salutation('The ContactOut team');
    }
}

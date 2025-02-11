<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

/**
 * This is the invite user notification class.
 *
 * @author James Brooks <james@alt-three.com>
 */
class InviteUserNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return string[]
     */
    public function via($notifiable)
    {
        $ret = [];

        if (\Config::get('setting.enable_mail')) {
            array_push($ret, 'mail');
        }
        if (\Config::get('setting.enable_twilio')) {
            array_push($ret, 'twilio');
        }

        return $ret;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject(trans('notifications.user.invite.mail.subject'))
                    ->greeting(trans('notifications.user.invite.mail.title', ['app_name' => Config::get('setting.app_name')]))
                    ->action(trans('notifications.user.invite.mail.action'), cachet_route('signup.invite', [$notifiable->code]))
                    ->line(trans('notifications.user.invite.mail.content', ['app_name' => Config::get('setting.app_name')]));
    }

    /**
     * Get the Twilio / SMS representation of the notification.
     * 
     * @param mixed $notifiable
     * 
     * @return \Illuminate\Notifications\Messages\TwilioMessage
     */
    public function toTwilio($notifiable)
    {
        $content = trans('notifications.user.invite.sms.content', [
            'app_name' => Config::get('setting.app_name')
        ]);

        return (new TwilioMessage())->content($content);
    }
}

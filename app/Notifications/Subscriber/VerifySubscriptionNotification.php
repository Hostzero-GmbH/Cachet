<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Notifications\Subscriber;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\TwilioMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * This is the verify subscription notification class.
 *
 * @author James Brooks <james@alt-three.com>
 */
class VerifySubscriptionNotification extends Notification
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
        $route = URL::signedRoute(cachet_route_generator('subscribe.verify'), ['code' => $notifiable->verify_code]);

        return (new MailMessage())
                    ->subject(trans('notifications.subscriber.verify.mail.subject'))
                    ->greeting(trans('notifications.subscriber.verify.mail.title', ['app_name' => Config::get('setting.app_name')]))
                    ->action(trans('notifications.subscriber.verify.mail.action'), $route)
                    ->line(trans('notifications.subscriber.verify.mail.content', ['app_name' => Config::get('setting.app_name')]));
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

        $route = URL::signedRoute(cachet_route_generator('subscribe.verify'), ['code' => $notifiable->verify_code]);

        $content = trans('notifications.subscriber.verify.sms.content', [
            'app_name' => Config::get('setting.app_name'),
            'link'     => $route,
        ]);

        return (new TwilioMessage())->content($content);
    }
}

<?php

namespace Astrotomic\Notifynder;

use Illuminate\Support\ServiceProvider;
use Astrotomic\Notifynder\Senders\RedisSender;

class NotifynderSenderRedisServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        app('notifynder')->extend('sendRedis', function (array $notifications) {
            return new RedisSender($notifications);
        });
    }
}

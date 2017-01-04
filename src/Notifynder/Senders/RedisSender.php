<?php

namespace Astrotomic\Notifynder\Senders;

use Illuminate\Support\Facades\Redis;
use Fenos\Notifynder\Builder\Notification;
use Fenos\Notifynder\Contracts\SenderContract;
use Fenos\Notifynder\Models\NotificationCategory;
use Fenos\Notifynder\Contracts\SenderManagerContract;

class RedisSender implements SenderContract
{
    /**
     * @var array
     */
    protected $notifications;

    /**
     * @var array
     */
    protected $categoryNames = [];

    /**
     * RedisSender constructor.
     *
     * @param array $notifications
     */
    public function __construct(array $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Send all notifications.
     *
     * @param SenderManagerContract $sender
     * @return bool
     */
    public function send(SenderManagerContract $sender)
    {
        $store = config('notifynder.senders.redis.store', false);
        foreach ($this->notifications as $notification) {
            $channel = $this->parseChannel($notification);
            Redis::publish($channel, $notification->toJson());
        }

        if ($store) {
            return $sender->send($this->notifications);
        }

        return true;
    }

    /**
     * Get the channel name for this notification.
     *
     * @param Notification $notification
     * @return string
     */
    protected function parseChannel(Notification $notification)
    {
        $channel = config('notifynder.senders.redis.channel');
        $type = $notification->attribute('to_type', notifynder_config()->getNotifiedModel());
        $id = $notification->attribute('to_id');
        $category = $this->getCategoryName($notification->attribute('category_id'));

        $replacers = [
            'type'=> $type,
            'id'=> $id,
            'category'=> $category,
        ];

        return preg_replace("|{(\w*)}|e", '$replacers["$1"]', $channel);
    }

    /**
     * Get the category name for this category id.
     *
     * @param int $categoryId
     * @return string
     */
    protected function getCategoryName($categoryId)
    {
        if (! array_key_exists($categoryId, $this->categoryNames)) {
            $this->categoryNames[$categoryId] = NotificationCategory::firstOrFail($categoryId)->name;
        }

        return $this->categoryNames[$categoryId];
    }
}

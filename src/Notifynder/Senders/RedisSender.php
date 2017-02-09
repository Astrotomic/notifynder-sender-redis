<?php

namespace Astrotomic\Notifynder\Senders;

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
    protected $config;

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
        $this->config = notifynder_config('senders.redis');
    }

    /**
     * Send all notifications.
     *
     * @param SenderManagerContract $sender
     * @return bool
     */
    public function send(SenderManagerContract $sender)
    {
        $store = $this->config['store'];
        foreach ($this->notifications as $notification) {
            $channel = $this->parseChannel($notification);
            app('redis')->publish($channel, $notification->toJson());
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
        $channel = $this->config['channel'];
        $type = $notification->attribute('to_type', notifynder_config()->getNotifiedModel());
        $id = $notification->attribute('to_id');
        $category = $this->getCategoryName($notification->attribute('category_id'));

        $replacers = [
            'type'=> strtolower(class_basename($type)),
            'id'=> $id,
            'category'=> $category,
        ];

        return preg_replace_callback("|{(\w*)}|", function ($hits) use ($replacers) {
            return array_get($replacers, $hits[1]);
        }, $channel);
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
            $this->categoryNames[$categoryId] = NotificationCategory::findOrFail($categoryId)->name;
        }

        return $this->categoryNames[$categoryId];
    }
}

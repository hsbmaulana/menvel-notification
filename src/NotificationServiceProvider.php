<?php

namespace Menvel\Notification;

use Menvel\Repository\RepositoryServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $map =
    [
        \Menvel\Notification\Contracts\Repository\INotificationRepository::class => \Menvel\Notification\Repositories\Eloquent\NotificationRepository::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Event::listen(function (\Illuminate\Notifications\Events\NotificationSent $notif) {

            if ($notif->channel === 'database' && method_exists($notif->notification, 'broadcastType')) {

                $notif->response->type = $notif->notification->broadcastType();
                $notif->response->save();
            }
        });
    }
}
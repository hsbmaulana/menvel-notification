<?php

namespace Menvel\Notification\Repositories\Eloquent;

use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Menvel\Notification\Events\Marking;
use Menvel\Notification\Events\Marked;
use Menvel\Notification\Events\Clearing;
use Menvel\Notification\Events\Cleared;
use Menvel\Repository\AbstractRepository;
use Menvel\Notification\Contracts\Repository\INotificationRepository;

class NotificationRepository extends AbstractRepository implements INotificationRepository
{
    /**
     * @param array $querystring
     * @return mixed
     */
    public function all($querystring = [])
    {
        $user = $this->user; $content = null;
        $querystring =
        [
            'notification_limit' => $querystring['notification_limit'] ?? 10,
            'notification_current_page' => $querystring['notification_current_page'] ?? 1,
            'notification_type' => $querystring['notification_type'] ?? null,
        ];
        extract($querystring);
        $notification_type = $notification_type ? explode(',', $notification_type) : null;

        $filter = function ($subquery) use ($notification_type) { $subquery->whereIn('type', $notification_type); };
        $user = $user->setRelation('notifications', $user->notifications()->when($notification_type, $filter)->orderBy('read_at', 'asc')->paginate($notification_limit, '*', 'notification_current_page', $notification_current_page)->appends($querystring));
        $content = $user->loadCount(
        [
            'notifications',
            'unreadNotifications' => function ($query) use ($filter, $notification_type) { $query->when($notification_type, $filter); },
            'readNotifications' => function ($query) use ($filter, $notification_type) { $query->when($notification_type, $filter); },
        ]);

        return $content;
    }

    /**
     * @param int|string|null $identifier
     * @param array $data
     * @return mixed
     */
    public function modify($identifier, $data)
    {
        $user = $this->user; $content = null;

        if ($identifier != null) $content = $user->unreadNotifications()->where('id', $identifier)->firstOrFail();
        else $content = $user->unreadNotifications;

        DB::beginTransaction();

        try {

            $content->markAsRead();

            DB::commit();

            event(new Marked($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string|null $uuid
     * @param array $data
     * @return mixed
     */
    public function markAsRead($uuid = null)
    {
        return $this->modify($uuid, null);
    }

    /**
     * @param int|string|null $identifier
     * @return mixed
     */
    public function remove($identifier)
    {
        $user = $this->user; $content = null;

        if ($identifier != null) $content = $user->notifications()->where('id', $identifier)->firstOrFail();
        else $content = $user->notifications();

        DB::beginTransaction();

        try {

            $content->delete();

            DB::commit();

            event(new Cleared($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string|null $uuid
     * @return mixed
     */
    public function clear($uuid = null)
    {
        return $this->remove($uuid);
    }
}
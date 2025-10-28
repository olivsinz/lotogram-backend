<?php

namespace App\Service;

use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    public static function send(User $user, string $eventKey, $ownerableId, $ownerableType, array $events = [])
    {
        $notification = new Notification([
            'user_id' => $user->id,
            'key' => $eventKey,
            'read_at' => null,
            'notifiable_uuid' => $ownerableId,
            'notifiable_type' => $ownerableType
        ]);

        $notification->save();

        if (is_array($events) && count($events) > 0)
        {
            foreach ($events as $event)
            {
                event($event);
            }
        }
    }
}

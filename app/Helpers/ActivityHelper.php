<?php

namespace App\Helpers;

use App\Models\ActivityLogModel;

class ActivityHelper
{
    /**
     * Helper function untuk log activity
     * Bisa dipanggil dari mana saja dengan: activity_log(...)
     */
    public static function log(
        string $module,
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?int $targetId = null
    ): bool {
        $model = new ActivityLogModel();
        return $model->log($module, $action, $description, $targetType, $targetId);
    }

    /**
     * Helper untuk log user actions
     */
    public static function logUserAction(string $action, ?string $description = null, ?int $targetId = null): bool
    {
        return self::log('user', $action, $description, 'user', $targetId);
    }

    /**
     * Helper untuk log chat actions
     */
    public static function logChatAction(string $action, ?string $description = null, ?int $targetId = null): bool
    {
        return self::log('chat', $action, $description, 'message', $targetId);
    }

    /**
     * Helper untuk log proposal actions
     */
    public static function logProposalAction(string $action, ?string $description = null, ?int $targetId = null): bool
    {
        return self::log('proposal', $action, $description, 'proposal', $targetId);
    }

    /**
     * Helper untuk log profile actions
     */
    public static function logProfileAction(string $action, ?string $description = null, ?int $targetId = null): bool
    {
        return self::log('profile', $action, $description, 'profile', $targetId);
    }

    /**
     * Helper untuk log admin actions
     */
    public static function logAdminAction(string $action, ?string $description = null, ?int $targetId = null): bool
    {
        return self::log('admin', $action, $description, 'admin', $targetId);
    }
}

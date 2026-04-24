<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StoreNotificationTypeRequest;
use App\Http\Requests\MasterData\UpdateNotificationTypeRequest;
use App\Models\NotificationType;

class NotificationTypeController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return NotificationType::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.notification-types';
    }

    protected function routePrefix(): string
    {
        return 'master-data.notification-types';
    }

    protected function title(): string
    {
        return 'ประเภทที่แจ้ง';
    }

    protected function storeRequestClass(): string
    {
        return StoreNotificationTypeRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateNotificationTypeRequest::class;
    }
}
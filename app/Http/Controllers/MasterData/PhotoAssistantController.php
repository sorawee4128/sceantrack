<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StorePhotoAssistantRequest;
use App\Http\Requests\MasterData\UpdatePhotoAssistantRequest;
use App\Models\PhotoAssistant;

class PhotoAssistantController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return PhotoAssistant::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.photo-assistants';
    }

    protected function routePrefix(): string
    {
        return 'master-data.photo-assistants';
    }

    protected function title(): string
    {
        return 'ผู้ช่วยช่างภาพ';
    }

    protected function storeRequestClass(): string
    {
        return StorePhotoAssistantRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdatePhotoAssistantRequest::class;
    }
}
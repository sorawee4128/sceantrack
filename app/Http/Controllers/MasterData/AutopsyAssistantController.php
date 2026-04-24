<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StoreAutopsyAssistantRequest;
use App\Http\Requests\MasterData\UpdateAutopsyAssistantRequest;
use App\Models\AutopsyAssistant;

class AutopsyAssistantController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return AutopsyAssistant::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.autopsy-assistants';
    }

    protected function routePrefix(): string
    {
        return 'master-data.autopsy-assistants';
    }

    protected function title(): string
    {
        return 'ผู้ช่วยผ่า';
    }

    protected function storeRequestClass(): string
    {
        return StoreAutopsyAssistantRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateAutopsyAssistantRequest::class;
    }
}
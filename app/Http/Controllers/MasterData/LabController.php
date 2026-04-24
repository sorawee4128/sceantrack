<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StoreLabRequest;
use App\Http\Requests\MasterData\UpdateLabRequest;
use App\Models\Lab;

class LabController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return Lab::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.labs';
    }

    protected function routePrefix(): string
    {
        return 'master-data.labs';
    }

    protected function title(): string
    {
        return 'ห้องปฎิบัติการ';
    }

    protected function storeRequestClass(): string
    {
        return StoreLabRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateLabRequest::class;
    }
}
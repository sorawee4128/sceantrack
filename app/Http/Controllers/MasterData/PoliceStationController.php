<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StorePoliceStationRequest;
use App\Http\Requests\MasterData\UpdatePoliceStationRequest;
use App\Models\PoliceStation;

class PoliceStationController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return PoliceStation::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.police-stations';
    }

    protected function routePrefix(): string
    {
        return 'master-data.police-stations';
    }

    protected function title(): string
    {
        return 'สถานีตำรวจ';
    }

    protected function storeRequestClass(): string
    {
        return StorePoliceStationRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdatePoliceStationRequest::class;
    }
}
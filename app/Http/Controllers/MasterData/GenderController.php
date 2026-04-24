<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StoreGenderRequest;
use App\Http\Requests\MasterData\UpdateGenderRequest;
use App\Models\Gender;

class GenderController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return Gender::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.genders';
    }

    protected function routePrefix(): string
    {
        return 'master-data.genders';
    }

    protected function title(): string
    {
        return 'เพศ';
    }

    protected function storeRequestClass(): string
    {
        return StoreGenderRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateGenderRequest::class;
    }
}
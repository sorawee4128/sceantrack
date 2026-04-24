<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Requests\MasterData\StoreBodyHandlingRequest;
use App\Http\Requests\MasterData\UpdateBodyHandlingRequest;
use App\Models\BodyHandling;

class BodyHandlingController extends BaseMasterDataController
{
    protected function modelClass(): string
    {
        return BodyHandling::class;
    }

    protected function viewPrefix(): string
    {
        return 'master-data.body-handlings';
    }

    protected function routePrefix(): string
    {
        return 'master-data.body-handlings';
    }

    protected function title(): string
    {
        return 'การจัดการศพ';
    }

    protected function storeRequestClass(): string
    {
        return StoreBodyHandlingRequest::class;
    }

    protected function updateRequestClass(): string
    {
        return UpdateBodyHandlingRequest::class;
    }
}
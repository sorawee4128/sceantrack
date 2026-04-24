<?php

namespace App\Models;

class Gender extends BaseMasterData
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    public function sceneCases()
    {
        return $this->hasMany(SceneCase::class);
    }
}

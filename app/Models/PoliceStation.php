<?php

namespace App\Models;

class PoliceStation extends BaseMasterData
{
    protected $fillable = ['code','name', 'email'];
    public function sceneCases()
    {
        return $this->hasMany(SceneCase::class);
    }
}

<?php

namespace App\Models;

class NotificationType extends BaseMasterData
{
    public function sceneCases()
    {
        return $this->hasMany(SceneCase::class);
    }
}

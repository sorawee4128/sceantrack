<?php

namespace App\Models;

class BodyHandling extends BaseMasterData
{
    public function sceneCases()
    {
        return $this->hasMany(SceneCase::class);
    }
}

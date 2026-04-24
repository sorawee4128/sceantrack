<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SceneCasePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'scene_case_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    public function sceneCase()
    {
        return $this->belongsTo(SceneCase::class);
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }
}

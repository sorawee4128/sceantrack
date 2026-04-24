<?php

namespace App\Models;

use App\Enums\SceneCaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutopsyCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'autopsy_no',
        'doctor_user_id',
        'scene_case_id',
        'police_station_id',
        'autopsy_date',
        'autopsy_method',
        'photo_assistant_id',
        'autopsy_assistant_id',
        'lab_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'autopsy_date' => 'date',
        ];
    }

    public function scene()
    {
        return $this->belongsTo(SceneCase::class,'scene_case_id');
    }

    public function photo()
    {
        return $this->belongsTo(PhotoAssistant::class,'photo_assistant_id');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_user_id');
    }

    public function assistant()
    {
        return $this->belongsTo(AutopsyAssistant::class, 'autopsy_assistant_id');
    }

    public function policeStation()
    {
        return $this->belongsTo(PoliceStation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->doctor_user_id === $user->id;
    }
}

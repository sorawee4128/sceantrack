<?php

namespace App\Models;

use App\Enums\SceneCaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SceneCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shift_id',
        'scene_no',
        'doctor_user_id',
        'assistant_user_id',
        'autopsy_case_id',
        'case_date',
        'notified_time',
        'arrival_time',
        'police_station_id',
        'deceased_name',
        'gender_id',
        'age',
        'body_handling_id',
        'notification_type_id',
        'case_description',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'case_date' => 'date',
            'status' => SceneCaseStatus::class,
            'age' => 'integer',
        ];
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_user_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }

    public function policeStation()
    {
        return $this->belongsTo(PoliceStation::class);
    }

    public function bodyHandling()
    {
        return $this->belongsTo(BodyHandling::class);
    }

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function photos()
    {
        return $this->hasMany(SceneCasePhoto::class)->orderBy('sort_order');
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
        return $this->doctor_user_id === $user->id || $this->assistant_user_id === $user->id;
    }

    public function isDraft(): bool
    {
        return $this->status === SceneCaseStatus::DRAFT;
    }
}

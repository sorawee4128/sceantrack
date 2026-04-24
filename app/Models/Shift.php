<?php

namespace App\Models;

use App\Enums\ShiftType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_date',
        'shift_type',
        'doctor_user_id',
        'assistant_user_id',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'shift_date' => 'date',
            'shift_type' => ShiftType::class,
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_user_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sceneCases()
    {
        return $this->hasMany(SceneCase::class);
    }

    public function title(): string
    {
        $type = $this->shift_type instanceof ShiftType ? $this->shift_type->label() : $this->shift_type;

        return sprintf(
            '%s | Dr. %s | Asst. %s',
            $type,
            $this->doctor?->displayName() ?? '-',
            $this->assistant?->displayName() ?? '-'
        );
    }
}

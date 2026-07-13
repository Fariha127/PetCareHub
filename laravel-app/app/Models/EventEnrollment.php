<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventEnrollment extends Model
{
    protected $primaryKey = 'enrollment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            if (empty($model->enrollment_id)) {
                $model->enrollment_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

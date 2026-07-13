<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($model) {
            if (empty($model->event_id)) {
                $model->event_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(EventEnrollment::class, 'event_id', 'event_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_enrollments', 'event_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}

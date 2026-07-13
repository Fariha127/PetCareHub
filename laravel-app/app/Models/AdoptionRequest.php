<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    protected $primaryKey = 'request_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->request_id)) {
                $model->request_id = strtoupper(str_replace('-', '', \Illuminate\Support\Str::uuid()->toString()));
            }
        });
    }

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pet_id',
        'request_date',
        'status',
        'reviewed_by',
        'decision_date',
        'remarks',
    ];

    protected $casts = [
        'request_date' => 'date',
        'decision_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'pet_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    protected $primaryKey = 'request_id';

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

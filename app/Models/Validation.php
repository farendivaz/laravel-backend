<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_category_id',
        'society_id',
        'user_id',
        'status',
        'work_experience',
        'job_position',
        'reason_accepted',
        'validator_notes'
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }
}

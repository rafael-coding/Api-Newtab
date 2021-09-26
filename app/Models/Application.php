<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function getJob()
    {
        return $this->hasOne(Job::class);
    }

    public function getCandidate()
    {
        return $this->hasOne(Candidate::class);
    }

}

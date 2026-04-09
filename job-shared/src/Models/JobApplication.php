<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;



class JobApplication extends BaseModel
{
    protected $fillable = [
        'status',
        'aiGeneratedScore',
        'aiGeneratedFeedback',
        'job_vacancy_id',
        'resume_id',
        'user_id',


    ];

    protected function casts(): array
    {
        return [
            'deleted_at'        => 'datetime',
        ];
    }
    protected $dates = ['deleted_at'];
    // relation
    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');

    }

    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id', 'id');
    }

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}

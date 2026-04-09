<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\JobVacancy;
use App\Models\User;


class Company extends BaseModel
{
    protected $fillable = [
        'name',
        'address',
        'industry',
        'website',
        'owner_id',

    ];

    protected function casts(): array
    {
        return [

            'deleted_at'        => 'datetime',
        ];
    }
    protected $dates = ['deleted_at'];

    // relation

     public function owner(){
        return $this->belongsTo(User::class,'owner_id','id');
     }



    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class);
    }
    public function jobApplications()
    {
        return $this->hasManyThrough(JobApplication::class, JobVacancy::class);
    }


}

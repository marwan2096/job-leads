<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCategory;


class JobVacancy extends BaseModel
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'salary',
        'type',
        'view_count',
        'category_id',
        'company_id',

    ];

    protected function casts(): array
    {
        return [

            'deleted_at'        => 'datetime',
        ];
    }
    protected $dates = ['deleted_at'];



    // relation
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'category_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }


}

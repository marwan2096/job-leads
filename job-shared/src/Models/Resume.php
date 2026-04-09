<?php

namespace App\Models;



class Resume extends BaseModel
{
    protected $fillable = [
        'filename',
        'fileUri',
        'contactDetails',
        'summary',
        'skills',
        'experience',
        'education',
        'user_id',
    ];
    // Resume.php
protected $casts = [
    'skills'     => 'array',
    'experience' => 'array',
    'education'  => 'array',
];

    protected function casts(): array
    {
        return [

            'deleted_at'        => 'datetime',
        ];
    }
    protected $dates = ['deleted_at'];
    //relation
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');

}

    // relation
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'resume_id', 'id');

}
}

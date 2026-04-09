<?php
namespace App\Models;

use App\Models\BaseModel;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class JobCategory extends Model
{
     use HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

   
    protected $table = 'job_categories';
    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [

            'deleted_at'        => 'datetime',
        ];
    }
    protected $dates = ['deleted_at'];

    // relation
    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class, 'category_id', 'id');
    }

}

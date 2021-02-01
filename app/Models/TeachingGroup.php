<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingGroup extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function assessment_source()
    {
        return $this->belongsTo(AssessmentSource::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }


}

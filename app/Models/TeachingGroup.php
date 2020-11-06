<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\AssessmentSource;

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


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Models\TeachingGroup;
use App\Models\Student;
use App\Models\Baseline;

class AssessmentSource extends Model
{
    use HasFactory;
    use HasRelationships;

    public $incrementing = false;
    protected $guarded = [];

    public function teaching_groups()
    {
        return $this->hasMany(TeachingGroup::class);
    }

    public function baselines()
    {
        return $this->hasMany(Baseline::class);
    }

    public function baseline_for_student(Student $student)
    {
        return $this->baselines()->where('student_id', $student->urn)->firstOr(function() {
            return 'NOBASELINE';
        });
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function students()
    {
        return $this->hasManyDeep(Student::class, [TeachingGroup::class, 'student_teaching_group'])->withIntermediate(TeachingGroup::class);
    }
}

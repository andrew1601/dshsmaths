<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships as HasDeepRelationships;

class Cohort extends Model
{
    use HasFactory;
    use HasDeepRelationships;

    protected $guarded = [];

    public function assessment_sources()
    {
        return $this->hasManyThrough(Cohort::class, TeachingGroup::class);
    }

    public function teaching_groups()
    {
        return $this->hasMany(TeachingGroup::class);
    }

    public function students()
    {
        return $this->hasManyDeep(Student::class, [TeachingGroup::class, 'student_teaching_group'])->distinct();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\SurnameScope;

use App\Models\TeachingGroup;
use App\Models\AssessmentSource;
use App\Models\Baseline;

class Student extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new SurnameScope);
    }


    protected $primaryKey = 'upn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function teaching_groups()
    {
        return $this->belongsToMany(TeachingGroup::class);
    }

    public function baselines()
    {
        return $this->hasMany(Baseline::class);
    }

    public function baseline_for_assessment_source(AssessmentSource $assessment_source)
    {
        return $this->baselines()->where('assessment_source_id', '=', $assessment_source->id)->firstOr(function() {
            return 'NOBASELINE';
        });
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TeachingGroup;
use App\Models\Baseline;

class AssessmentSource extends Model
{
    use HasFactory;
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
}

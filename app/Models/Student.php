<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TeachingGroup;
use App\Models\Baseline;

class Student extends Model
{
    use HasFactory;
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyReview extends Model
{
    protected $fillable = [
        'company_id',
        'reviewer_name',
        'reviewer_role',
        'employment_type',
        'culture_rating',
        'compensation_rating',
        'leadership_rating',
        'work_life_rating',
        'growth_rating',
        'overall_rating',
        'would_recommend',
        'highlights',
        'challenges',
        'advice',
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

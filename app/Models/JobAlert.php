<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'keyword',
        'company',
        'location',
        'last_sent_at',
    ];

    protected $casts = [
        'last_sent_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobState extends Model
{
    use HasFactory;

    protected $table = 'jobstate';

    protected $fillable = [

        'id',
        'namestate',
        'code',
    ];
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdEvents extends Model
{
    use HasFactory;

    protected $table = 'job';

    protected $fillable = [
        'id',
        'typeEvent',
        'dateEvent',
        'contentEvent',
        'txtCodeRefus',
        'comment',
        'idDevis',
        'idUser',
        'idJob',
        'idCustomer',
    ];
}

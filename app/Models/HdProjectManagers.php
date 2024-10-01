<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdProjectManagers extends Model
{
    use HasFactory;

    protected $table = 'hd_project_managers';

    protected $fillable = [
        'id',
        'code',
        'name',
        'manager_name',
        'phone_number',
        'adress_one',
        'adress_two',
        'country',
        'pdf_header',
        'pdf_infos_header',
    ];
}


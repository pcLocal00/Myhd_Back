<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdCatProduct extends Model
{
    use HasFactory;

    protected $table = 'hd_cat_product';

    protected $fillable = [
        'id',
        'idFkCat',
        'idFkProduct',

    ];
}


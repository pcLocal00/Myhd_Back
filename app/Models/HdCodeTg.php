<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdCodeTg extends Model
{
    use HasFactory;
    protected $table = 'hd_code_tg';

    protected $fillable = [
        'id',
        'code',
        'label',
    ];
}

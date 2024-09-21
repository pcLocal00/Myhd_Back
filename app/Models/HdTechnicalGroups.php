<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdTechnicalGroups extends Model
{
    use HasFactory;
    protected $table = 'hd_technical_groups';

    protected $fillable = [
        'id',
        'code',
        'codeValeur',
        'valeur',
        'typeGroup',
        'requireTxtField',
        'labelTxtFiel',
    ];
    public function lineTg()
    {
        return $this->hasMany(HdLineTg::class);
    }
}

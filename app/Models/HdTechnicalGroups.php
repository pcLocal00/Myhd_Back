<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdTechnicalGroups extends Model
{
    use HasFactory;
    protected $table = 'hd_technical_groups';
    public $timestamps = false; 
    protected $fillable = [
        'id',
        'code',
        'codeValeur',
        'valeur',
        'typeGroup',
        'requireTxtField',
        'labelTxtField',
    ];
    public function lineTg()
    {
        return $this->hasMany(HdLineTg::class);
    }
}

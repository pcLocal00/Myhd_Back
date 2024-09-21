<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdLineTg extends Model
{
    use HasFactory;
    protected $table = 'hd_line_tg';

    protected $fillable = [
        'id',
        'code',
        'isDefaultValue',
        'isRequiredValue',
        'idFkTg',
        'idFkElement',
        'idFkProduct',
        'ordreShow',
    ];
    public function tg()
    {
        return $this->belongsTo(HdTechnicalGroups::class, 'idFkTg');
    }
}

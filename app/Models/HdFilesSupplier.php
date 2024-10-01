<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdFilesSupplier extends Model
{
    use HasFactory;
    protected $table = 'hd_files_supplier';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'uploadDate',
        'nameFile',
        'description',
        'path',
        'typeFile',
        'idFkSupplier',
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'idFkSupplier');
    }
}

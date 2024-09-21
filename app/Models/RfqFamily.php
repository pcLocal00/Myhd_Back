<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqFamily extends Model
{
    use HasFactory;

    protected $table = 'rfq_family';

    protected $fillable = [
        'idFamily ',
        'codeFamily',
        'nameFamily',
        'descFamily',
        'imagePathFamily',
        'iconePathFamily',
        'numOrderFamily',
        'enabledFamily',
        'isShowInCatalogue',
        'delaiLivraison',
        'idParentFamil',
    ];
    public function product()
    {
        return $this->hasMany(RfqProduct::class,'idFkFamily');
    }
}


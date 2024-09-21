<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqProduct extends Model
{
    use HasFactory;

    protected $table = 'rfq_product';

    protected $fillable = [
        'idProduct',
        'codeProduct',
        'descProduct',
        'nameProduct',
        'intraprintCodeProduct',
        'imagePathProduct',
        'numOrderProduct',
        'typeProduct',
        'nbElementProduct',
        'enabledProduct',
        'establishementProduct',
        'isBestSellersProduct',
        'estimateModelNumber',
        'priceModel',
        'manageFormatOuvert',
        'isUpdateNbPli',
        'delai',
        'detailspaoProduct',
        'estimateModelXmlData',
        'idFkFamily',
        'gestionRainage',
        'isActiveNbModel',
        'isShowAutreAssemblageFaconnange',
        'isShowAutreFinition',
        'isGeneri',
    ];

    public function family()
    {
        return $this->belongsTo(RfqFamily::class, 'idFkFamily','idFamily');
    }

    public function productElement()
    {
        return $this->hasMany(RfqProductElement::class,'idProduct','idFkProduct');
    }
}

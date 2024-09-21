<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqProductElement extends Model
{
    use HasFactory;

    protected $table = 'rfq_product_element';

    protected $fillable = [
        'idProductElement',
        'numElem',
        'elementCode',
        'descElement',
        'visibleElement',
        'titleElement',
        'persoTitleElement',
        'nbSectionsElement',
        'nbPagesElement',
        'typeElement',
        'subTypeElement',
        'numOrderProductElement',
        'technicalDataXml',
        'isActiveNbPage',
        'idFkRfqProduct',
    ];

}

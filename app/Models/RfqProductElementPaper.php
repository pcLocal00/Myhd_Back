<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqProductElementPaper extends Model
{
    use HasFactory;

    protected $table = 'rfq_product_element_paper';

    protected $fillable = [
        'idproduct_element_paper',
        'selected',
        'displayType',
        'enabled',
        'idFkProductElement',
        'idFkRfqPaper',
        'orderConfigPape',
    ];

    public function productElement()
    {
        return $this->belongsTo(RfqProductElement::class, 'idFkProductElement');
    }

    public function rfqPaper()
    {
        return $this->belongsTo(rfq_paper::class, 'idFkRfqPaper','idPaper');
    }
}


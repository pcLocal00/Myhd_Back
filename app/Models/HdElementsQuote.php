<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdElementsQuote extends Model
{
    use HasFactory;
    protected $table = 'hd_elements_quote';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'elementTitle',
        'txtFormatSelect',
        'txtFormat',
        'txtSupport',
        'txtGrammage',
        'txtAutreSupport',
        'txtPrintSelect',
        'txtPrint',
        'txtFaconnageSelect',
        'txtFaconnage',
        'txtFinitionSelect',
        'txtFinition',
        'txtOptionsDesc',
        'txtElementDescriptionSelect',
        'txtElementDescription',
        'txtElementNbPli',
        'txtElementFormatOuvertSelect',
        'txtElementFormatOuvert',
        'idConfiguredElement',
        'idfkquote',
    ];
}


<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rfq_paper extends Model
{
    use HasFactory;

    protected $table = 'rfq_paper';

    protected $fillable = [
        'idPaper',
        'labelPersoPaper',
        'labelPaper',
        'descPaper',
        'codePaper',
        'cfamPaper',
        'lfamPaper',
        'coulPaper',
        'lcoulpaper',
        'grammPaper',
        'longPaper',
        'largPaper',
        'crapidePaper',
        'ctypePaper',
        'ltypePaper',
        'certificationPaper',
        'xmlPaper',
        'enabled',
        'isActifMyH',
    ];

}


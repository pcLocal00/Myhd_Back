<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contact';
    protected $primaryKey = 'idContact';

    protected $fillable = [
        'idContact','genderContact','firstname','lastname','emailContact','metiersMyhd','linkedin','statusContact','phone','mobile','fax','adress',
        'zipcode','city','country','isFirst','emailnotification','batnotification','contactseq','is_contact_webtoprint','is_active','customerid',
        'fonction','typeContact','idFkSupplier','islivraison',
    ];
}


<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'hd_supplier';

    protected $fillable = [
        'id','ordre','code','typeFrs','name','siret','siren','tva','creationDate','updateDate','genderContact','firstnameContact','lastnameContact',
        'emailContact','adressOne','adressTwo','adressThree','zipCode','city','country','tel','portable','fax','websiteAdress','isActive','archived',
        'isSynchronisedCrm','isSynchronisedWtp'
    ];
    public function files()
    {
        return $this->hasMany(HdFilesSupplier::class,'idFkSupplier');
    }
}

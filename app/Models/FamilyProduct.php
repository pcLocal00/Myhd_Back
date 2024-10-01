<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyProduct extends Model
{
    use HasFactory;
    protected $table = 'rfq_family_product';
    
    protected $primaryKey = 'idFamily_product';

    protected $fillable = [ 'idFamily_product ', 'idFkProduct ', 'idFkFamily ', ];
}

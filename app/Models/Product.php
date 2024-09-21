<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'nv_product';

    protected $fillable = [
        'title','famille_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Famille::class);
    }
}

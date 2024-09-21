<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    use HasFactory;

    protected $table = 'nv_famille';

    protected $fillable = [
        'title','catalogue_id'
    ];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}

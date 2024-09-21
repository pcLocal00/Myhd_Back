<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

    protected $table = 'nv_catalogue';

    protected $fillable = [
        'title'
    ];

    public function famille()
    {
        return $this->hasMany(Famille::class);
    }
}


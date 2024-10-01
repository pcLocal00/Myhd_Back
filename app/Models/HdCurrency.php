<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HdCurrency extends Model
{
    use HasFactory;

    protected $table = 'hd_currencies';

    protected $fillable = [
        'code','label','symbol','active',
    ];

    public function famille()
    {
        return $this->hasMany(Famille::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'initial_value',
        'initial_date',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function mainCurrency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function secondaryCurrency()
    {
        return $this->belongsTo(Currency::class);
    }
}
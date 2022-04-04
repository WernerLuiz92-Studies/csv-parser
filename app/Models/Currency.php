<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'format',
        'last_update',
    ];

    public function parity()
    {
        return $this->belongsTo(Currency::class, 'parity_id');
    }

    public function historicalData()
    {
        return $this->hasMany(HistoricalData::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
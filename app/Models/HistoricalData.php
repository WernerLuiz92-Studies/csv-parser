<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalData extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'opening_value',
        'closing_value',
        'high_value',
        'low_value',
        'volume',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
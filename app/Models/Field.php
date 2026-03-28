<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'name',
        'image',
        'price_day',
        'price_night',
        'is_active'
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * 🔥 Helper: Ambil harga berdasarkan jam
     */
    public function getPriceByHour($hour)
    {
        if ($hour >= 8 && $hour < 17) {
            return $this->price_day;
        }

        if ($hour >= 17 && $hour < 23) {
            return $this->price_night;
        }

        return 0;
    }

    /**
     * 🔥 Helper: Cek apakah jam termasuk malam
     */
    public function isNight($hour)
    {
        return $hour >= 17 && $hour < 23;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'field_id',
        'date',
        'start_time',
        'end_time',
        'duration_hours',
        'price_per_hour',
        'subtotal',
        'status'
    ];

    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    // ================= RELATION =================

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
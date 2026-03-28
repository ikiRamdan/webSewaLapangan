<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Karena tabel tidak punya updated_at
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'created_at'
    ];

    // 🔥 TAMBAHKAN INI UNTUK MEMPERBAIKI ERROR FORMAT()
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
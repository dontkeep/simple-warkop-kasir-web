<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'jumlah_item',
        'total_harga',
        
    ];

    public function details()
    {
        return $this->hasMany(\App\Models\Detail_Transaksi::class, 'transaksi_id');
    }
}

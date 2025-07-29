<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'nama_menu',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];
}

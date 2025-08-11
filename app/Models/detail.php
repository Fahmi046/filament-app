<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail extends Model
{
    use HasFactory;

    protected $table = 'details';

    protected $fillable = [
        'barang_id',
        'faktur_id',
        'diskon',
        'nama_barang',
        'harga',
        'subtotal',
        'qty',
        'hasil_qty',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function faktur()
    {
        return $this->belongsTo(faktur::class, 'faktur_id');
    }
}

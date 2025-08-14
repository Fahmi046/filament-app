<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    protected $table = "penjualan";
    protected $fillable = [
        'kode',
        'tanggal',
        'jumlah',
        'customer_id',
        'faktur_id',
        'status',
        'keterangan',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }

    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'id');
    }
}

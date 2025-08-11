<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class faktur extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fakturs';
    protected $fillable = [
        'kode_faktur',
        'tanggal_faktur',
        'kode_customer',
        'customer_id',
        'ket_faktur',
        'total',
        'nominal_charge',
        'charge',
        'total_final',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }

    public function details()
    {
        return $this->hasMany(detail::class, 'faktur_id');
    }
}

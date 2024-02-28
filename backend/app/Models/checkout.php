<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class checkout extends Model
{
    use HasFactory;
    public $increment = false;
    protected $KeyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = ['kode_transaksi_out', 'kode_mobil', 'tanggal_mulai', 'tanggal_akhir', 'jumlah_hari', 'status'];

    /**
     * Get the check_ that owns the checkout
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    /**
     * Get the mobil that owns the checkout
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mobil(): BelongsTo
    {
        return $this->belongsTo(mobil::class, 'kode_mobil', 'kode_mobil');
    }
}

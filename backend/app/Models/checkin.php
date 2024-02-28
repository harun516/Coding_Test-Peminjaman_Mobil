<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class checkin extends Model
{
    use HasFactory;
    public $increments = false;
    protected $KeyType = 'string';
    protected $primarykey = 'id';
    protected $fillable = ['kode_transaksi_in', 'kode_transaksi_out'];

    /**
     * Get the mobil that owns the checkin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checkout(): BelongsTo
    {
        return $this->belongsTo(checkout::class, 'kode_transaksi_out', 'kode_transaksi_out');
    }

    /**
     * Get the mobil that owns the checkin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mobil(): BelongsTo
    {
        return $this->belongsTo(mobil::class, 'kode_mobil', 'kode_mobil');
    }
}

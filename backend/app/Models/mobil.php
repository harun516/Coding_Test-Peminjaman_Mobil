<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mobil extends Model
{
    use HasFactory;
    public $increments = false;
    protected $keyType = 'string';
    protected $primarykey = 'id';
    protected $fillable = ['kode_mobil','merk','model','no_pol','tarif','tersedia'];

    /**
     * Get all of the mobil for the mobil
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkout(): HasMany
    {
        return $this->hasMany(checkout::class, 'kode_mobil', 'kode_mobil');
    }
}

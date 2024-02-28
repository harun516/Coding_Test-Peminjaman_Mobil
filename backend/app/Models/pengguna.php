<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengguna extends Model
{
    use HasFactory;
    public $increment = false;
    protected $primarykey = 'id';
    protected $keyType = 'string';
    protected $fillable = ['kode_pengguna', 'nama', 'alamat', 'telp', 'no_sim'];

}

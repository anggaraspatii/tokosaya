<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsis';

    public $incrementing = false;

    protected $fillable = ['id', 'nama'];

    public function kota()
    {
        return $this->hasMany(Kota::class, 'provinsi_id');
    }
}

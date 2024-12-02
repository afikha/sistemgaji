<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = ['nama_karyawan', 'jabatan_karyawan'];

    public function gajitenun()
    {
        return $this->hasMany(GajiTenun::class, 'karyawan_id');
    }
}
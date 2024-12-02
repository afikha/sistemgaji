<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gajitenun extends Model
{
    use HasFactory;

    protected $table = 'gajitenun';
    protected $fillable = ['karyawan_id', 'gaji', 'tanggal'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
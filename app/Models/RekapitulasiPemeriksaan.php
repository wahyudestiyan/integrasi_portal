<?php

// app/Models/RekapitulasiPemeriksaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapitulasiPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'rekapitulasi_pemeriksaan';

    protected $fillable = [
        'instansi_token_id',
        'tahun',
        'jumlah_sk_sekda',
        'jumlah_terdaftar_di_portal',
        'jumlah_data_terisi',
        'status',
    ];

    public function instansi()
    {
        return $this->belongsTo(InstansiToken::class, 'instansi_token_id');
    }
}

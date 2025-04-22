<?php

// app/Models/DataPrioritasBelumTerisi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPrioritasBelumTerisi extends Model
{
    use HasFactory;

    protected $table = 'data_prioritas_belum_terisi';

    protected $fillable = [
        'instansi_token_id',
        'tahun',
        'judul_data',
        'keterangan',  // Pastikan kolom yang ada di tabel sudah dimasukkan ke fillable
    ];

    // Relasi dengan InstansiToken
    public function instansi()
    {
        return $this->belongsTo(InstansiToken::class, 'instansi_token_id');
    }
}

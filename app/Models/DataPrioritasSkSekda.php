<?php

// app/Models/DataPrioritasSkSekda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPrioritasSkSekda extends Model
{
    use HasFactory;

    protected $table = 'data_prioritas_sk_sekda';

    protected $fillable = [
        'instansi_token_id',
        'tahun',
        'judul_data',
        'id_data_portal',
    ];

    public function instansi()
    {
        return $this->belongsTo(InstansiToken::class, 'instansi_token_id');
    }
    public function dataTerisi()
    {
        return $this->hasOne(DataPrioritasBelumTerisi::class, 'judul_data', 'judul_data')
            ->where('tahun', $this->tahun);
    }
    
    

}

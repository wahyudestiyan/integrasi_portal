<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiToken extends Model
{
    use HasFactory;

    protected $table = 'instansi_tokens';

    protected $fillable = [
        'nama_instansi',
        'bearer_token',
    ];

    public function dataApis()
    {
        return $this->hasMany(DataApi::class);
    }

    public function logs()
    {
        return $this->hasMany(DataApiLog::class);
    }

    public function dataPrioritasSk()
    {
        return $this->hasMany(DataPrioritasSkSekda::class, 'instansi_token_id');
    }

    public function dataBelumTerisi()
    {
        return $this->hasMany(DataPrioritasBelumTerisi::class, 'instansi_token_id');
    }

    public function rekapitulasi()
{
    return $this->hasMany(RekapitulasiPemeriksaan::class, 'instansi_token_id');
}

}

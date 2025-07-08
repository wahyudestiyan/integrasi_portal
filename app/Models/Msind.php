<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Msind extends Model
{
    protected $table = 'msind';

    protected $fillable = [
        'id_indikator_mms',
        'id_api',
        'id_mskeg',
        'nama',
        'konsep',
        'definisi',
        'interpretasi',
        'metode_perhitungan',
        'rumus',
        'ukuran',
        'satuan',
        'variabel_disaggregasi',
        'apakah_indikator_komposit',
        'indikator_pembangun',
        'variabel_pembangun',
        'level_estimasi',
        'apakah_indikator_bisa_diakses_umum',
        'requested_by',
        'updated_by',
        'approved_by',
        'produsen_data_name',
        'produsen_data_province_code',
        'produsen_data_city_code',
        'last_sync',
        'status',
        'submission_period',
        'link_msind',
        'link_mskeg',
         'created_at',
        'updated_at'
    ];

    protected $casts = [
        'konsep' => 'array',
        'variabel_disaggregasi' => 'array',
        'indikator_pembangun' => 'array',
        'variabel_pembangun' => 'array',
        'last_sync' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(IndahKegiatan::class, 'id_mskeg', 'id_keg');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Msvar extends Model
{
    protected $table = 'msvar';

    protected $fillable = [
        'id_variabel_mms',
        'id_api',
        'id_mskeg',
        'nama',
        'alias',
        'konsep',
        'definisi',
        'referensi_pemilihan',
        'referensi_waktu',
        'ukuran',
        'satuan',
        'tipe_data',
        'value_domain',
        'aturan_validasi',
        'kalimat_pertanyaan',
        'apakah_variabel_bisa_diakses_umum',
        'requested_by',
        'updated_by',
        'approved_by',
        'produsen_data_name',
        'produsen_data_province_code',
        'produsen_data_city_code',
        'last_sync',
        'status',
        'submission_period',
        'link_msvar',
        'link_mskeg'
    ];

    protected $casts = [
        'konsep' => 'array',
        'referensi_pemilihan' => 'array',
        'value_domain' => 'array',
        'aturan_validasi' => 'array',
        'last_sync' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(IndahKegiatan::class, 'id_mskeg', 'id_keg');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiBps extends Model
{
    use HasFactory;

    protected $table = 'apibps'; // Pastikan nama tabel sesuai dengan di database

    protected $fillable = [
        'nama_instansi',
        'nama_data',
        'url_api',
        'credential_key',
        'id_data',
        'token',
        'method',
        'status',
    ];

    // Relasi ke ApiResponse
    public function apiResponses()
    {
        return $this->hasMany(ApiResponse::class, 'apibps_id');
    }

    // Relasi ke DataMapping
    public function dataMappings()
    {
        return $this->hasMany(DataMapping::class, 'apibps_id');
    }
}

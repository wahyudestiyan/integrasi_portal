<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
        return $this->hasMany(ApiResponse::class, 'api_id');
    }

    // Relasi ke DataMapping
    public function dataMappings()
    {
        return $this->hasMany(DataMapping::class, 'api_id');
    }
}

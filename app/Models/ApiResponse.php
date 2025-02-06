<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    use HasFactory;

    protected $fillable = ['api_id', 'response_data', 'version_timestamp', 'is_latest'];

    protected $casts = [
        'response_data' => 'array', // JSON otomatis dikonversi ke array
    ];

    // Scope untuk mengambil data terbaru
    public function scopeLatestVersion($query)
    {
        return $query->where('is_latest', true);
    }
}
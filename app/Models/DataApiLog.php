<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_api_id',
        'instansi_token_id',
        'tipe_perubahan',
        'judul_lama',
        'judul_baru',
    ];

    public function instansi()
    {
        return $this->belongsTo(InstansiToken::class);
    }

    public function dataApi()
    {
        return $this->belongsTo(DataApi::class);
    }
    
}

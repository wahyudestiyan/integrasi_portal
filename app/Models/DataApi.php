<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataApi extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'instansi_token_id','id_api','judul','tahun_data'];

    public function instansi()
    {
        return $this->belongsTo(InstansiToken::class);
    }

    public function logs()
    {
        return $this->hasMany(DataApiLog::class);
    }
    

}

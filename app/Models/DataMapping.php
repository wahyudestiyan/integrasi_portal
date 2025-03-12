<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMapping extends Model
{
    use HasFactory;

    protected $fillable = ['api_id', 'apibps_id', 'source_field', 'target_field', 'jsonhasil'];

    // Relasi ke Api
    public function api()
    {
        return $this->belongsTo(Api::class, 'api_id');
    }

    // Relasi ke ApiBps
    public function apiBps()
    {
        return $this->belongsTo(ApiBps::class, 'apibps_id');
    }
}

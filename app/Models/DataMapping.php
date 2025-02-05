<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMapping extends Model
{
    use HasFactory;

    protected $fillable = ['api_id', 'source_field', 'target_field', 'jsonhasil']; // Menambahkan jsonhasil
}

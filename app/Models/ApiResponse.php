<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    use HasFactory;

    protected $fillable = ['api_id', 'response_data'];

    protected $casts = [
        'response_data' => 'array', // Agar JSON otomatis diconvert ke array
    ];
}

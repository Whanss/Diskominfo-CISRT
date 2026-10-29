<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    protected $fillable = [
        'nama',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'kecamatan_id');
    }
}

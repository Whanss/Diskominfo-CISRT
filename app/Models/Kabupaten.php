<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';

    protected $fillable = [
        'nama',
        'kategori',
    ];

    public function kecamatans()
    {
        return $this->hasMany(\App\Models\Kecamatan::class, 'kabupaten_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'kabupaten_id');
    }
}

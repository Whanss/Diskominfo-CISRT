<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananCategory extends Model
{
    protected $table = 'layanan_categories';

    protected $fillable = [
        'layanan_id', // references master_layanan.id
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function layanan()
    {
        return $this->belongsTo(\App\Models\MasterLayanan::class, 'layanan_id');
    }

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'layanan_category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLayanan extends Model
{
    protected $table = 'master_layanan';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'layanan_id');
    }
}

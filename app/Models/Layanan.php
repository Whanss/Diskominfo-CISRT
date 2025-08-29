<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $fillable = [
        'admin_id',
        'ticket_id',
        'type',
        'tittle',
        'content',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

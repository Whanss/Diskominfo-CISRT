<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'action',
        'description',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

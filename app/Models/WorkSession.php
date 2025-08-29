<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'admin_id',
        'status',
        'started_at',
        'paused_at',
        'completed_at',
        'duration',
    ];

    protected $dates = [
        'started_at',
        'paused_at',
        'completed_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getCurrentDurationAttribute()
    {
        if ($this->status === 'active') {
            return now()->diffInSeconds($this->started_at);
        }

        return $this->duration;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'code_tracking', 'judul', 'nama_pelapor', 'email', 'no_hp',
        'description', 'status', 'resolved_at', 'resolution_category', 'resolved_by',
        'kabupaten_id', 'kecamatan_id', 'accepted_at', 'processing_started_at',
        'assigned_to', 'resolution_notes'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'accepted_at' => 'datetime',
        'processing_started_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function layanan()
    {
        return $this->hasMany(Layanan::class);
    }

    public function workSessions()
    {
        return $this->hasMany(WorkSession::class);
    }

    public function activeWorkSession()
    {
        return $this->hasOne(WorkSession::class)->where('status', 'active');
    }

    public function activityLogs()
    {
        return $this->hasMany(TicketActivityLog::class);
    }

    public function getTotalProcessingTimeAttribute()
    {
        // Use work sessions if available, otherwise fallback to old method
        $workSessionsTime = $this->workSessions()->sum('duration');

        if ($workSessionsTime > 0) {
            return $workSessionsTime;
        }

        // Fallback to old calculation method
        if ($this->accepted_at && $this->resolved_at) {
            return $this->accepted_at->diffInSeconds($this->resolved_at);
        }

        if ($this->accepted_at) {
            return $this->accepted_at->diffInSeconds(now());
        }

        return 0;
    }

    public function getFormattedProcessingTimeAttribute()
    {
        return gmdate("H:i:s", $this->total_processing_time);
    }

    public function getIsProcessingAttribute()
    {
        return $this->status === 'diterima/approved' && $this->accepted_at && !$this->resolved_at;
    }

    public function getHasActiveWorkSessionAttribute()
    {
        return $this->activeWorkSession()->exists();
    }

    public function getCurrentWorkSessionAttribute()
    {
        return $this->activeWorkSession;
    }

    public function getWorkSessionStatsAttribute()
    {
        $sessions = $this->workSessions;

        return [
            'total_sessions' => $sessions->count(),
            'total_duration' => $sessions->sum('duration'),
            'completed_sessions' => $sessions->where('status', 'completed')->count(),
            'active_sessions' => $sessions->where('status', 'active')->count(),
            'paused_sessions' => $sessions->where('status', 'paused')->count(),
        ];
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}

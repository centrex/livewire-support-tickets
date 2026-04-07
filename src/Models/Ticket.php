<?php

namespace Centrex\LivewireSupportTickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Centrex\LivewireSupportTickets\Models\TicketReply;
use Centrex\LivewireSupportTickets\Models\TicketAttachment;
use App\Models\User;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'status',
        'priority',
        'category',
        'closed_at'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
        });

        static::updating(function ($ticket) {
            if ($ticket->isDirty('status') && in_array($ticket->status, ['resolved', 'closed'])) {
                $ticket->closed_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'blue',
            'in_progress' => 'yellow',
            'waiting_response' => 'orange',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray'
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['closed', 'resolved']);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}


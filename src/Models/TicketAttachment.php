<?php

namespace Centrex\LivewireSupportTickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Centrex\LivewireSupportTickets\Models\Ticket;
use Centrex\LivewireSupportTickets\Models\TicketReply;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'ticket_reply_id',
        'filename',
        'path',
        'mime_type',
        'size'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class, 'ticket_reply_id');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
<?php

namespace Centrex\LivewireSupportTickets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Centrex\LivewireSupportTickets\Models\TicketAttachment;
use App\Models\User;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_internal'
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
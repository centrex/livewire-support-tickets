<?php

namespace Centrex\LivewireSupportTickets\Policies;

use Centrex\LivewireSupportTickets\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine if the user can view any tickets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->is_admin || $user->id === $ticket->user_id || $user->id === $ticket->assigned_to;
    }

    /**
     * Determine if the user can create tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->is_admin || $user->id === $ticket->user_id;
    }

    /**
     * Determine if the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->is_admin || $user->id === $ticket->user_id;
    }

    /**
     * Determine if the user can assign tickets.
     */
    public function assign(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can change ticket status.
     */
    public function changeStatus(User $user): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine if the user can add internal notes.
     */
    public function addInternalNote(User $user): bool
    {
        return $user->is_admin;
    }
}
<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Livewire;

use Centrex\LivewireSupportTickets\Models\Ticket;
use Livewire\{Component, WithPagination};

class TicketList extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $priorityFilter = '';

    public $categoryFilter = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $perPage = 10;

    protected $queryString = [
        'search'         => ['except' => ''],
        'statusFilter'   => ['except' => ''],
        'priorityFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteTicket($ticketId): void
    {
        $ticket = Ticket::find($ticketId);

        if ($ticket && (auth()->id() === $ticket->user_id || auth()->user()->is_admin)) {
            $ticket->delete();
            session()->flash('message', 'Ticket deleted successfully!');
            $this->dispatch('ticketDeleted');
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter', 'categoryFilter']);
        $this->resetPage();
    }

    public function getTicketsProperty()
    {
        $query = Ticket::query()
            ->with(['user', 'assignedUser', 'replies'])
            ->when($this->search, function ($q): void {
                $q->where(function ($query): void {
                    $query->where('ticket_number', 'like', '%' . $this->search . '%')
                        ->orWhere('subject', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q): void {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($q): void {
                $q->where('priority', $this->priorityFilter);
            })
            ->when($this->categoryFilter, function ($q): void {
                $q->where('category', $this->categoryFilter);
            });

        // User can only see their own tickets unless admin
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.ticket-list', [
            'tickets' => $this->tickets,
        ]);
    }
}

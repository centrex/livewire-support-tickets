<?php

namespace Centrex\LivewireSupportTickets\Livewire;

use Centrex\LivewireSupportTickets\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

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
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteTicket($ticketId)
    {
        $ticket = Ticket::find($ticketId);
        
        if ($ticket && (auth()->id() === $ticket->user_id || auth()->user()->is_admin)) {
            $ticket->delete();
            session()->flash('message', 'Ticket deleted successfully!');
            $this->dispatch('ticketDeleted');
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter', 'categoryFilter']);
        $this->resetPage();
    }

    public function getTicketsProperty()
    {
        $query = Ticket::query()
            ->with(['user', 'assignedUser', 'replies'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('ticket_number', 'like', '%' . $this->search . '%')
                          ->orWhere('subject', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($q) {
                $q->where('priority', $this->priorityFilter);
            })
            ->when($this->categoryFilter, function ($q) {
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
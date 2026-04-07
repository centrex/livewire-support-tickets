<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Livewire;

use Centrex\LivewireSupportTickets\Models\{Ticket};
use Livewire\{Component, WithFileUploads};

class ShowTicket extends Component
{
    use WithFileUploads;

    public Ticket $ticket;

    public $replyMessage = '';

    public $attachments = [];

    public $isInternal = false;

    public $newStatus = '';

    public $assignedTo = null;

    protected $listeners = ['ticketUpdated' => '$refresh'];

    public function mount(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $this->ticket = $ticket;
        $this->newStatus = $ticket->status;
        $this->assignedTo = $ticket->assigned_to;
    }

    protected function rules()
    {
        return [
            'replyMessage'  => 'required|min:3',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
        ];
    }

    public function addReply()
    {
        $this->validate();

        $reply = $this->ticket->replies()->create([
            'user_id'     => auth()->id(),
            'message'     => $this->replyMessage,
            'is_internal' => $this->isInternal && auth()->user()->is_admin,
        ]);

        // Handle attachments
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $filename = $attachment->getClientOriginalName();
                $path = $attachment->store('ticket-attachments', 'public');

                $reply->attachments()->create([
                    'ticket_id' => $this->ticket->id,
                    'filename'  => $filename,
                    'path'      => $path,
                    'mime_type' => $attachment->getMimeType(),
                    'size'      => $attachment->getSize(),
                ]);
            }
        }

        // Update ticket status if customer replies
        if (!auth()->user()->is_admin && $this->ticket->status === 'waiting_response') {
            $this->ticket->update(['status' => 'in_progress']);
        }

        $this->reset(['replyMessage', 'attachments', 'isInternal']);
        $this->ticket->refresh();

        session()->flash('reply-success', 'Reply added successfully!');
    }

    public function updateStatus()
    {
        if (!auth()->user()->is_admin) {
            return;
        }

        $this->ticket->update([
            'status' => $this->newStatus,
        ]);

        session()->flash('message', 'Ticket status updated!');
        $this->dispatch('ticketUpdated');
    }

    public function updateAssignment()
    {
        if (!auth()->user()->is_admin) {
            return;
        }

        $this->ticket->update([
            'assigned_to' => $this->assignedTo,
        ]);

        session()->flash('message', 'Ticket assignment updated!');
        $this->dispatch('ticketUpdated');
    }

    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function render()
    {
        return view('livewire.show-ticket', [
            'replies' => $this->ticket->replies()->with(['user', 'attachments'])->get(),
            'users'   => auth()->user()->is_admin ? \App\Models\User::where('is_admin', true)->get() : [],
        ]);
    }
}

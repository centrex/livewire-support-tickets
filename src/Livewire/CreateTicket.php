<?php

declare(strict_types = 1);

namespace Centrex\LivewireSupportTickets\Livewire;

use Centrex\LivewireSupportTickets\Models\Ticket;
use Livewire\{Component, WithFileUploads};

class CreateTicket extends Component
{
    use WithFileUploads;

    public $subject = '';

    public $description = '';

    public $priority = 'medium';

    public $category = '';

    public $attachments = [];

    public $categories = [
        'Technical Issue',
        'Billing',
        'Account',
        'Feature Request',
        'Bug Report',
        'Other',
    ];

    protected $rules = [
        'subject'       => 'required|min:5|max:255',
        'description'   => 'required|min:10',
        'priority'      => 'required|in:low,medium,high,urgent',
        'category'      => 'nullable|string',
        'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
    ];

    protected $messages = [
        'subject.required'     => 'Please enter a subject for your ticket.',
        'subject.min'          => 'Subject must be at least 5 characters.',
        'description.required' => 'Please describe your issue.',
        'description.min'      => 'Description must be at least 10 characters.',
        'attachments.*.max'    => 'Each file must not exceed 5MB.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function removeAttachment($index)
    {
        array_splice($this->attachments, $index, 1);
    }

    public function submit()
    {
        $this->validate();

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'subject'     => $this->subject,
            'description' => $this->description,
            'priority'    => $this->priority,
            'category'    => $this->category,
            'status'      => 'open',
        ]);

        // Handle file uploads
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $filename = $attachment->getClientOriginalName();
                $path = $attachment->store('ticket-attachments', 'public');

                $ticket->attachments()->create([
                    'filename'  => $filename,
                    'path'      => $path,
                    'mime_type' => $attachment->getMimeType(),
                    'size'      => $attachment->getSize(),
                ]);
            }
        }

        session()->flash('message', 'Ticket created successfully! Ticket #' . $ticket->ticket_number);

        return redirect()->route('tickets.show', $ticket->id);
    }

    public function render()
    {
        return view('livewire.create-ticket');
    }
}

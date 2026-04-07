<div class="ticket-list-container">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Support Tickets</h2>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Ticket
        </a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Ticket #, subject..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="waiting_response">Waiting Response</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select wire:model.live="priorityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="flex items-end">
                <button wire:click="clearFilters" class="btn btn-secondary w-full">
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('ticket_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Ticket #
                            @if($sortField === 'ticket_number')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th wire:click="sortBy('priority')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Priority
                            @if($sortField === 'priority')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Replies
                        </th>
                        <th wire:click="sortBy('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            Created
                            @if($sortField === 'created_at')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-blue-600">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->subject, 50) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($ticket->description, 60) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-800">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->replies->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ticket->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                @if(auth()->id() === $ticket->user_id || auth()->user()->is_admin)
                                    <button wire:click="deleteTicket({{ $ticket->id }})" 
                                            wire:confirm="Are you sure you want to delete this ticket?"
                                            class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="mt-2 text-sm">No tickets found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<style>
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
    
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
</style>
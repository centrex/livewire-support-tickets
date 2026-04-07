<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Tickets
        </a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Header -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="font-medium text-blue-600">{{ $ticket->ticket_number }}</span>
                            <span>Created {{ $ticket->created_at->diffForHumans() }}</span>
                            <span>by {{ $ticket->user->name }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $ticket->status_color }}-100 text-{{ $ticket->status_color }}-800">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $ticket->priority_color }}-100 text-{{ $ticket->priority_color }}-800">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>

                @if($ticket->attachments->count() > 0)
                    <div class="mt-4 pt-4 border-t">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Attachments:</h3>
                        <div class="space-y-2">
                            @foreach($ticket->attachments as $attachment)
                                <a href="{{ Storage::url($attachment->path) }}" 
                                   target="_blank"
                                   class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $attachment->filename }} ({{ $attachment->formatted_size }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Replies -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-gray-900">Replies ({{ $replies->count() }})</h2>
                
                @foreach($replies as $reply)
                    @if(!$reply->is_internal || auth()->user()->is_admin)
                        <div class="bg-white rounded-lg shadow-sm p-6 {{ $reply->is_internal ? 'border-l-4 border-yellow-400' : '' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $reply->user->name }}
                                            @if($reply->is_internal)
                                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Internal Note</span>
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</div>

                            @if($reply->attachments->count() > 0)
                                <div class="mt-3 pt-3 border-t">
                                    <div class="space-y-2">
                                        @foreach($reply->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment->path) }}" 
                                               target="_blank"
                                               class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                {{ $attachment->filename }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                @if($replies->count() === 0)
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p>No replies yet</p>
                    </div>
                @endif
            </div>

            <!-- Reply Form -->
            @if(!in_array($ticket->status, ['closed']))
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add Reply</h3>

                    @if (session()->has('reply-success'))
                        <div class="alert alert-success mb-4">
                            {{ session('reply-success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="addReply">
                        <div class="mb-4">
                            <textarea wire:model="replyMessage" 
                                      rows="4"
                                      placeholder="Type your reply here..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('replyMessage') border-red-500 @enderror"></textarea>
                            @error('replyMessage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <input type="file" 
                                   wire:model="attachments" 
                                   multiple
                                   class="hidden"
                                   id="reply-file-upload">
                            <label for="reply-file-upload" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Attach Files
                            </label>

                            @if(!empty($attachments))
                                <div class="mt-2 space-y-1">
                                    @foreach($attachments as $index => $attachment)
                                        <div class="flex items-center justify-between text-sm text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                            <span>{{ $attachment->getClientOriginalName() }}</span>
                                            <button type="button" 
                                                    wire:click="removeAttachment({{ $index }})"
                                                    class="text-red-600 hover:text-red-800">
                                                ×
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(auth()->user()->is_admin)
                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           wire:model="isInternal"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Internal Note (only visible to staff)</span>
                                </label>
                            </div>
                        @endif

                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="btn btn-primary">
                            <span wire:loading.remove>Post Reply</span>
                            <span wire:loading>Posting...</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-600">
                    This ticket is closed. No further replies can be added.
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ticket Details</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <label class="block text-gray-500 mb-1">Category</label>
                        <p class="font-medium">{{ $ticket->category ?: 'Not specified' }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-500 mb-1">Created</label>
                        <p class="font-medium">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-500 mb-1">Last Updated</label>
                        <p class="font-medium">{{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>

                    @if($ticket->closed_at)
                        <div>
                            <label class="block text-gray-500 mb-1">Closed</label>
                            <p class="font-medium">{{ $ticket->closed_at->format('M d, Y h:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Controls -->
            @if(auth()->user()->is_admin)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Admin Controls</h3>
                    
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select wire:model="newStatus" 
                                wire:change="updateStatus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="waiting_response">Waiting Response</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <!-- Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                        <select wire:model="assignedTo" 
                                wire:change="updateAssignment"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-block;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover:not(:disabled) {
        background-color: #2563eb;
    }
    
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
    }
    
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
</style>
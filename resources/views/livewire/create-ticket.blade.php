<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Tickets
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Support Ticket</h2>

        <form wire:submit.prevent="submit">
            <!-- Subject -->
            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="subject"
                       wire:model.blur="subject" 
                       placeholder="Brief description of your issue"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category and Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <select id="category" 
                            wire:model="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select a category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" 
                            wire:model="priority" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description"
                          wire:model.blur="description" 
                          rows="6"
                          placeholder="Provide detailed information about your issue..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"></textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 10 characters</p>
            </div>

            <!-- File Attachments -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Attachments
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-md p-6">
                    <input type="file" 
                           wire:model="attachments" 
                           multiple
                           class="hidden"
                           id="file-upload">
                    <label for="file-upload" class="cursor-pointer flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                        <span class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 5MB each)</span>
                    </label>
                </div>

                @error('attachments.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Preview attachments -->
                @if(!empty($attachments))
                    <div class="mt-4 space-y-2">
                        @foreach($attachments as $index => $attachment)
                            <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $attachment->getClientOriginalName() }}</span>
                                </div>
                                <button type="button" 
                                        wire:click="removeAttachment({{ $index }})"
                                        class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div wire:loading wire:target="attachments" class="mt-2 text-sm text-blue-600">
                    Uploading files...
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    <span wire:loading.remove wire:target="submit">Create Ticket</span>
                    <span wire:loading wire:target="submit">Creating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
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
</style>
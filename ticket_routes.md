# Laravel Livewire Support Ticket System

A complete support ticket management system with real-time updates, file attachments, status tracking, and admin controls.

## Installation Guide

### 1. Run Migrations

```bash
php artisan make:migration create_tickets_table
php artisan make:migration create_ticket_replies_table
php artisan make:migration create_ticket_attachments_table
```

Copy the migration code provided and run:

```bash
php artisan migrate
```

### 2. Create Models

Create the following models in `app/Models/`:
- `Ticket.php`
- `TicketReply.php`
- `TicketAttachment.php`

### 3. Add is_admin to Users Table

Add migration:

```bash
php artisan make:migration add_is_admin_to_users_table
```

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false);
    });
}
```

Then run:
```bash
php artisan migrate
```

### 4. Update User Model

Add to `app/Models/User.php`:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
];

protected $casts = [
    'is_admin' => 'boolean',
];

public function tickets()
{
    return $this->hasMany(Ticket::class);
}

public function assignedTickets()
{
    return $this->hasMany(Ticket::class, 'assigned_to');
}
```

### 5. Create Livewire Components

```bash
php artisan make:livewire TicketList
php artisan make:livewire CreateTicket
php artisan make:livewire ShowTicket
```

Replace the generated files with the provided component code.

### 6. Create Policy

```bash
php artisan make:policy TicketPolicy --model=Ticket
```

Use the provided TicketPolicy code.

### 7. Register Policy

In `app/Providers/AuthServiceProvider.php`:

```php
use App\Models\Ticket;
use App\Policies\TicketPolicy;

protected $policies = [
    Ticket::class => TicketPolicy::class,
];
```

### 8. Configure Storage

Make sure the storage is linked:

```bash
php artisan storage:link
```

### 9. Add Routes

In `routes/web.php`:

```php
use App\Livewire\TicketList;
use App\Livewire\CreateTicket;
use App\Livewire\ShowTicket;

Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', TicketList::class)->name('tickets.index');
    Route::get('/tickets/create', CreateTicket::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', ShowTicket::class)->name('tickets.show');
});
```

### 10. Create Layouts

Create `resources/views/layouts/app.blade.php` if not exists:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('tickets.index') }}" class="text-xl font-bold text-gray-800">
                        Support Tickets
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->is_admin)
                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">Admin</span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    @livewireScripts
</body>
</html>
```

## Usage Examples

### Creating a Ticket

Users can create tickets by navigating to `/tickets/create`. They can:
- Set a subject and description
- Choose priority level (low, medium, high, urgent)
- Select a category
- Upload multiple attachments (max 5MB each)

### Viewing Tickets

Navigate to `/tickets` to see all tickets. The list includes:
- Search functionality
- Filter by status and priority
- Sortable columns
- Pagination

### Managing Tickets (Admin)

Admins have additional controls:
- Assign tickets to staff members
- Change ticket status
- Add internal notes (invisible to customers)
- View all tickets regardless of ownership

### Adding Replies

On the ticket detail page, users can:
- Add text replies
- Attach files to replies
- View conversation history
- See timestamps and user information

## Features

### ✅ Core Functionality
- **CRUD Operations**: Create, read, update, and delete tickets
- **Status Management**: Open, In Progress, Waiting Response, Resolved, Closed
- **Priority Levels**: Low, Medium, High, Urgent
- **File Attachments**: Support for multiple file types (PDF, DOC, images)
- **Real-time Updates**: Livewire reactivity for instant feedback

### ✅ User Features
- Create and track support tickets
- Add replies with attachments
- View ticket history
- Filter and search tickets
- Receive status notifications

### ✅ Admin Features
- View all tickets system-wide
- Assign tickets to staff members
- Change ticket status
- Add internal notes (staff-only)
- Track ticket metrics
- Bulk actions and filtering

### ✅ Security
- Policy-based authorization
- Users can only view their own tickets (unless admin)
- Protected file uploads
- CSRF protection
- Input validation and sanitization

## API Reference

### Ticket Model Methods

```php
// Scopes
Ticket::open()->get(); // Get open tickets
Ticket::assignedTo($userId)->get(); // Get tickets assigned to user
Ticket::byPriority('high')->get(); // Filter by priority

// Relationships
$ticket->user; // Ticket creator
$ticket->assignedUser; // Assigned staff member
$ticket->replies; // All replies
$ticket->attachments; // Ticket attachments

// Attributes
$ticket->status_color; // Get color for status badge
$ticket->priority_color; // Get color for priority badge
```

### Livewire Component Methods

```php
// TicketList Component
public function sortBy($field) // Sort tickets
public function deleteTicket($ticketId) // Delete ticket
public function clearFilters() // Reset all filters

// CreateTicket Component
public function submit() // Create new ticket
public function removeAttachment($index) // Remove attachment

// ShowTicket Component
public function addReply() // Add reply to ticket
public function updateStatus() // Update ticket status (admin)
public function updateAssignment() // Assign ticket (admin)
```

## Configuration

### File Upload Limits

Edit `config/livewire.php`:

```php
'temporary_file_upload' => [
    'disk' => 'local',
    'rules' => ['file', 'max:5120'], // 5MB max
    'directory' => 'livewire-tmp',
],
```

### Allowed File Types

In `CreateTicket.php` and `ShowTicket.php`:

```php
'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,txt,zip'
```

### Ticket Categories

Customize categories in `CreateTicket.php`:

```php
public $categories = [
    'Technical Issue',
    'Billing',
    'Account',
    'Feature Request',
    'Bug Report',
    'General Inquiry',
    'Other'
];
```

## Customization

### Adding Email Notifications

Create notification:

```bash
php artisan make:notification TicketCreated
```

```php
namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TicketCreated extends Notification
{
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Support Ticket: ' . $this->ticket->ticket_number)
            ->line('A new support ticket has been created.')
            ->line('Subject: ' . $this->ticket->subject)
            ->action('View Ticket', route('tickets.show', $this->ticket->id))
            ->line('Thank you for using our support system!');
    }
}
```

Use in `CreateTicket.php`:

```php
use App\Notifications\TicketCreated;

public function submit()
{
    // ... existing code ...
    
    // Notify admins
    $admins = User::where('is_admin', true)->get();
    Notification::send($admins, new TicketCreated($ticket));
    
    return redirect()->route('tickets.show', $ticket->id);
}
```

### Adding Ticket Tags

Create migration:

```bash
php artisan make:migration create_ticket_tags_table
```

```php
Schema::create('ticket_tags', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('color')->default('gray');
    $table->timestamps();
});

Schema::create('ticket_tag_pivot', function (Blueprint $table) {
    $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
    $table->foreignId('ticket_tag_id')->constrained()->onDelete('cascade');
});
```

### Dashboard Widget

Create a dashboard component:

```bash
php artisan make:livewire TicketStats
```

```php
namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;

class TicketStats extends Component
{
    public function render()
    {
        return view('livewire.ticket-stats', [
            'openTickets' => Ticket::where('status', 'open')->count(),
            'inProgress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'avgResponseTime' => $this->calculateAvgResponseTime(),
        ]);
    }

    protected function calculateAvgResponseTime()
    {
        // Calculate average response time logic
        return '2 hours'; // Placeholder
    }
}
```

## Database Seeder

Create test data:

```bash
php artisan make:seeder TicketSeeder
```

```php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Ticket::create([
                'user_id' => $user->id,
                'subject' => 'Sample Ticket for ' . $user->name,
                'description' => 'This is a test ticket description.',
                'status' => 'open',
                'priority' => 'medium',
                'category' => 'Technical Issue'
            ]);
        }
    }
}
```

## Troubleshooting

**File uploads not working**: Check `php.ini` settings for `upload_max_filesize` and `post_max_size`

**Storage link error**: Run `php artisan storage:link` and ensure the `public/storage` directory exists

**Permission errors**: Ensure proper file permissions on `storage/app/public`

**Livewire not updating**: Clear cache with `php artisan cache:clear` and `php artisan livewire:publish --assets`

## Testing

Create tests:

```bash
php artisan make:test TicketTest
```

```php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Tests\TestCase;

class TicketTest extends TestCase
{
    public function test_user_can_create_ticket()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        
        $response = $this->post('/tickets', [
            'subject' => 'Test Ticket',
            'description' => 'Test description',
            'priority' => 'medium'
        ]);
        
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test Ticket',
            'user_id' => $user->id
        ]);
    }
}
```

## License

Open source - Free to use and modify for your projects!
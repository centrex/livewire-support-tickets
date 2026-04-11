# agents.md

## Agent Guidance — livewire-support-tickets

### Package Purpose
Full Livewire-powered support ticket system. Users submit tickets, agents reply, and admins manage status and assignment.

### Before Making Changes
- Read `src/Models/` — `Ticket` and reply model fields, status/priority enums
- Read `src/Livewire/` — Livewire components (ticket list, create form, ticket detail/reply)
- Read `src/Policies/` — who can create, view, reply, assign, and close tickets
- Read `src/Facades/` — the top-level API

### Common Tasks

**Adding a new ticket status**
1. Add the value to the status enum or config array
2. Update `Ticket` model scopes (e.g., `open()`, `closed()`, `resolved()`)
3. Update the Livewire component status filter/badge rendering
4. Update the Policy if the new status gates access to any action

**Adding ticket categories**
1. Add a `category_id` foreign key column with a nullable migration
2. Create a `TicketCategory` model if categories are dynamic (DB-driven)
3. Or use a config array if categories are static
4. Add a category filter to the Livewire list component

**Adding file attachments**
- Store file paths only — not binary data in the DB
- Use Laravel's storage disk (configurable via `config/`)
- Add a `ticket_attachments` migration with `ticket_id`, `path`, `disk`, `original_name`
- Validate file type and size in the Livewire component — do not rely solely on JS

**Adding email notifications**
- Notifications belong in `src/Notifications/` using Laravel's notification system
- Dispatch from model observers or event listeners, not directly in Livewire components
- Make notifications opt-in via config

### Testing
```sh
composer test:unit        # pest
composer test:types       # phpstan
composer test:lint        # pint
```

Test the ticket lifecycle:
```php
$ticket = Ticket::factory()->create(['status' => 'open']);
$ticket->close();
expect($ticket->fresh()->status)->toBe('closed');
```

Test policy gates:
```php
expect($user->can('reply', $ticket))->toBeTrue();
expect($otherUser->can('close', $ticket))->toBeFalse();
```

### Safe Operations
- Adding new Livewire component features (props, methods)
- Adding new ticket statuses/priorities via config
- Adding nullable migration columns
- Adding policy methods for new actions

### Risky Operations — Confirm Before Doing
- Changing the `status` column type (e.g., string → enum at DB level)
- Renaming `ticket_id` in the replies table
- Changing Livewire component names (breaks host app `@livewire` references)

### Do Not
- Allow arbitrary users to view tickets they did not create — enforce via Policy
- Store file contents in the DB
- Send emails synchronously inside Livewire components — use queued notifications
- Skip `declare(strict_types=1)` in any new file

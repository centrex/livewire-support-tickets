# CLAUDE.md

## Package Overview

`centrex/livewire-support-tickets` — Livewire-powered support ticket system for Laravel.

Namespace: `Centrex\LivewireSupportTickets\`  
Service Provider: `LivewireSupportTicketsServiceProvider`  
Facade: `Facades/LivewireSupportTickets`

## Commands

Run from inside this directory (`cd livewire-support-tickets`):

```sh
composer install          # install dependencies
composer test             # full suite: rector dry-run, pint check, phpstan, pest
composer test:unit        # pest tests only
composer test:lint        # pint style check (read-only)
composer test:types       # phpstan static analysis
composer test:refacto     # rector refactor check (read-only)
composer lint             # apply pint formatting
composer refacto          # apply rector refactors
composer analyse          # phpstan (alias)
composer build            # prepare testbench workbench
composer start            # build + serve testbench dev server
```

Run a single test:
```sh
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Structure

```
src/
  LivewireSupportTickets.php
  LivewireSupportTicketsServiceProvider.php
  Facades/
  Commands/
  Livewire/                     # Livewire components (ticket list, create, view)
  Models/                       # Ticket, TicketReply models
  Policies/                     # Authorization policies
resources/views/livewire/
config/config.php
database/migrations/
tests/
workbench/
```

## Key Concepts

- Full ticket lifecycle: open → in-progress → resolved/closed
- `Models/Ticket`: stores subject, description, status, priority, assignee
- `Models/TicketReply`: threaded replies on tickets
- Policies control who can create, view, reply to, and close tickets
- Livewire components handle the full UI without page reloads

## Conventions

- PHP 8.2+, `declare(strict_types=1)` in all files
- Pest for tests, snake_case test names
- Pint with `laravel` preset
- Rector targeting PHP 8.3 with `CODE_QUALITY`, `DEAD_CODE`, `EARLY_RETURN`, `TYPE_DECLARATION`, `PRIVATIZATION` sets
- PHPStan at level `max` with Larastan

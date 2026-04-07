# This is my package livewire-support-tickets

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/livewire-support-tickets.svg?style=flat-square)](https://packagist.org/packages/centrex/livewire-support-tickets)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/livewire-support-tickets/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/livewire-support-tickets/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/livewire-support-tickets/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/livewire-support-tickets/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/livewire-support-tickets?style=flat-square)](https://packagist.org/packages/centrex/livewire-support-tickets)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## ✨ Key Features:
### 🎫 Ticket Management

Create tickets with subject, description, priority, and category
Unique ticket numbers (TKT-xxxxx format)
Multiple status levels (Open, In Progress, Waiting Response, Resolved, Closed)
Priority levels (Low, Medium, High, Urgent) with color coding
File attachments support (PDF, DOC, images, etc.)

### 💬 Communication

Reply system with threaded conversations
File attachments on replies
Internal notes for staff (invisible to customers)
Real-time updates with Livewire

### 🔐 Security & Authorization

Policy-based access control
Users can only see their own tickets
Admins can view and manage all tickets
File upload validation and size limits

### 👨‍💼 Admin Features

Assign tickets to staff members
Change ticket status
Add internal staff notes
View all tickets system-wide
Filter and search capabilities

### 🎨 UI/UX

Clean, modern interface
Responsive design
Color-coded status and priority badges
Search and filtering
Sortable columns
Pagination
File upload with preview
Loading states

### 🚀 Quick Start:

Run migrations
Create the models and components
Add routes
Configure file storage
Start creating tickets!

## Contents

  - [Installation](#installation)
  - [Usage](#usage)
  - [Testing](#testing)
  - [Changelog](#changelog)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Installation

You can install the package via composer:

```bash
composer require centrex/livewire-support-tickets
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="livewire-support-tickets-config"
```

This is the contents of the published config file:

```php
return [
];
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="livewire-support-tickets-migrations"
php artisan migrate
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="livewire-support-tickets-views"
```

## Usage

```php
$livewireSupportTickets = new Centrex\LivewireSupportTickets();
echo $livewireSupportTickets->echoPhrase('Hello, Centrex!');
```

## Testing

🧹 Keep a modern codebase with **Pint**:
```bash
composer lint
```

✅ Run refactors using **Rector**
```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:
```bash
composer test:types
```

✅ Run unit tests using **PEST**
```bash
composer test:unit
```

🚀 Run the entire test suite:
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [rochi88](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

# Ticket System with Filament

A modern ticket management system built with Laravel and Filament, providing a powerful admin interface for managing support tickets.

## Features

-   🎫 Ticket Management
-   👥 User Management
-   🔐 Role-based Access Control
-   📊 Dashboard with Analytics
-   📱 Responsive Design
-   🔍 Advanced Search & Filtering
-   📧 Email Notifications
-   📝 Rich Text Editor for Ticket Descriptions

## Requirements

-   PHP 8.2 or higher
-   Composer
-   Node.js & NPM
-   MySQL/PostgreSQL/SQLite
-   Git

## Installation

1. Clone and setup:

```bash
git clone git@github.com:ht3aa/ticket-system-filament.git
cd ticket-system-filament
git checkout dev
composer install
npm install
```

2. Configure environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Setup database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

4. Run migrations:

```bash
php artisan migrate --seed
```

admin user is

```php
email: admin@example.com
password: password
```

5. Start the servers:

```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000/admin/login` to access the admin panel.

## Development

-   Run tests: `php artisan test`
-   Run code style fixer: `./vendor/bin/pint`
-   Run static analysis: `./vendor/bin/phpstan analyse`

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

If you encounter any issues or have questions, please open an issue in the GitHub repository.

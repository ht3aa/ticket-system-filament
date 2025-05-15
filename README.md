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

1. Clone the repository:

```bash
git clone git@github.com:ht3aa/ticket-system-filament.git
cd ticket-system-filament
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install NPM dependencies:

```bash
npm install
```

4. Create environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Configure your database in `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations:

```bash
php artisan migrate
```

8. Create an admin user:

```bash
php artisan make:filament-user
```

9. Start the development server:

```bash
php artisan serve
```

10. In a separate terminal, start Vite:

```bash
npm run dev
```

## Accessing the Admin Panel

Visit `http://localhost:8000/user/login` and log in with your admin credentials.

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

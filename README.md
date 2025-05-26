# SPK Kelola Sampah - Waste Management Decision Support System

A Laravel-based decision support system for waste management, featuring role-based access control, waste type management, landfill (TPA) management, and decision-making capabilities.

## Features

-   🔐 **Authentication & Authorization**

    -   User authentication system
    -   Role-based access control
    -   Secure login/logout functionality

-   📊 **Dashboard**

    -   Interactive data visualization
    -   Real-time statistics
    -   Activity monitoring

-   🗑️ **Waste Management**

    -   Waste type categorization
    -   Waste data tracking
    -   Waste processing information

-   🏭 **Landfill (TPA) Management**

    -   Landfill location tracking
    -   Capacity management
    -   Operational status monitoring

-   🤖 **Decision Support System**

    -   Automated decision-making
    -   Decision history tracking
    -   Decision result analysis

-   📝 **Activity Logging**
    -   User activity tracking
    -   System operation logs
    -   Audit trail

## Requirements

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MySQL/MariaDB
-   Web Server (Apache/Nginx)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/spk-kelola-sampah.git
    cd spk-kelola-sampah
    ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install Node.js dependencies:

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

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

7. Run database migrations:

    ```bash
    php artisan migrate
    ```

8. Start the development server:

    ```bash
    php artisan serve
    ```

9. In a separate terminal, start Vite:
    ```bash
    npm run dev
    ```

## Development

The project uses Laravel 12.x with AdminLTE 3.x for the admin interface. Key development commands:

```bash
# Run tests
php artisan test

# Start development server with all services
composer dev

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Project Structure

-   `app/` - Core application code
    -   `Http/Controllers/` - Application controllers
    -   `Models/` - Eloquent models
    -   `Http/Middleware/` - Custom middleware
-   `resources/` - Views, assets, and language files
-   `routes/` - Application routes
-   `database/` - Migrations and seeders
-   `public/` - Publicly accessible files
-   `storage/` - Application storage
-   `tests/` - Automated tests

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the development team.

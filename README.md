# PHP Vending Machine System

A PHP & MySQL vending machine system featuring product management, inventory tracking, user authentication, role-based access control, and RESTful APIs.

## Features

- **Product Management**: Add, update, delete, and view products with inventory tracking
- **User Authentication**: Secure login/logout with session management
- **Role-Based Access Control**: Admin and user roles with different permissions
- **Transaction Logging**: Record all purchases with transaction history
- **RESTful API**: JSON-based API for external integrations
- **MVC Architecture**: Clean separation of concerns with Models, Views, and Controllers
- **JWT Authentication**: Token-based authentication for API endpoints

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer (for dependency management)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/php-vending-machine-system.git
   cd php-vending-machine-system
   ```

2. Install dependencies (if any):
   ```bash
   composer install
   ```

3. Set up the database:
   - Create a MySQL database named `vending_machine`
   - Run the schema file:
     ```bash
     mysql -u root -p < sql/schema.sql
     ```

4. Configure database connection:
   - Edit `config/database.php` with your database credentials

5. Start the development server:
   ```bash
   php -S localhost:8000 -t public/
   ```

6. Access the application at `http://localhost:8000`

## Usage

### Web Interface

- **Login**: Visit `/login` to authenticate
- **Products**: View available products at `/products`
- **Admin Functions**: Admins can manage products via the web interface

### API Endpoints

#### Authentication
- `POST /api/auth/login` - Login and get JWT token
  ```json
  {
    "username": "your_username",
    "password": "your_password"
  }
  ```

#### Products
- `GET /api/products` - List all products (requires auth)
- `GET /api/products/{id}` - Get product details (requires auth)
- `POST /api/products` - Create new product (admin only)
- `PUT /api/products/{id}` - Update product (admin only)
- `DELETE /api/products/{id}` - Delete product (admin only)
- `POST /api/products/{productId}/purchase` - Purchase product (requires auth)

## Project Structure

```
php-vending-machine-system/
├── app/
│   ├── controllers/     # HTTP request handlers
│   ├── core/           # Core framework components
│   ├── helpers/        # Utility functions
│   ├── models/         # Database models
│   └── views/          # Template files
├── config/             # Configuration files
├── public/             # Web root directory
├── sql/               # Database schema and migrations
├── tests/             # Unit tests
└── plans/             # Project documentation
```

## Testing

Run the test suite with PHPUnit:

```bash
./vendor/bin/phpunit
```

## Security Considerations

- Passwords are hashed using `password_hash()`
- JWT tokens expire after 1 hour
- Role-based access control for sensitive operations
- Input validation and sanitization
- Prepared statements for SQL queries

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License.

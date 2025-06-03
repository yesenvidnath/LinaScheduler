# LinaScheduler - Educational Resource Management System

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## About LinaScheduler

LinaScheduler is a comprehensive educational resource management system built with Laravel, designed to streamline the management of educational facilities, equipment, and course scheduling. This system provides a robust platform for educational institutions to efficiently manage their resources and scheduling needs.

### Key Features

- **Room Management**
  - Multiple room types (Library, Class, Laboratory, Study Area)
  - Detailed room specifications and equipment tracking
  - Smart board and projector availability tracking
  - Room capacity management

- **Equipment Management**
  - Equipment categorization and type management
  - Equipment booking system
  - Usage tracking and availability status
  - Equipment image management

- **Course Management**
  - Course creation and assignment
  - Student enrollment tracking
  - Course scheduling
  - Batch management

- **Booking System**
  - Class room booking
  - Equipment reservation
  - Conflict prevention
  - Resource availability checking

- **User Management**
  - Role-based access control
  - User designation system
  - Student and staff management
  - Administrative controls

### Technical Features

- RESTful API Architecture
- Secure Authentication (Sanctum)
- Soft Delete Implementation
- Comprehensive Data Validation
- Transaction Management
- Relationship Management
- Image Handling

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## System Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM
- Laravel CLI

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/LinaScheduler.git
cd LinaScheduler
```

2. Install PHP dependencies
```bash
composer install
```

3. Copy environment file and configure database
```bash
cp .env.example .env
```

4. Generate application key
```bash
php artisan key:generate
```

5. Run database migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

6. Start the development server
```bash
php artisan serve
```

## API Documentation

### Base URL
`http://localhost:8000/api`

### Authentication
All API routes are protected by Laravel Sanctum. Include the authentication token in the request header:
```
Authorization: Bearer {your-token}
```

### Available Endpoints

#### Equipment Management
- `GET /admin/equipmentrequestlist` - List all equipment requests
- `POST /admin/equipmentrequestlist/create` - Create new equipment request
- `GET /admin/equipmentrequestlist/show/{param}` - Show specific equipment request(s)
- `PUT /admin/equipmentrequestlist/update/{id}` - Update equipment request
- `DELETE /admin/equipmentrequestlist/destroy/{param}` - Delete equipment request(s)
- `PUT /admin/equipmentrequestlist/recover/{param}` - Recover deleted request(s)
- `GET /admin/equipmentrequestlist/deleted/{param}` - Show deleted requests

#### Room Management
- Room creation and management
- Room type assignment
- Facility management
- Image management

#### Course Management
- Course creation and updates
- Student enrollment
- Course scheduling
- Batch management

## Database Structure

The system uses a well-structured database with the following key tables:
- `rooms` - Manages all room information
- `equipment` - Tracks available equipment
- `courses` - Stores course information
- `bookings` - Handles room and equipment bookings
- `users` - Manages user information
- `students` - Stores student records

## Contributing

Please read our [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## Security

If you discover any security-related issues, please email your-email@example.com instead of using the issue tracker.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE) file for details

## Support

For support, please contact:
- Email: support@linascheduler.com
- Website: www.linascheduler.com

# 🎓 LinaScheduler - Smart Educational Resource Management System

<div align="center">

![Laravel Version](https://img.shields.io/badge/Laravel-v10.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP Version](https://img.shields.io/badge/PHP-v8.1+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-v5.7+-4479A1?style=for-the-badge&logo=mysql)
![License](https://img.shields.io/badge/license-MIT-blue?style=for-the-badge)

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

</div>

## 🌟 About LinaScheduler

LinaScheduler is a comprehensive educational resource management system built with Laravel, designed to streamline the management of educational facilities, equipment, and course scheduling. This system provides a robust platform for educational institutions to efficiently manage their resources and scheduling needs.

### ✨ Core Features

#### 🏛️ Room Management
- Smart classification of spaces (Library, Classroom, Laboratory, Study Areas)
- Real-time tracking of room equipment and facilities
- Interactive smart board and projector management
- Dynamic capacity optimization system
- Room image gallery management

#### 🔧 Equipment Management
- Hierarchical equipment categorization
- Smart booking and reservation system
- Real-time availability tracking
- Equipment condition monitoring
- Visual equipment catalog with images
- Equipment usage analytics

#### 📚 Course Management
- Intuitive course creation interface
- Advanced student enrollment system
- Smart scheduling algorithm
- Batch and group management
- Course progress tracking
- Resource allocation optimization

#### 📅 Booking System
- Intelligent room allocation
- Conflict-free equipment reservation
- Real-time availability checking
- Automated booking confirmation
- Booking history and analytics
- Resource usage optimization

#### 👥 User Management
- Comprehensive role-based access control
- Flexible user designation system
- Student profile management
- Staff and faculty administration
- Activity logging and monitoring
- User preference management

### 🚀 Technical Architecture

#### 🔌 API & Integration
- Modern RESTful API Architecture
- Comprehensive API Documentation
- Secure Authentication (Laravel Sanctum)
- Rate Limiting & Throttling
- API Versioning Support
- Cross-Origin Resource Sharing (CORS)

#### 🛡️ Security Features
- Role-Based Access Control (RBAC)
- Request Validation & Sanitization
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- Rate Limiting

#### 💾 Data Management
- Efficient Database Schema
- Soft Delete Implementation
- Transaction Management
- Database Relationships
- Query Optimization
- Data Validation

#### 📁 File Management
- Image Upload & Processing
- Secure File Storage
- File Type Validation
- Image Optimization
- Multiple Storage Support
- File Access Control

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

## 🔧 System Requirements

### Required Software
- PHP >= 8.1 with extensions:
  - BCMath
  - Ctype
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
- MySQL >= 5.7
- Composer 2.x
- Node.js >= 16.x & NPM
- Laravel CLI

### Recommended Server Specifications
- Memory: 2GB RAM minimum
- Storage: 20GB available space
- Processor: 2 cores minimum
- Network: Stable internet connection

## 🚀 Quick Start Guide

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

## 📚 API Documentation

### 🔗 Base URL
```
Development: http://localhost:8000/api
Production: https://api.linascheduler.com/api
```

### 🔒 Authentication
All API routes are protected by Laravel Sanctum. Include the authentication token in the request header:
```http
Authorization: Bearer {your-token}
Content-Type: application/json
Accept: application/json
```

### 🎯 Rate Limiting
- 60 requests per minute per IP
- 1000 requests per hour per authenticated user

### 🛠️ Available Endpoints

#### Equipment Management API
```http
GET    /admin/equipmentrequestlist          # List all equipment requests
POST   /admin/equipmentrequestlist/create   # Create new equipment request
GET    /admin/equipmentrequestlist/show/{param}    # View specific request(s)
PUT    /admin/equipmentrequestlist/update/{id}     # Update request
DELETE /admin/equipmentrequestlist/destroy/{param}  # Soft delete request(s)
PUT    /admin/equipmentrequestlist/recover/{param}  # Recover deleted request(s)
GET    /admin/equipmentrequestlist/deleted/{param}  # List deleted requests
```

#### Room Management API
```http
GET    /admin/rooms                    # List all rooms
POST   /admin/rooms/create            # Create new room
GET    /admin/rooms/{id}              # View room details
PUT    /admin/rooms/{id}              # Update room
DELETE /admin/rooms/{id}              # Soft delete room
POST   /admin/rooms/{id}/images       # Upload room images
GET    /admin/rooms/types             # List room types
```

#### Course Management API
```http
GET    /admin/courses                 # List all courses
POST   /admin/courses/create          # Create new course
GET    /admin/courses/{id}            # View course details
PUT    /admin/courses/{id}            # Update course
DELETE /admin/courses/{id}            # Soft delete course
POST   /admin/courses/enroll          # Enroll students
GET    /admin/courses/{id}/students   # List enrolled students
```

#### Booking Management API
```http
POST   /admin/bookings/create         # Create new booking
GET    /admin/bookings/check          # Check availability
PUT    /admin/bookings/{id}/confirm   # Confirm booking
DELETE /admin/bookings/{id}/cancel    # Cancel booking
GET    /admin/bookings/history        # View booking history
```

## 🗄️ Database Architecture

### Core Tables
| Table Name | Description | Key Relations |
|------------|-------------|---------------|
| `rooms` | Room management | flows, room_image_list |
| `equipment` | Equipment tracking | equipment_types, equipment_images |
| `courses` | Course information | course_list, booking_requests |
| `users` | User management | user_designation, honorifics |
| `students` | Student records | users, course_list |
| `bookings` | Resource booking | rooms, equipment, courses |

### Key Features
- Optimized indexing for fast queries
- Referential integrity enforcement
- Soft delete implementation
- Audit trails for critical tables
- Data versioning support
- Automated backups

## 👨‍💻 Development Guide

### Code Style
- Follow PSR-12 coding standards
- Use type hints and return types
- Write descriptive variable and function names
- Document complex logic with comments
- Keep functions small and focused
- Write unit tests for new features

### Git Workflow
```bash
git checkout -b feature/your-feature-name
git add .
git commit -m "feat: your descriptive commit message"
git push origin feature/your-feature-name
```

### Testing
```powershell
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature

# Run with coverage report
./vendor/bin/phpunit --coverage-html coverage
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create your feature branch
3. Write and test your code
4. Create a pull request

Read our [Contributing Guide](CONTRIBUTING.md) for detailed information.

## 🛡️ Security

For security vulnerabilities, please contact:
- Email: security@linascheduler.com
- PGP Key: [Download](https://linascheduler.com/pgp-key.asc)

## 📄 License

LinaScheduler is open-source software licensed under the [MIT License](LICENSE.md).

## 🆘 Support & Community

- 📧 Email: support@linascheduler.com
- 💬 Discord: [Join our community](https://discord.gg/linascheduler)
- 📚 Documentation: [docs.linascheduler.com](https://docs.linascheduler.com)
- 🐦 Twitter: [@LinaScheduler](https://twitter.com/LinaScheduler)

## 🙏 Acknowledgements

- Laravel Team for the excellent framework
- Our contributors and community
- All open-source packages used in this project

# 🏥 Hospital Management System

A comprehensive web-based hospital management system built with Laravel 12 that manages patient data, doctors, schedules, and appointments with an integrated approval workflow system.

## 🚀 Features

### 🔐 Authentication & Authorization
- Role-based access control (Admin/User)
- Session-based authentication
- Password encryption with bcrypt
- CSRF protection

### 📊 Core Management
- **Patient Management**: Complete patient data with medical history
- **Doctor Management**: Doctor profiles with specializations
- **Schedule Management**: Weekly doctor availability
- **Appointment Management**: Booking system with approval workflow

### 📋 Request System
- **Patient Request Approval**: Users submit patient data for admin approval
- **Appointment Request**: Request appointments with admin approval
- **Edit Request System**: Request edits to patient data

### 📤 Excel Import
- Import doctors from Excel/CSV files
- Import patients from Excel/CSV files
- Import schedules from Excel/CSV files
- Data validation and error handling

### 🎨 User Interface
- Bootstrap 5 responsive design
- Role-based dashboards
- Interactive forms with validation
- Real-time status updates

## 🛠 Technology Stack

- **Backend**: Laravel 12.20.0
- **Frontend**: Bootstrap 5.3.0, Blade Templates
- **Database**: MySQL (SQLite for development)
- **PHP**: 8.2.12
- **Libraries**: PhpSpreadsheet, Carbon

## 📊 Database Schema

The system uses 8 interconnected tables:

1. **users** - User authentication and roles
2. **patients** - Patient information and medical data
3. **doctors** - Doctor profiles and specializations
4. **schedules** - Doctor availability schedules
5. **appointments** - Patient-doctor appointments
6. **patient_requests** - Patient data approval requests
7. **appointment_requests** - Appointment approval requests
8. **patient_edit_requests** - Patient data edit requests

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- MySQL/MariaDB
- Node.js

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/hospital-management-system.git
   cd hospital-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Edit .env file with your database credentials
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## � Project Structure

```
hospital-management-system/
├── app/                    # Laravel application files
├── database/              # Migrations, seeders, factories
├── resources/             # Views, CSS, JS files
├── routes/                # Application routes
├── sample-data/           # Sample Excel/CSV files for testing
│   ├── data dokter.csv    # Sample doctor data
│   ├── data dokter.xlsx   # Sample doctor data (Excel)
│   ├── data pasien.csv    # Sample patient data
│   ├── jadwal dokter.csv  # Sample schedule data
│   └── README.md          # Sample data documentation
├── setup-files/           # Composer setup files
│   ├── composer-setup.php # Composer installer
│   ├── composer.phar      # Composer executable
│   └── README.md          # Setup instructions
├── dokumentasi.md         # Complete technical documentation
└── README.md              # This file
```

## 🧪 Testing with Sample Data

The `sample-data/` folder contains ready-to-use Excel and CSV files for testing the import functionality:

1. **Login as admin** (admin@hospital.com / password)
2. **Navigate to Import** (`/import`)
3. **Upload sample files**:
   - `data dokter.csv` or `data dokter.xlsx` - For doctor import
   - `data pasien.csv` - For patient import  
   - `jadwal dokter.csv` - For schedule import
4. **Verify imported data** in respective management sections

## �👥 Default Credentials

- **Admin**: admin@hospital.com / password
- **User**: user@hospital.com / password

## 📖 Usage

### Admin Features
- Manage all hospital data (patients, doctors, schedules)
- Approve/reject patient requests
- Approve/reject appointment requests
- Import data from Excel files
- View comprehensive statistics

### User Features
- Submit patient data requests
- Request appointments with doctors
- View appointment history
- Check request status

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

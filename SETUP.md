# Hospital Management System - Quick Setup Guide

## 🚀 Quick Setup for Different Environments

### Windows (XAMPP)
```bash
# 1. Start XAMPP (Apache + MySQL)
# 2. Open phpMyAdmin (http://localhost/phpmyadmin)
# 3. Create database or import SQL file

# Database Configuration:
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=

# Import database:
mysql -u root -p < database/hospital_management_setup.sql
```

### Linux (Ubuntu/Debian)
```bash
# 1. Install LAMP stack
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql

# 2. Import database
mysql -u root -p < database/hospital_management_setup.sql

# Database Configuration:
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### Mac (MAMP)
```bash
# 1. Install MAMP
# 2. Start MAMP servers
# 3. Open phpMyAdmin

# Database Configuration:
DB_HOST=localhost
DB_PORT=8889
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=root
```

### Docker
```bash
# 1. Create docker-compose.yml
version: '3.8'
services:
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: hospital_management_db
    ports:
      - "3306:3306"

# Database Configuration:
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=password
```

## 📋 Step-by-Step Setup

1. **Clone project** (or download files)
2. **Run** `composer install`
3. **Copy** `database/.env.example.custom` to `.env`
4. **Edit** `.env` with your database settings
5. **Import** `database/hospital_management_setup.sql`
6. **Run** `php artisan key:generate`
7. **Start** `php artisan serve`
8. **Access** `http://localhost:8000`

## 🔑 Login Credentials

- **Admin:** admin@hospital.com / password
- **User:** user@hospital.com / password

## 📞 Support

If you encounter issues, check the database/README.md for detailed troubleshooting.

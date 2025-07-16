# 🗄️ Database Configuration Guide

This folder contains database configuration files for the Hospital Management System that can be used across different devices and environments.

## 📁 Files Included

### 1. **hospital_management_setup.sql**
Complete SQL script to set up the database from scratch.

**Features:**
- Creates database `hospital_management_db`
- Creates all 8 main tables with proper relationships
- Creates Laravel system tables (cache, jobs, sessions, etc.)
- Inserts sample data for testing
- Creates indexes for better performance
- Creates views for reporting

**Usage:**
```bash
# Method 1: Using MySQL command line
mysql -u root -p < hospital_management_setup.sql

# Method 2: Using phpMyAdmin
# - Open phpMyAdmin
# - Click "Import" tab
# - Select hospital_management_setup.sql
# - Click "Go"

# Method 3: Using MySQL Workbench
# - Open MySQL Workbench
# - File → Open SQL Script
# - Select hospital_management_setup.sql
# - Execute script
```

### 2. **.env.example.custom**
Environment configuration template with database settings.

**Usage:**
```bash
# Copy to project root and rename
cp database/.env.example.custom .env

# Generate application key
php artisan key:generate

# Configure database settings in .env file
```

## 🚀 Setup Instructions

### **For New Environment/Device:**

1. **Install Prerequisites:**
   ```bash
   # Install PHP 8.2+, MySQL, Composer
   # Install XAMPP or WAMP (Windows)
   # Install LAMP (Linux) or MAMP (Mac)
   ```

2. **Clone/Copy Project:**
   ```bash
   git clone https://github.com/YOUR_USERNAME/hospital-management-system.git
   cd hospital-management-system
   ```

3. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

4. **Setup Database:**
   ```bash
   # Method A: Using SQL file
   mysql -u root -p < database/hospital_management_setup.sql
   
   # Method B: Using Laravel migrations
   cp database/.env.example.custom .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   ```

5. **Configure Environment:**
   ```bash
   # Edit .env file with your database credentials
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hospital_management_db
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

6. **Final Setup:**
   ```bash
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   ```

7. **Start Application:**
   ```bash
   php artisan serve
   ```

## 🔧 Environment-Specific Configuration

### **Development (Local):**
```env
APP_ENV=local
APP_DEBUG=true
DB_HOST=127.0.0.1
DB_PORT=3306
```

### **Production (Server):**
```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=your_server_ip
DB_PORT=3306
```

### **Docker Environment:**
```env
DB_HOST=mysql
DB_PORT=3306
```

## 📊 Database Schema

### **Main Tables:**
1. **users** - User authentication and roles
2. **doctors** - Doctor profiles and information
3. **patients** - Patient data and medical records
4. **schedules** - Doctor availability schedules
5. **appointments** - Patient-doctor appointments
6. **patient_requests** - Patient registration requests
7. **appointment_requests** - Appointment booking requests
8. **patient_edit_requests** - Patient data edit requests

### **Laravel System Tables:**
- **cache** - Application cache storage
- **jobs** - Queue jobs
- **sessions** - User sessions
- **failed_jobs** - Failed queue jobs

## 🔑 Default Login Credentials

After running the SQL setup script:

- **Admin:** admin@hospital.com / password
- **User:** user@hospital.com / password

## 📱 Different Device Configurations

### **Windows (XAMPP):**
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=
```

### **Linux (LAMP):**
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### **Mac (MAMP):**
```env
DB_HOST=localhost
DB_PORT=8889
DB_DATABASE=hospital_management_db
DB_USERNAME=root
DB_PASSWORD=root
```

### **Remote Server:**
```env
DB_HOST=your_server_ip
DB_PORT=3306
DB_DATABASE=hospital_management_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🚨 Troubleshooting

### **Common Issues:**

1. **Database Connection Error:**
   - Check database credentials in .env
   - Ensure MySQL service is running
   - Verify database exists

2. **Permission Errors:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. **Migration Errors:**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Cache Issues:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

## 📝 Notes

- Always backup your database before making changes
- Use different database names for different environments
- Keep your .env file secure and never commit it to version control
- Test the setup with sample data before using real data

## 🔒 Security Considerations

- Change default passwords in production
- Use strong database passwords
- Enable SSL for production databases
- Regularly update dependencies
- Monitor database access logs

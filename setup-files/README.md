# 🛠️ Setup Files

This folder contains setup files for the Hospital Management System development environment.

## 📁 Files Included

### 1. **composer-setup.php**
Composer installer script for setting up Composer on your system.

**Usage:**
```bash
php composer-setup.php
```

### 2. **composer.phar**
Composer executable file for managing PHP dependencies.

**Usage:**
```bash
php composer.phar install
php composer.phar update
```

## 🚀 Setup Instructions

### Installing Composer (if not already installed)

1. **Using composer-setup.php:**
   ```bash
   php composer-setup.php
   ```

2. **Or download from official site:**
   - Visit: https://getcomposer.org/download/
   - Follow installation instructions

### Installing Project Dependencies

1. **Using system Composer:**
   ```bash
   composer install
   ```

2. **Using composer.phar:**
   ```bash
   php composer.phar install
   ```

## 📋 Notes

- These files are included for convenience
- You can use system-wide Composer if already installed
- composer.phar is a portable version of Composer
- Always use the latest version from official sources in production

## 🔧 Alternative Installation

If you prefer to use the official Composer installer:

```bash
# Download installer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

# Install Composer
php composer-setup.php

# Remove installer
php -r "unlink('composer-setup.php');"
```

## ⚠️ Important

These setup files are for development environment only. For production deployment, use the official Composer installation methods.

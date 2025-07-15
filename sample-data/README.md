# 📊 Sample Data for Hospital Management System

This folder contains sample data files that can be used to test the Excel import functionality of the Hospital Management System.

## 📁 Files Included

### 1. **data dokter.csv / data dokter.xlsx**
Sample doctor data for testing doctor import functionality.

**Format:**
```
Name | Email | Phone | Specialty | Birth Date | Gender | Address
```

**Usage:**
- Go to `/import` in the application
- Select "Import Doctors" 
- Upload this file

### 2. **data pasien.csv**
Sample patient data for testing patient import functionality.

**Format:**
```
Name | Email | Phone | Address | Birth Date | Gender | Blood Type | Emergency Contact | Emergency Phone | Insurance Number
```

**Usage:**
- Go to `/import` in the application
- Select "Import Patients"
- Upload this file

### 3. **jadwal dokter.csv**
Sample schedule data for testing schedule import functionality.

**Format:**
```
Doctor Email | Day | Start Time | End Time | Available
```

**Usage:**
- Go to `/import` in the application
- Select "Import Schedules"
- Upload this file

## 🚀 How to Use

1. **Start the application**
   ```bash
   php artisan serve
   ```

2. **Login as admin**
   - Email: admin@hospital.com
   - Password: password

3. **Navigate to Import**
   - Go to `/import` or click "Import Excel" in the menu

4. **Upload sample files**
   - Choose the appropriate file for each import type
   - Click "Import" button

5. **Verify data**
   - Check doctors, patients, and schedules tables
   - Verify imported data appears correctly

## 📋 Notes

- Make sure to import doctors first before importing schedules
- Patient data will auto-generate patient numbers
- Schedule import requires existing doctors with matching emails
- All files support .csv, .xlsx, and .xls formats

## 🔧 File Format Requirements

- **CSV**: Comma-separated values
- **Excel**: .xlsx or .xls format
- **Headers**: First row should contain column headers
- **Data Types**: Ensure correct data types (dates, emails, etc.)

## ⚠️ Important

These are sample files for testing purposes only. Do not use in production with real patient data.

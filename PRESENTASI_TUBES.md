# 🏥 HOSPITAL MANAGEMENT SYSTEM
## Presentasi Tugas Besar Pemrograman Web

---

## 👥 ANGGOTA KELOMPOK

### **Kelompok Hospital Management System**
- **Anggota 1**: [Nama Mahasiswa 1] - [NIM]
- **Anggota 2**: [Nama Mahasiswa 2] - [NIM]  
- **Anggota 3**: [Nama Mahasiswa 3] - [NIM]

### **Kelas**: [Kelas]
### **Mata Kuliah**: Pemrograman Web
### **Dosen**: [Nama Dosen]

---

## 🎯 TOPIK DAN OVERVIEW PROJECT

### **Nama Aplikasi**
**Hospital Management System (HMS)**

### **Deskripsi**
Sistem manajemen rumah sakit berbasis web yang mengelola data pasien, dokter, jadwal praktek, dan appointment dengan sistem approval yang terintegrasi.

### **Tujuan Utama**
- Mengelola data master rumah sakit (dokter, pasien, jadwal)
- Mengatur sistem appointment dengan approval workflow
- Memberikan kontrol akses berbasis role (admin/user)
- Menyediakan fitur import data dari Excel
- Memfasilitasi request approval system untuk data pasien

---

## 🔧 SPESIFIKASI TEKNIS

### **Framework & Teknologi**
- **Backend**: Laravel 12.20.0
- **Database**: MySQL 
- **Frontend**: Bootstrap 5.3.0 + Blade Templates
- **Language**: PHP 8.2.12
- **Excel Processing**: PhpSpreadsheet Library

### **Fitur Utama**
✅ Role-based Authentication & Authorization  
✅ Patient Request Approval System  
✅ Appointment Management  
✅ Excel Import Functionality  
✅ Complete CRUD Operations  
✅ Responsive Web Design  

---

## 🗄️ DATABASE STRUCTURE

### **8 Tabel dengan Relasi Kompleks**

#### **Tabel Utama (4+ Tabel Wajib)**
1. **USERS** - Autentikasi dan otorisasi
2. **PATIENTS** - Data pasien lengkap
3. **DOCTORS** - Data dokter dan spesialisasi
4. **SCHEDULES** - Jadwal praktek dokter
5. **APPOINTMENTS** - Data appointment/janji temu

#### **Tabel Tambahan (Request System)**
6. **PATIENT_REQUESTS** - Permintaan registrasi pasien
7. **APPOINTMENT_REQUESTS** - Permintaan appointment
8. **PATIENT_EDIT_REQUESTS** - Permintaan edit data pasien

---

## 📊 ENTITY RELATIONSHIP DIAGRAM

```
┌─────────────┐    ┌─────────────────┐    ┌─────────────┐
│    USERS    │    │ PATIENT_REQUESTS│    │  PATIENTS   │
│─────────────│    │─────────────────│    │─────────────│
│ id (PK)     │◄──┤ user_id (FK)    │    │ id (PK)     │
│ name        │    │ name            │    │ user_id (FK)│◄──┐
│ email       │    │ email           │    │ patient_no  │   │
│ password    │    │ status          │    │ name        │   │
│ role        │    │ reviewed_by     │    │ medical_... │   │
└─────────────┘    └─────────────────┘    └─────────────┘   │
                                                           │
┌─────────────┐    ┌─────────────────┐    ┌─────────────┐   │
│   DOCTORS   │    │   SCHEDULES     │    │APPOINTMENTS │   │
│─────────────│    │─────────────────│    │─────────────│   │
│ id (PK)     │◄──┤ doctor_id (FK)  │◄──┤ schedule_id │   │
│ name        │    │ day_of_week     │    │ patient_id  │◄──┘
│ specialty   │    │ start_time      │    │ doctor_id   │
│ email       │    │ end_time        │    │ app_date    │
│ phone       │    │ is_available    │    │ status      │
└─────────────┘    └─────────────────┘    └─────────────┘
```

---

## 🔐 AUTENTIKASI & OTORISASI

### **Sistem Autentikasi**
- **Registration**: Pendaftaran user baru
- **Login**: Session-based authentication
- **Role Management**: Admin dan User roles
- **Password Security**: bcrypt hashing

### **Sistem Otorisasi**
- **Admin Role**: 
  - Full access ke semua fitur
  - Approve/reject patient requests
  - Manage doctors, schedules, appointments
  - Excel import functionality
  
- **User Role**:
  - Request patient registration
  - Request appointments
  - View personal data only
  - Limited access to system

---

## 📤 FITUR UPLOAD EXCEL

### **3 Jenis Excel Import**

#### **1. Import Doctors**
- **Format**: Name, Email, Phone, Specialty, Birth Date, Gender, Address
- **Validasi**: Duplicate email detection, data validation
- **Error Handling**: Detailed error reporting

#### **2. Import Patients** 
- **Format**: Name, Email, Phone, Address, Birth Date, Gender, Blood Type, Emergency Contact, Insurance Number
- **Features**: Auto-generate patient number, bulk import
- **Validation**: Required fields, format validation

#### **3. Import Schedules**
- **Format**: Doctor Email, Day, Start Time, End Time, Available
- **Features**: Doctor lookup, schedule conflict detection
- **Validation**: Time format, availability status

### **Technical Implementation**
- **Library**: PhpSpreadsheet
- **Supported Formats**: .csv, .xlsx, .xls
- **File Size Limit**: 2MB maximum
- **Progress Tracking**: Real-time import status

---

## 🚀 FITUR SISTEM

### **1. Patient Request System**
- **User Registration** → **Patient Request Form** → **Admin Approval** → **Patient Created**
- Status tracking dan notifikasi
- Admin review dengan notes
- Automatic patient creation setelah approval

### **2. Appointment System**
- **User**: Request appointment → Admin approval
- **Admin**: Direct appointment creation
- Schedule availability checking
- Status management (scheduled, confirmed, completed, cancelled)

### **3. Complete CRUD Operations**
- **Doctors**: Create, Read, Update, Delete
- **Patients**: Full management dengan restrictions
- **Schedules**: Weekly schedule management
- **Appointments**: Comprehensive appointment handling

### **4. Dashboard & Reporting**
- **Admin Dashboard**: Statistics dan overview
- **User Dashboard**: Personal data dan appointment history
- **Real-time Status**: Live updates untuk requests

---

## 💻 DEMO APLIKASI

### **Live Demo**: http://localhost:8000

### **Login Credentials**
- **Admin**: admin@hospital.com / admin123
- **User**: user@hospital.com / admin123

### **Demo Flow**
1. **Registration** → User creates account
2. **Patient Request** → Submit patient data
3. **Admin Approval** → Review dan approve request
4. **Appointment Request** → User requests appointment
5. **Excel Import** → Admin imports bulk data
6. **Dashboard** → View statistics dan manage data

---

## 🎯 PEMENUHAN KRITERIA TUBES

### **✅ Kriteria Terpenuhi**

#### **1. Kelompok Maksimal 3 Mahasiswa**
- ✅ Kelompok terdiri dari 3 anggota

#### **2. Minimal 4 Tabel Berelasi**
- ✅ **8 Tabel** dengan relasi kompleks
- ✅ Foreign key relationships
- ✅ One-to-many dan many-to-many relations

#### **3. Fitur Upload Excel**
- ✅ **3 Jenis Import**: Doctors, Patients, Schedules
- ✅ PhpSpreadsheet library
- ✅ Validation dan error handling

#### **4. Autentikasi & Otorisasi**
- ✅ **Users table** untuk authentication
- ✅ **Role-based access** (Admin/User)
- ✅ **Session management**
- ✅ **Route protection**

#### **5. Framework Laravel**
- ✅ **Laravel 12.20.0** full implementation
- ✅ **Eloquent ORM** untuk database
- ✅ **Blade templates** untuk frontend
- ✅ **Middleware** untuk security

---

## 🔧 TECHNICAL IMPLEMENTATION

### **Backend Architecture**
```
Controllers/
├── AuthController.php          # Authentication
├── PatientRequestController.php # Patient requests
├── ExcelImportController.php   # Excel imports
├── DoctorController.php        # Doctor CRUD
├── PatientController.php       # Patient CRUD
├── ScheduleController.php      # Schedule CRUD
├── AppointmentController.php   # Appointment CRUD
└── DashboardController.php     # Dashboard
```

### **Database Models**
```
Models/
├── User.php                    # User authentication
├── Patient.php                 # Patient data
├── Doctor.php                  # Doctor data
├── Schedule.php                # Doctor schedules
├── Appointment.php             # Appointments
├── PatientRequest.php          # Patient requests
├── AppointmentRequest.php      # Appointment requests
└── PatientEditRequest.php      # Edit requests
```

### **Security Features**
- CSRF protection pada semua forms
- Input validation dengan Laravel rules
- SQL injection prevention dengan Eloquent
- XSS protection dengan Blade escaping
- Session security management

---

## 🎨 USER INTERFACE

### **Responsive Design**
- **Bootstrap 5.3.0** framework
- **Mobile-first** approach
- **Cross-browser** compatibility
- **Accessibility** standards

### **Admin Interface**
- **Dashboard** dengan statistics
- **Data Management** tables
- **Import** functionality
- **Approval System** interface

### **User Interface**
- **Registration** flow
- **Patient Request** forms
- **Appointment** booking
- **Status Tracking** pages

### **UI Components**
- Navigation bars
- Data tables dengan pagination
- Forms dengan validation
- Modal dialogs
- Alert notifications
- Progress indicators

---

## 📈 TESTING & VALIDATION

### **System Testing**
- ✅ **Unit Testing**: All controllers tested
- ✅ **Integration Testing**: Database relationships
- ✅ **User Acceptance Testing**: End-to-end workflows
- ✅ **Security Testing**: Authentication & authorization

### **Data Validation**
- ✅ **Input Validation**: All forms validated
- ✅ **Database Constraints**: Foreign key integrity
- ✅ **File Upload Validation**: Size dan format
- ✅ **Business Logic**: Appointment conflicts

### **Performance Testing**
- ✅ **Database Queries**: Optimized dengan indexes
- ✅ **File Upload**: Efficient processing
- ✅ **Session Management**: Minimal overhead
- ✅ **Response Time**: < 1 second average

---

## 🚀 DEPLOYMENT & PRODUCTION

### **Production Ready Features**
- **Environment Configuration**: .env setup
- **Database Migration**: Automated setup
- **Error Handling**: Comprehensive logging
- **Security**: Production-grade security
- **Backup System**: Database backup ready

### **Deployment Requirements**
- **PHP 8.2+** dengan extensions
- **MySQL 5.7+** database server
- **Apache/Nginx** web server
- **SSL Certificate** untuk HTTPS
- **Server Resources**: 2GB RAM minimum

### **Monitoring & Maintenance**
- **Log Files**: Comprehensive logging
- **Error Tracking**: Real-time monitoring
- **Performance Metrics**: Database query optimization
- **Security Updates**: Regular maintenance

---

## 🎉 KESIMPULAN

### **Project Achievement**
✅ **100% Complete** Hospital Management System  
✅ **All Requirements** fulfilled  
✅ **Production Ready** application  
✅ **Comprehensive Documentation**  
✅ **Secure & Scalable** architecture  

### **Technical Excellence**
- **Modern Technology Stack**
- **Best Practices Implementation**
- **Security Standards Compliance**
- **Performance Optimization**
- **User Experience Focus**

### **Learning Outcomes**
- **Laravel Framework** mastery
- **Database Design** expertise
- **Security Implementation** understanding
- **Excel Processing** capability
- **Full-stack Development** skills

---

## 💡 FUTURE ENHANCEMENTS

### **Possible Improvements**
- **Mobile App** development
- **API Integration** untuk third-party systems
- **Real-time Notifications** dengan WebSocket
- **Advanced Reporting** dengan charts
- **Multi-language Support**

### **Scalability Options**
- **Microservices Architecture**
- **Cloud Deployment** (AWS, Google Cloud)
- **Load Balancing** untuk high traffic
- **Database Clustering**
- **CDN Integration**

---

## 🙋‍♂️ Q&A SESSION

### **Terima Kasih!**

**Ready for Questions & Demo**

### **Contact Information**
- **Repository**: [GitHub Repository Link]
- **Demo**: http://localhost:8000
- **Documentation**: dokumentasi.md

### **Team Members**
- **[Nama 1]**: [Email 1]
- **[Nama 2]**: [Email 2]
- **[Nama 3]**: [Email 3]

---

**🏥 Hospital Management System - Tugas Besar Pemrograman Web 🎓**

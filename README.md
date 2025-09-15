# 🧾 Stampit — Digital Fee Verification System

Stampit is a comprehensive Laravel-based digital fee verification system designed for final year Computer Science projects. It digitizes and automates the verification/stamping of school fee or IGR payment receipts, providing a modern web-based solution that eliminates physical queues and manual processing.

---

## 🎯 Key Features

### 👨‍🎓 Student Portal
- **Invoice Upload**: Upload school fees or IGR payment invoices with validation
- **Status Tracking**: Real-time status updates (pending, stamped, rejected)
- **Digital Receipts**: Download stamped receipts with official digital signatures
- **Email Notifications**: Automatic notifications with attached stamped documents
- **Dashboard**: Modern, responsive interface for managing submissions

### 👨‍💼 Admin/Officer Portal
- **Document Management**: View and process student-uploaded invoices
- **Digital Stamping**: Apply official stamps with timestamps and signatures
- **Role-Based Access**: Separate permissions for School Fees and IGR admins
- **Signature Management**: Create and manage digital signatures via HTML5 canvas
- **Stamp Management**: Upload and manage official stamp images with background removal
- **Bulk Processing**: Efficient workflow for handling multiple documents

---

## 💡 Benefits
- **Paperless Processing**: Eliminates physical queues and manual paperwork
- **Automated Workflow**: Reduces administrative workload with digital stamping
- **Real-time Updates**: Instant notifications and status tracking
- **Secure Authentication**: Role-based access with OTP verification
- **Document Integrity**: Digital signatures ensure authenticity
- **24/7 Accessibility**: Students can access documents anytime, anywhere

---

## 🛠️ Tech Stack

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Backend Framework** | Laravel 12 | Core application logic and API |
| **Frontend** | Livewire/Volt | Interactive UI components |
| **Database** | MySQL/SQLite | Data persistence and relationships |
| **Authentication** | Laravel Breeze + Spatie Permissions | User management and role-based access |
| **PDF Processing** | FPDI + Intervention Image | Document stamping and manipulation |
| **Email System** | Laravel Mail/SMTP | Notifications and document delivery |
| **File Storage** | Laravel Storage | Secure file management |
| **UI Framework** | Tailwind CSS + Flux UI | Modern, responsive design |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/SQLite database
- Imagick extension (for PDF processing)

### 1. Clone the Repository
```bash
git clone https://github.com/hyconcodes/newstampit.git
cd stampit
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=stampit
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database with permissions and roles
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

### 6. Build Assets & Serve
```bash
# Development mode
npm run dev

# Production build
npm run build

# Serve the application
php artisan serve
```

### 7. Default Access
- **Super Admin**: Create via `php artisan tinker` or registration
- **Admin Panels**: `/admin/dashboard`
- **Student Portal**: `/student/dashboard`

## 🏗️ System Architecture

### User Roles & Permissions
- **Super Admin**: Full system access and user management
- **School Fees Admin**: Process school fee invoices
- **IGR Admin**: Process IGR payment invoices  
- **Students**: Upload invoices and download stamped receipts

### Core Models
- **User**: Authentication and role management
- **Invoice**: Document submissions with status tracking
- **School**: Institution management and user relationships
- **Stamp**: Official stamp images for different fee types

### Key Features Implementation
- **Digital Signatures**: HTML5 canvas-based signature creation
- **PDF Stamping**: FPDI-based document processing with composite stamps
- **Email System**: Automated notifications with document attachments
- **File Management**: Secure storage with download links
- **OTP Verification**: Enhanced security for student accounts

---

## 🔧 Configuration

### Email Setup
Configure SMTP settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stampit.com
MAIL_FROM_NAME="Stampit System"
```

### File Storage
Ensure proper storage permissions:
```bash
# Create required directories
mkdir -p storage/app/public/{invoices,stamps,signatures}
mkdir -p storage/app/temp

# Set permissions
chmod -R 775 storage/
```

---

## 📋 Usage Guide

### For Administrators
1. **Setup Stamps**: Upload official stamp images via `/admin/stamps`
2. **Create Signature**: Draw digital signature via `/admin/signature`
3. **Process Documents**: Review and stamp invoices via admin dashboard
4. **Manage Users**: Create and assign roles to staff and students

### For Students
1. **Register Account**: Complete registration with OTP verification
2. **Upload Invoice**: Submit school fees or IGR payment receipts
3. **Track Status**: Monitor processing status in dashboard
4. **Download Receipt**: Access stamped documents via email or dashboard

---

## 🐛 Troubleshooting

### Common Issues
- **PDF Encryption Error**: Upload unencrypted PDF files
- **Missing Stamp Error**: Admin must upload stamp image first
- **No Signature Error**: Admin must create digital signature
- **Email Not Sending**: Check SMTP configuration
- **File Upload Failed**: Verify storage permissions

### System Requirements
- **PHP Extensions**: GD, Imagick, PDO, OpenSSL
- **Memory Limit**: Minimum 256MB for PDF processing
- **File Upload**: Max 10MB for invoice files

---

## 📬 Contact

For questions or feedback, contact:

* 👤 **Hycon Codes**
* 📧 [hyconcodes@gmail.com](mailto:hyconcodes@gmail.com)
* 🌐 **GitHub**: [hyconcodes](https://github.com/hyconcodes)

---

## 📄 License

This project is for academic purposes and is open for learning, testing, and educational use. Built as a final year Computer Science project demonstrating modern web development practices and digital document processing.

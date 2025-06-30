# 🧾 Stampit — Digital Fee Verification System

Stampit is a final year Computer Science project designed to digitize and automate the process of verifying and stamping school fee or IGR payment receipts. It features a **mobile app** for students and a **desktop/web app** for bursary officers, providing a smooth, paperless experience that reduces queues and manual processing.

---

## 🎯 Key Features

### 📱 Student Mobile App
- Upload school fees or IGR payment invoices.
- Check the status of submitted documents.
- Receive stamped receipts in the dashboard.
- Get email notifications when documents are stamped.

### 🖥️ Bursary Officer Desktop/Web App
- View and verify student-uploaded invoices.
- Digitally stamp verified receipts.
- Automatically resend stamped receipts to the student dashboard and email.
- Track document history for recordkeeping.

---

## 💡 Benefits
- Eliminates physical queues and paperwork.
- Reduces workload for bursary staff.
- Improves communication and transparency.
- Enables students to access their documents anytime.

---

## 🛠️ Tech Stack

| Layer        | Technology                |
|--------------|---------------------------|
| Frontend     | React Native *(Mobile)*   |
| Backend      | Laravel                   |
| Database     | MySQL / SQLite            |
| PDF Handling | DomPDF / TCPDF            |
| Email System | Laravel Mail / SMTP       |

---

## 🚀 Getting Started

### 1. Clone the Repository
```bash
git clone https://github.com/hyconcodes/newstampit.git
cd newstampit
````

### 2. Install Backend Dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 3. Build Frontend Assets (if using Laravel + Vite)

```bash
npm install
npm run build
```

> If in development mode, use:

```bash
npm run dev
```

### 4. Serve the Laravel App

```bash
composer run dev
```

---

## 📬 Contact

For questions or feedback, contact:

* 👤 **Hycon Codes**
* 📧 [hyconcodes@gmail.com](mailto:hyconcodes@gmail.com)

---

## 📄 License

This project is for academic purposes and is open for learning, testing, and educational use.

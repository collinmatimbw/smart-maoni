# Smart Feedback & Reward System

A full-stack customer feedback management system with promo code rewards, QR code management, analytics, and an admin dashboard.

## Features

- Submit feedback with star ratings and categories
- Automatic promo code generation on feedback submission
- Admin dashboard with real-time stats and charts
- Sentiment analysis (Positive / Neutral / Negative)
- QR code management per branch
- Branch performance analytics
- Multi-branch support

## Tech Stack

- **Frontend:** HTML, Tailwind CSS, Chart.js
- **Backend:** PHP
- **Database:** MySQL (via XAMPP / phpMyAdmin)
- **Libraries:** PHPMailer (for OTP emails)

## Requirements

- XAMPP (Apache + PHP + MySQL)
- Web browser

## Setup

### 1. Install XAMPP

Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/).

Start the **Apache** and **MySQL** services from the XAMPP Control Panel.

### 2. Clone or copy the project

Clone the repository into XAMPP's `htdocs` folder:

```bash
cd C:\xampp\htdocs
git clone https://github.com/collinmatimbw/smart-maoni.git
```

Or manually copy the `smart_feedback` folder into `C:\xampp\htdocs\`.

### 3. Import the database

1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click the **Import** tab
3. Click **Choose File** and select `smart_feedback_db.sql` (located in the project root)
4. Click **Go**

The database `smart_feedback_db` will be created automatically with all tables and sample data.

### 4. Access the app

Open your browser and go to:

```
http://localhost/smart_feedback/
```

### 5. (Optional) PHPMailer for OTP

If you want OTP email functionality to work:

```bash
cd C:\xampp\htdocs\smart_feedback
composer install
```

Then update SMTP credentials in `otp/otp_funct.php`.

## Project Structure

```
smart_feedback/
├── api/                  # Backend API endpoints
│   ├── admin_login.php
│   ├── get_feedbacks.php
│   ├── get_stats.php
│   ├── manage_qr.php
│   ├── save_feedback.php
│   └── save_reward.php
├── conn.php              # Database connection
├── index.php             # Main application (SPA)
├── setup.php             # Setup helper (delete after use)
├── smart_feedback_db.sql # Database dump for import
├── otp/                  # OTP functions
│   ├── generate_otp.php
│   └── otp_funct.php
└── tailwind.config.js    # Tailwind CSS config
```

## API Endpoints

| Endpoint | Method | Description |
|---|---|---|
| `api/save_feedback.php` | POST | Submit new feedback |
| `api/get_feedbacks.php` | GET | Retrieve all feedbacks |
| `api/get_stats.php` | GET | Get dashboard statistics |
| `api/admin_login.php` | POST | Admin login |
| `api/manage_qr.php` | GET/POST/PUT | QR code management |
| `api/save_reward.php` | POST | Update promo code status |

## Default Admin Login

The default admin credentials are stored in the SQL file comments. Import the database to find them.

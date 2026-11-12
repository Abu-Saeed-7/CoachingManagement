# Coaching Management System

**Course:** Database Management Systems (DBMS)  
**Group ID:** Group-13  
**Authors / Contributors:**  
* **Zarif Tahmid** (Student ID: 240221) — Database Architect & Backend Controller Engineer  
* **Abu Saeed** (Student ID: 240222) — Relational Schema Analyst & Full-Stack UI Developer  

---

## 1. Project Overview
The **Coaching Management System** is a centralized, transaction-safe web application designed for academic coaching institutes. It replaces fragmented paper registers and disconnected spreadsheets with a 3NF-normalized relational database engine and role-based portals for **Administrators**, **Teachers**, and **Students**.

### Key Modules & Capabilities:
* **Role-Based Authentication (RBAC):** Dedicated dashboards and permission boundaries for Admins, Faculty, and Students.
* **Unified Student Admission:** Single-transaction enrollment creating user authentication records, demographic profiles, and batch allocations atomically.
* **Faculty & Batch Allocations:** Associative mapping supporting multi-subject teaching assignments.
* **Attendance Tracking:** Daily batch attendance logging protected by composite unique constraints.
* **Exams & Evaluation:** Numeric score recording with automatic letter grade computation.
* **Tuition Fee Ledger:** Real-time billing, overdue alerts, and instant payment reconciliation.
* **Analytical Reporting:** Multi-table joined summaries for administrative executive decision-making.

---

## 2. Technology Stack
* **Frontend:** HTML5, Modular CSS3 Grid/Flexbox, Vanilla JavaScript (ES6+)
* **Backend:** PHP 8.x (MVC architecture with OOP Controllers & Auth Middleware)
* **Database Engine:** MySQL / MariaDB (InnoDB Storage Engine with foreign key cascades & ACID transaction support)
* **Server Environment:** Apache (via XAMPP) or Docker (PHP 8.2-Apache)

---

## 3. Database Setup & Installation

### Option A: Standard Setup (XAMPP / phpMyAdmin)
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open your browser and navigate to **phpMyAdmin**:  
   `http://localhost/phpmyadmin/`
3. Create a new database named:
   ```sql
   CREATE DATABASE coaching_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
4. Select `coaching_management` and navigate to the **Import** tab:
   * First, import **`database/schema.sql`** (Creates all 10 tables, indexes, and foreign keys).
   * Second, import **`database/sample_data.sql`** (Populates verified test instances in valid foreign key dependency order).

### Option B: Command Line (MySQL CLI)
```bash
# Log in to MySQL and create database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS coaching_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import DDL schema
mysql -u root -p coaching_management < database/schema.sql

# Import initial sample data
mysql -u root -p coaching_management < database/sample_data.sql
```

---

## 4. Environment Variables Configuration

The application automatically loads default local XAMPP credentials (`localhost`, `root`, blank password).  
To customize connection parameters or deploy to cloud hosts (Railway, Render, TiDB, etc.):

1. In the project root, copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Configure your database credentials:
   ```ini
   # Option A: Single Connection URL (Cloud PaaS)
   # DATABASE_URL=mysql://user:password@host:3306/coaching_management

   # Option B: Individual Variables
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=coaching_management
   DB_USER=root
   DB_PASSWORD=

   # SSL Mode (Set to true for cloud hosts like TiDB Cloud / Aiven)
   DB_SSL=false

   # Application Environment ('development' or 'production')
   APP_ENV=development
   ```

---

## 5. Running the Application

### Via XAMPP (Localhost):
1. Place the `CoachingManagement` directory inside your web root:
   * Windows: `C:/xampp/htdocs/CoachingManagement`
   * Linux: `/opt/lampp/htdocs/CoachingManagement`
2. Open your browser and navigate to:
   ```
   http://localhost/CoachingManagement/
   ```

### Via PHP Built-in Server (Alternative):
```bash
cd /path/to/CoachingManagement
php -S localhost:8000
```
Then visit: `http://localhost:8000/`

---

## 6. Pre-Configured Test Credentials

The database comes pre-seeded with verified test accounts across all roles:

| Role | Email | Password | Primary Access / Capabilities |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `admin123` | Full governance, admissions, financial ledgers, KPI reports |
| **Teacher** | `rahim@gmail.com` | `teacher123` | Batch rosters, daily attendance logging, exam marks evaluation |
| **Teacher** | `karim@gmail.com` | `teacher123` | Assigned batches & result submissions |
| **Student** | `rifat@gmail.com` | `student123` | Class routines, evaluated results, personal tuition fee ledger |
| **Student** | `sakib@gmail.com` | `student123` | Student dashboard & attendance scorecard |

---

## 7. Submission Package Folder Structure

As per assignment submission requirements:
```
Group13_CoachingManagement_FinalDB.zip
├── Report_Group13_CoachingManagement.pdf   # Printed/Softcopy report with QR & YouTube link
├── database/
│   ├── schema.sql                         # Full DDL script (tables, keys, constraints, indexes)
│   └── sample_data.sql                    # Initial seed records ordered without FK conflicts
└── source_code/
    ├── backend/                           # PHP controllers, PDO connection, auth middleware
    ├── frontend/                          # Admin, Teacher, Student dashboards and styles
    ├── database/                          # Schemas, ERD diagrams
    ├── index.php                          # Application landing & portal router
    ├── landing.css                        # Responsive landing page styling
    └── README.md                          # Configuration and local execution guide
```


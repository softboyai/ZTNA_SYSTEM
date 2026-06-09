# Mount Kigali University - Zero-Trust Network Access (ZTNA) System

**Student:** INGABIRE GISELE  
**Student ID:** BBICTR/2024/36790  
**University:** Mount Kigali University, Kigali, Rwanda  
**Year:** 2026

---

## What Is This Project?

This is a **Zero-Trust Network Access (ZTNA) System** — a web-based security system for Mount Kigali University that controls and monitors who can access university resources.

**Zero-Trust means:** "Never trust, always verify." Every time someone tries to access a resource, the system checks their identity, role, device, and IP address before allowing access.

---

## Features

- Homepage with full university information
- User registration with strict validation (real names, real emails, strong passwords)
- Login system with bcrypt password encryption
- 4 user roles: Admin, Student, Lecturer, Staff
- Role-based access control (each role can ONLY access their resources)
- Network segmentation (Academic, Administrative, Research, General)
- Configurable security policies
- Access logging (tracks every attempt with IP, device, timestamp)
- Resource access monitoring (logs every page visit per user)
- ZTNA verification banner on every page (shows role, IP, segment, time)
- My Security page (users can see their own access history)
- Admin panel (manage users, logs, policies, segments, reports)
- PDF report generation (real PDF download)
- Account suspension (block compromised accounts instantly)
- Responsive design (works on phones and computers)

---

## Login Credentials (Default Users)

| Role | Username | Password | What They Can Do |
|------|----------|----------|------------------|
| Admin | admin | Admin@1234 | Manage users, view logs, policies, segments, reports, PDF |
| Student | student1 | Student@1234 | Learning platform, grades, library, email, my security |
| Lecturer | lecturer1 | Lecturer@1234 | Courses, student records, online learning, research, my security |
| Staff | staff1 | Staff@1234 | Admin services, finance, HR, communication, my security |

---

## How To Install On Another Computer (Step-by-Step)

### Step 1: Download and Install XAMPP

1. Go to: https://www.apachefriends.org/download.html
2. Download XAMPP for Windows (it's free)
3. Run the installer — click **Next** through everything
4. Install it to `C:\xampp` (default location)

### Step 2: Start Apache and MySQL

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** (should turn green)
3. Click **Start** next to **MySQL** (should turn green)

### Step 3: Copy the Project Folder

1. Copy the entire `ztna_system` folder
2. Paste it into: `C:\xampp\htdocs\`
3. Final path should be: `C:\xampp\htdocs\ztna_system\`

### Step 4: Create the Database

1. Open browser → go to: `http://localhost/phpmyadmin`
2. Click **Import** tab at the top
3. Click **Choose File** → select: `C:\xampp\htdocs\ztna_system\database\ztna_system.sql`
4. Click **Go** (or **Import**)
5. You should see: "Import has been successfully finished"

### Step 5: Open the System

1. Open browser → go to: `http://localhost/ztna_system/`
2. You'll see the homepage!
3. Click **Login** and use credentials from the table above

---

## IMPORTANT: If You Get "Table doesn't exist" Error

If you already imported the database before and get an error about `resource_access` table:

1. Go to `http://localhost/phpmyadmin`
2. Click on `ztna_system` database
3. Click the **SQL** tab
4. Paste this and click **Go**:

```sql
CREATE TABLE IF NOT EXISTS resource_access (
    access_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    resource_name VARCHAR(255) NOT NULL,
    segment_name VARCHAR(100),
    access_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    access_result ENUM('allowed', 'blocked') DEFAULT 'allowed',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

Or simply **drop the old database** and re-import the full SQL file.

---

## Project File Structure

```
ztna_system/
├── index.php                  ← Homepage (public landing page)
├── login.php                  ← Login page
├── register.php               ← User registration
├── logout.php                 ← Logout (destroys session)
├── my_profile.php             ← My Security page (all users)
├── db_connect.php             ← Database connection
├── dashboard_admin.php        ← Admin dashboard
├── dashboard_student.php      ← Student dashboard
├── dashboard_lecturer.php     ← Lecturer dashboard
├── dashboard_staff.php        ← Staff dashboard
├── admin/
│   ├── manage_users.php       ← View/add/edit/suspend/delete users
│   ├── add_user.php           ← Add new user form
│   ├── edit_user.php          ← Edit user details
│   ├── access_logs.php        ← View all login logs (filter by date/status)
│   ├── security_policies.php  ← Manage security policies (CRUD)
│   ├── network_segments.php   ← Manage network segments (CRUD)
│   ├── reports.php            ← System reports & analytics
│   ├── generate_report.php    ← View report in browser
│   └── download_report.php    ← Download real PDF report
├── student/
│   ├── learning_platform.php  ← Courses, progress, assignments
│   ├── academic_records.php   ← GPA, grades, transcript
│   ├── library.php            ← E-books, journals, borrowed books
│   └── email_comm.php         ← Inbox, messages
├── lecturer/
│   ├── course_management.php  ← Active courses, materials, stats
│   ├── student_records.php    ← Student grades by course
│   ├── online_learning.php    ← Virtual classes, scheduling
│   └── research_portal.php    ← Publications, research resources
├── staff/
│   ├── admin_services.php     ← Requests, document processing
│   ├── financial_mgmt.php     ← Budget, transactions (RWF)
│   ├── hr_portal.php          ← Employees, leave management
│   └── communication.php      ← Announcements, messaging
├── css/
│   └── style.css              ← All styling (navy blue #003366)
├── js/
│   └── main.js               ← JavaScript (sidebar, validation)
├── images/
│   └── MKUR-logo.png         ← University logo
├── lib/
│   └── fpdf.php              ← PDF library (for report download)
├── database/
│   └── ztna_system.sql       ← Database file (import this)
├── README.md                  ← This file
├── PRESENTATION_GUIDE.md      ← How to present & customize
└── PROJECT_DOCUMENTATION.md   ← Full project documentation
```

---

## Technologies Used

| Technology | Purpose |
|-----------|---------|
| PHP | Server-side logic, authentication, access control |
| MySQL | Database (users, logs, policies, segments, resource_access) |
| HTML | Page structure |
| CSS | Design (pure CSS, no frameworks) |
| JavaScript | Real-time form validation, UI interactions |
| XAMPP | Local server (Apache + MySQL + PHP) |
| FPDF | PDF report generation |

---

## Security Features

1. **Password Hashing (bcrypt)** — Passwords encrypted, never stored as plain text
2. **Prepared Statements** — Prevents SQL injection attacks
3. **Session Management** — Every page verifies login + role
4. **Role-Based Access** — Students can't access admin pages (and vice versa)
5. **IP & Device Logging** — Every login records IP and browser info
6. **Resource Access Logging** — Every page visit is tracked per user
7. **Account Suspension** — Admin can instantly block any account
8. **Strong Password Enforcement** — Min 8 chars, uppercase, lowercase, number, special char
9. **Network Segmentation** — Resources divided by role-based zones
10. **ZTNA Verification Banner** — Every page shows verification status in real-time

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Connection failed" error | Start MySQL in XAMPP Control Panel |
| "Table doesn't exist" error | Run the CREATE TABLE SQL above in phpMyAdmin |
| Can't login with default passwords | Re-import `ztna_system.sql` in phpMyAdmin |
| Page is blank | Start Apache in XAMPP Control Panel |
| Redirect to login page | You're accessing a page that requires a different role |

---

© 2026 Mount Kigali University | ZTNA System | Developed by INGABIRE GISELE | BBICTR/2024/36790

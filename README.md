# Mount Kigali University - Zero-Trust Network Access (ZTNA) System

**Student:** INGABIRE GISELE  
**Student ID:** BBICTR/2024/36790  
**University:** Mount Kigali University, Rwanda  
**Motto:** Empowering Generations Through Education

---

## What Is This Project?

This is a **Zero-Trust Network Access (ZTNA) System** — a web-based security system for Mount Kigali University that controls who can access university resources (like learning platforms, academic records, etc.).

**Zero-Trust means:** "Never trust, always verify." Every time someone logs in, the system checks their identity, device, and IP address before giving access.

---

## Features

- Login system with strong password security (bcrypt encryption)
- 4 user roles: Admin, Student, Lecturer, Staff
- Self-registration for new users
- Admin panel to manage users, view logs, set policies
- Access logging (tracks every login attempt with IP & device)
- Network segmentation (controls which roles access which resources)
- Account suspension (block compromised users instantly)
- Responsive design (works on phones and computers)
- Real-time form validation (checks names, emails, passwords as you type)

---

## Login Credentials (Default Users)

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | Admin@1234 |
| Student | student1 | Student@1234 |
| Lecturer | lecturer1 | Lecturer@1234 |
| Staff | staff1 | Staff@1234 |

---

## How To Install On Another Computer (Step-by-Step)

### Step 1: Download and Install XAMPP

1. Go to: https://www.apachefriends.org/download.html
2. Download XAMPP for Windows (it's free)
3. Run the installer — click **Next** through everything
4. Install it to `C:\xampp` (default location)
5. After install, open **XAMPP Control Panel**

### Step 2: Start Apache and MySQL

1. In XAMPP Control Panel, click **Start** next to **Apache**
2. Click **Start** next to **MySQL**
3. Both should turn green — that means they're running

### Step 3: Copy the Project Folder

1. Copy the entire `ztna_system` folder
2. Paste it into: `C:\xampp\htdocs\`
3. The path should be: `C:\xampp\htdocs\ztna_system\`

### Step 4: Create the Database

1. Open your web browser (Chrome, Firefox, etc.)
2. Go to: `http://localhost/phpmyadmin`
3. Click **Import** tab at the top
4. Click **Choose File**
5. Navigate to: `C:\xampp\htdocs\ztna_system\database\ztna_system.sql`
6. Select that file and click **Open**
7. Scroll down and click **Go** (or **Import**)
8. You should see a success message: "Import has been successfully finished"

### Step 5: Open the System

1. Open your browser
2. Go to: `http://localhost/ztna_system/`
3. You'll see the homepage!
4. Click **Login** and use the credentials from the table above

---

## That's It! You're Done! 🎉

---

## Project File Structure

```
ztna_system/
├── index.php              ← Homepage (landing page)
├── login.php              ← Login page
├── register.php           ← Registration page
├── logout.php             ← Logout handler
├── db_connect.php         ← Database connection
├── dashboard_admin.php    ← Admin dashboard
├── dashboard_student.php  ← Student dashboard
├── dashboard_lecturer.php ← Lecturer dashboard
├── dashboard_staff.php    ← Staff dashboard
├── admin/
│   ├── manage_users.php   ← View/edit/delete users
│   ├── add_user.php       ← Add new user
│   ├── edit_user.php      ← Edit user details
│   ├── access_logs.php    ← View login logs
│   ├── security_policies.php ← Manage policies
│   ├── network_segments.php  ← Manage network zones
│   └── reports.php        ← System reports
├── css/
│   └── style.css          ← All styling
├── js/
│   └── main.js            ← JavaScript functions
├── images/
│   └── MKUR-logo.png      ← University logo
└── database/
    └── ztna_system.sql    ← Database file (import this)
```

---

## Technologies Used

| Technology | Purpose |
|-----------|---------|
| PHP | Server-side programming |
| MySQL | Database (stores users, logs, policies) |
| HTML | Page structure |
| CSS | Styling and design |
| JavaScript | Form validation and interactivity |
| XAMPP | Local server (Apache + MySQL + PHP) |

---

## Troubleshooting

**Problem:** Page shows "Connection failed" error  
**Solution:** Make sure MySQL is running in XAMPP Control Panel, and you imported the database file.

**Problem:** Can't login with default passwords  
**Solution:** Re-import the `ztna_system.sql` file in phpMyAdmin. Make sure you selected the correct file.

**Problem:** Page is blank or shows error  
**Solution:** Make sure Apache is running in XAMPP Control Panel.

**Problem:** "Access denied" or redirect to login  
**Solution:** You're trying to access a page that requires a different role. Login with the correct account.

---

## Security Features Explained

1. **Password Hashing (bcrypt)** — Passwords are never stored as plain text. They're encrypted.
2. **Prepared Statements** — Prevents SQL injection attacks.
3. **Session Management** — Each page verifies you're logged in before showing content.
4. **Role-Based Access** — Students can't access admin pages, etc.
5. **IP & Device Logging** — Every login attempt records the IP address and browser info.
6. **Account Suspension** — Admin can block suspicious accounts immediately.

---

© 2026 Mount Kigali University | ZTNA System | Developed by INGABIRE GISELE

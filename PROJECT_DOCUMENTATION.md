# Zero-Trust Network Access (ZTNA) Architecture for Mount Kigali University

**Student:** INGABIRE GISELE  
**Student ID:** BBICTR/2024/36790  
**University:** Mount Kigali University, Kigali, Rwanda

---

## 1.3.1 General Objective

**The main objective of this project is to design and implement a Zero-Trust Network Access (ZTNA) Architecture for Mount Kigali University.**

### How This System Achieves the General Objective:

This system IS the Zero-Trust Network Access Architecture for Mount Kigali University. It is a fully functional web-based system that:

- **NEVER trusts any user or device by default** — even if you are connected to the university network, you CANNOT access anything without proving who you are
- **ALWAYS verifies** — every single page, every single click, every single action checks: "Is this person logged in? Do they have the right role? Are they allowed here?"
- **Controls ALL access** — students can only access student resources, lecturers can only access lecturer resources, staff can only access staff resources
- **Monitors EVERYTHING** — every login attempt (success or failure) is recorded with the user's IP address, device information, and timestamp

The system runs at: `http://localhost/ztna_system/`

---

## 1.3.2 Specific Objectives

---

## OBJECTIVE i: To design a secure Zero-Trust Network Architecture that controls and monitors access to university network resources.

---

### What This Objective Means:
Design a system where access to university resources is CONTROLLED (only authorized people get in) and MONITORED (we can see who accessed what, when, and from where).

### HOW OUR SYSTEM DOES THIS:

#### A) CONTROLLING ACCESS — Nobody gets in without verification

When you open the system (`http://localhost/ztna_system/`), here is what happens:

```
User opens website
        ↓
Homepage shows (public - anyone can see)
        ↓
User clicks "Login" or any resource link
        ↓
MUST enter username + password
        ↓
System checks: Does this user exist? Is password correct? Is account active?
        ↓
IF YES → Send user ONLY to their role's dashboard
IF NO → Block access, show error, LOG the failed attempt
```

**You can see this working in:**
- Open `http://localhost/ztna_system/dashboard_admin.php` directly WITHOUT logging in
- Result: You are BLOCKED and sent to the login page
- This proves: The system controls access — you cannot bypass it

#### B) MONITORING ACCESS — Every action is recorded

Every time ANYONE tries to log in (even if they fail), the system records:

| What We Record | How We Get It | Why |
|---|---|---|
| **Who** tried to log in | Username entered | To identify the person |
| **When** they tried | Server timestamp | To know exact time |
| **From where** (IP address) | `$_SERVER['REMOTE_ADDR']` | To track location/network |
| **Using what device** | `$_SERVER['HTTP_USER_AGENT']` | To detect unusual devices |
| **Result** (granted/denied) | Login success or failure | To detect attacks |
| **What they accessed** | Page/resource name | To see what they were trying to reach |

**You can see this working in:**
- Login as admin (admin / Admin@1234)
- Go to **Access Logs** in the sidebar
- You will see EVERY login attempt with all the details above
- You can filter by date and by status (granted/denied)

#### C) THE ARCHITECTURE DESIGN:

```
┌─────────────────────────────────────────────────────────────┐
│                    MOUNT KIGALI UNIVERSITY                    │
│                    ZTNA ARCHITECTURE                          │
└──────────────────────────────┬──────────────────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │                     │
                    │   AUTHENTICATION    │
                    │   GATEWAY           │
                    │   (login.php)       │
                    │                     │
                    │   Checks:           │
                    │   ✓ Username        │
                    │   ✓ Password (hash) │
                    │   ✓ Account status  │
                    │   ✓ Records IP      │
                    │   ✓ Records device  │
                    │                     │
                    └──────────┬──────────┘
                               │
            ┌──────────────────┼──────────────────┐
            │                  │                  │
   ┌────────▼────────┐ ┌──────▼──────┐ ┌────────▼────────┐
   │  ADMIN SEGMENT  │ │  ACADEMIC   │ │  ADMIN/STAFF    │
   │                 │ │  SEGMENT    │ │  SEGMENT        │
   │ • User Mgmt    │ │             │ │                 │
   │ • Access Logs  │ │ • Students  │ │ • Finance       │
   │ • Policies     │ │ • Lecturers │ │ • HR            │
   │ • Segments     │ │ • Learning  │ │ • Communication │
   │ • Reports      │ │ • Research  │ │ • Documents     │
   │ • PDF Export   │ │ • Library   │ │                 │
   └────────┬────────┘ └──────┬──────┘ └────────┬────────┘
            │                  │                  │
            └──────────────────┼──────────────────┘
                               │
                    ┌──────────▼──────────┐
                    │                     │
                    │   MONITORING &      │
                    │   LOGGING ENGINE    │
                    │   (access_logs)     │
                    │                     │
                    │   Records ALL:      │
                    │   • Login attempts  │
                    │   • Logouts         │
                    │   • IP addresses    │
                    │   • Device info     │
                    │   • Success/Failure │
                    │   • Timestamps      │
                    │                     │
                    └─────────────────────┘
```

#### WHERE TO SEE THIS IN THE SYSTEM:

| Feature | Where in the system | What it shows |
|---|---|---|
| Access control | Try to open any dashboard without login | You get blocked → CONTROL works |
| Monitoring | Admin → Access Logs | Full list of every access attempt |
| Architecture | Admin → Network Segments | See all network zones defined |
| PDF Report | Admin → Reports → Download PDF | Complete system report |

---

## OBJECTIVE ii: To develop an authentication and access control system that verifies users and devices before granting access to the university network.

---

### What This Objective Means:
Build a login system that checks WHO you are and WHAT DEVICE you're using before letting you in.

### HOW OUR SYSTEM DOES THIS:

#### A) AUTHENTICATION (Verifying WHO you are)

**Step 1 — User Registration:**
- Go to `http://localhost/ztna_system/register.php`
- System requires:
  - **Real name** (letters only — no numbers allowed)
  - **Username** (letters only — this is your login name)
  - **Valid email** (must be real format like name@mku.ac.rw)
  - **Strong password** — MUST have:
    - Minimum 8 characters
    - At least 1 UPPERCASE letter (A-Z)
    - At least 1 lowercase letter (a-z)
    - At least 1 number (0-9)
    - At least 1 special character (!@#$%^&*)
  - **Role** (Student, Lecturer, or Staff)
- Password is ENCRYPTED using bcrypt before storing (never stored as plain text)

**Step 2 — User Login:**
- Go to `http://localhost/ztna_system/login.php`
- Enter username and password
- System verifies using `password_verify()` — compares what you typed against the encrypted hash
- If the password matches AND account is active → ACCESS GRANTED
- If password is wrong → ACCESS DENIED + logged
- If account is suspended → BLOCKED with message "Account suspended. Contact the network administrator."

**Step 3 — Session Creation:**
- After successful login, a session is created containing:
  - `$_SESSION['user_id']` — your unique ID
  - `$_SESSION['username']` — your name
  - `$_SESSION['user_role']` — your role (admin/student/lecturer/staff)
- This session is checked on EVERY page you visit

#### B) DEVICE VERIFICATION (Verifying WHAT DEVICE you're using)

Every time someone logs in, the system captures:

```php
$ip_address = $_SERVER['REMOTE_ADDR'];        // e.g., "192.168.1.105"
$device_info = $_SERVER['HTTP_USER_AGENT'];    // e.g., "Mozilla/5.0 (Windows NT 10.0; Win64) Chrome/120.0"
```

This tells the admin:
- **IP Address** — which network/computer the person is connecting from
- **Device/Browser** — what operating system and browser they are using

**Example of what the admin sees in Access Logs:**

| User | IP Address | Device | Status |
|---|---|---|---|
| student1 | 127.0.0.1 | Mozilla/5.0 (Windows NT 10.0) Chrome/120.0 | Granted |
| unknown | 192.168.1.200 | Mozilla/5.0 (Linux) Firefox/115.0 | Denied |

If someone's device info suddenly changes (different browser, different OS), the admin can see this and investigate — because in Zero-Trust, we don't just trust that it's still the same person.

#### C) ACCESS CONTROL (What happens AFTER login)

After login, the system enforces STRICT role-based access:

```
┌─────────────┐     ┌───────────────────────────────────────┐
│   ADMIN     │────▶│ Can access: User management, logs,    │
│             │     │ policies, segments, reports, PDF       │
│             │     │ CANNOT access: Student/Lecturer/Staff  │
│             │     │ dashboards                             │
└─────────────┘     └───────────────────────────────────────┘

┌─────────────┐     ┌───────────────────────────────────────┐
│   STUDENT   │────▶│ Can access: Learning platform, grades,│
│             │     │ library, email                         │
│             │     │ CANNOT access: Admin panel, Staff      │
│             │     │ finance, Lecturer courses              │
└─────────────┘     └───────────────────────────────────────┘

┌─────────────┐     ┌───────────────────────────────────────┐
│  LECTURER   │────▶│ Can access: Course management, student│
│             │     │ records, online learning, research     │
│             │     │ CANNOT access: Admin panel, Staff HR,  │
│             │     │ Student grades view                    │
└─────────────┘     └───────────────────────────────────────┘

┌─────────────┐     ┌───────────────────────────────────────┐
│    STAFF    │────▶│ Can access: Admin services, finance,  │
│             │     │ HR portal, communication               │
│             │     │ CANNOT access: Admin panel, Student    │
│             │     │ or Lecturer resources                  │
└─────────────┘     └───────────────────────────────────────┘
```

**HOW TO PROVE THIS WORKS:**
1. Login as `student1` (Student@1234)
2. Try to open `http://localhost/ztna_system/dashboard_admin.php` in browser
3. Result: You are IMMEDIATELY sent back to login — ACCESS DENIED
4. This proves: Even a logged-in user cannot access resources outside their role

#### WHERE TO SEE THIS IN THE SYSTEM:

| Feature | How to test it | What happens |
|---|---|---|
| Strong password | Try registering with "123" as password | Rejected — too weak |
| Real name only | Try registering with "john123" | Rejected — no numbers allowed |
| Valid email | Try registering with "notanemail" | Rejected — must be real email |
| Login verification | Enter wrong password | Denied + recorded in logs |
| Device capture | Login then check Access Logs | You'll see your browser/IP |
| Role blocking | Login as student, try admin URL | Blocked immediately |
| Account suspension | Admin suspends a user, user tries to login | Blocked with message |

---

## OBJECTIVE iii: To implement network segmentation and security policies that help protect sensitive university data from internal and external cyber threats.

---

### What This Objective Means:
Divide the network into separate zones (segments) and create rules (policies) that protect university data from hackers (external threats) AND from unauthorized users inside the network (internal threats).

### HOW OUR SYSTEM DOES THIS:

#### A) NETWORK SEGMENTATION (Dividing the network into zones)

The system divides university resources into separate network segments:

| Segment Name | Who Can Access | What's Inside | Why It's Separated |
|---|---|---|---|
| **Academic Network** | Students, Lecturers | Learning platforms, course materials, academic databases | Students shouldn't see admin data |
| **Administrative Network** | Admin, Staff | HR systems, financial management, admin tools | Sensitive employee/financial data |
| **Research Network** | Lecturers only | Research portals, journals, collaboration tools | Proprietary research data |
| **General Network** | Everyone | Basic internet, email, communication | Low-sensitivity resources |

**How to see this in the system:**
1. Login as admin (admin / Admin@1234)
2. Click **Network Segments** in the sidebar
3. You'll see all segments listed with their allowed roles
4. You can ADD new segments, EDIT existing ones, or DELETE them
5. Each segment shows exactly which roles (admin, student, lecturer, staff) are allowed

**How segmentation is enforced in code:**

Each user role has its own folder of resources:
- `/student/` — only accessible if `$_SESSION['user_role'] === 'student'`
- `/lecturer/` — only accessible if `$_SESSION['user_role'] === 'lecturer'`
- `/staff/` — only accessible if `$_SESSION['user_role'] === 'staff'`
- `/admin/` — only accessible if `$_SESSION['user_role'] === 'admin'`

A student PHYSICALLY CANNOT open a staff page. The code blocks it:

```php
// At the top of every staff page:
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: ../login.php");  // BLOCKED - sent back to login
    exit();
}
```

#### B) SECURITY POLICIES (Rules that protect the system)

The admin can create and manage security policies that define how the system behaves:

| Policy Name | What It Does | Status |
|---|---|---|
| Multi-Factor Authentication | Requires additional verification for sensitive resources | Active |
| Device Compliance Check | Only devices meeting security standards (updated OS, antivirus) are allowed | Active |
| Session Timeout Policy | User sessions expire after 30 minutes of inactivity | Active |
| IP Restriction Policy | Access restricted to university-approved IP ranges for critical systems | Active |

**How to see this in the system:**
1. Login as admin
2. Click **Security Policies** in the sidebar
3. You'll see all policies with their descriptions and status (Active/Inactive)
4. You can ADD new policies, EDIT them, or DELETE them
5. Each policy has an ON/OFF toggle (is_active)

#### C) PROTECTION AGAINST EXTERNAL THREATS (Hackers from outside)

| External Threat | How Our System Blocks It |
|---|---|
| **Brute force attack** (guessing passwords) | Failed attempts are logged with IP. Admin sees repeated failures from same IP. |
| **SQL injection** (hacking the database) | ALL queries use prepared statements. User input is NEVER put directly into SQL. |
| **Unauthorized access** (accessing pages without login) | Every page checks session. No session = no access. |
| **Password theft** (stealing stored passwords) | Passwords are bcrypt hashed. Even if database is stolen, passwords are unreadable. |

**How to test external threat protection:**
1. Try to login with username: `admin` password: `wrongpassword`
2. Result: Denied. Check Access Logs — the attempt is recorded with IP and device.
3. Try entering `' OR '1'='1` as username (SQL injection attack)
4. Result: Nothing happens — prepared statements treat it as plain text, not code.

#### D) PROTECTION AGAINST INTERNAL THREATS (Unauthorized users inside the network)

| Internal Threat | How Our System Blocks It |
|---|---|
| **Student tries to see other students' grades** | Each user only sees their OWN dashboard data |
| **Student tries to access admin panel** | Role check blocks them immediately |
| **Staff member tries to access financial records they shouldn't** | Role + segment check prevents access |
| **Compromised account** | Admin clicks "Suspend" → account is immediately blocked |
| **Former employee still has credentials** | Admin deletes or suspends the account → instant block |

**How to test internal threat protection:**
1. Login as `student1` (Student@1234)
2. In the browser, type: `http://localhost/ztna_system/admin/manage_users.php`
3. Result: BLOCKED — redirected to login page
4. This proves: Even a legitimate logged-in student cannot access admin resources

#### E) HOW SUSPENSION WORKS (Immediate threat response):

1. Admin notices suspicious activity in Access Logs (e.g., failed logins from unknown IP)
2. Admin goes to **User Management**
3. Admin clicks **Suspend** on the suspicious account
4. From that moment, even if the attacker has the correct password:
   - They see: "Account suspended. Contact the network administrator."
   - They CANNOT get in
   - The attempt is logged

---

## HOW TO DEMONSTRATE THE WHOLE SYSTEM WORKING

### Demo 1: Show that NO ONE is trusted by default
1. Open browser → go to `http://localhost/ztna_system/dashboard_student.php`
2. **Result:** Redirected to login page — BLOCKED
3. **This proves:** Zero-Trust = never trust, always verify

### Demo 2: Show authentication works
1. Go to login page → enter `admin` / `Admin@1234`
2. **Result:** Successfully logged in, see admin dashboard with stats
3. **This proves:** Valid credentials → access granted

### Demo 3: Show failed login is monitored
1. Logout → try logging in with `admin` / `WrongPassword`
2. **Result:** "Invalid username or password" error
3. Login with correct password → go to Access Logs
4. **Result:** You see the failed attempt with IP, device, timestamp
5. **This proves:** ALL activity is monitored, even failures

### Demo 4: Show role-based access control
1. Login as `student1` / `Student@1234`
2. You see the student dashboard with: Learning Platform, Grades, Library, Email
3. Type `http://localhost/ztna_system/dashboard_admin.php` in the URL bar
4. **Result:** Sent back to login — BLOCKED
5. **This proves:** Each role can ONLY access their own resources

### Demo 5: Show network segmentation
1. Login as admin → go to Network Segments
2. You see: Academic Network (students, lecturers), Administrative (admin, staff), Research (lecturers), General (all)
3. **This proves:** Network is divided into zones with specific role permissions

### Demo 6: Show security policies
1. Login as admin → go to Security Policies
2. You see all active policies: MFA, Device Compliance, Session Timeout, IP Restriction
3. You can add a new policy → it appears in the list
4. **This proves:** Configurable security rules that protect the system

### Demo 7: Show account suspension (threat response)
1. Login as admin → User Management → click "Suspend" on student1
2. Logout → try to login as `student1` / `Student@1234`
3. **Result:** "Account suspended. Contact the network administrator."
4. **This proves:** Admin can instantly block compromised accounts

### Demo 8: Show PDF report
1. Login as admin → Reports → click "Download PDF Report"
2. **Result:** A PDF file downloads with all system statistics
3. **This proves:** System can generate documentation for auditing

---

## SUMMARY: HOW EACH OBJECTIVE IS MET

| Objective | Achieved? | Evidence |
|---|---|---|
| **General: Design and implement ZTNA for MKU** | ✅ YES | The complete system is running at localhost/ztna_system with login, dashboards, admin panel, monitoring |
| **i. Secure architecture that controls and monitors access** | ✅ YES | Access control: session + role check on every page. Monitoring: access_logs table records every attempt with IP/device/time |
| **ii. Authentication system that verifies users and devices** | ✅ YES | bcrypt password hashing, password_verify(), IP capture ($_SERVER['REMOTE_ADDR']), device capture ($_SERVER['HTTP_USER_AGENT']), strong password validation |
| **iii. Network segmentation and security policies** | ✅ YES | 4 network segments (Academic, Administrative, Research, General) with role-based access. 4 security policies configurable by admin. Role isolation via separate folders + session checks |

---

## TECHNOLOGIES USED

| Technology | What It Does In This Project |
|---|---|
| **PHP** | Server-side authentication, session management, access control, database queries |
| **MySQL** | Stores users, access logs, security policies, network segments |
| **HTML** | Structure of all web pages |
| **CSS** | Visual design (dark navy blue #003366 theme, responsive) |
| **JavaScript** | Real-time form validation (checks name/email/password as you type) |
| **XAMPP** | Local server environment (Apache web server + MySQL database) |
| **FPDF Library** | Generates real PDF report files for download |

---

## DEFAULT LOGIN ACCOUNTS

| Role | Username | Password | What They Can Do |
|---|---|---|---|
| Admin | admin | Admin@1234 | Manage users, view logs, manage policies, manage segments, reports, PDF |
| Student | student1 | Student@1234 | Access learning platform, grades, library, email |
| Lecturer | lecturer1 | Lecturer@1234 | Manage courses, student records, online learning, research |
| Staff | staff1 | Staff@1234 | Administrative services, finance, HR, communication |

---

© 2026 Mount Kigali University | ZTNA System | Developed by INGABIRE GISELE | BBICTR/2024/36790

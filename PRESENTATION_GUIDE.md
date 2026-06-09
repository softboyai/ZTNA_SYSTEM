# 🎓 PRESENTATION GUIDE — For the Student Presenting This Project

**This guide tells you exactly what to say, what to click, and how to customize the project.**  
**No coding experience needed. Just follow the steps.**

---

## 📋 TABLE OF CONTENTS

1. [How to Change Your Name & Student ID](#1-how-to-change-your-name--student-id)
2. [How to Change the University Name](#2-how-to-change-the-university-name)
3. [How to Change Colors](#3-how-to-change-colors)
4. [How to Change the Logo](#4-how-to-change-the-logo)
5. [How to Change Contact Information](#5-how-to-change-contact-information)
6. [PRESENTATION SCRIPT — What to Say & Demo](#6-presentation-script)
7. [Common Questions the Panel Will Ask](#7-common-questions-the-panel-will-ask)
8. [Tips for Presentation Day](#8-tips-for-presentation-day)

---

## 1. How to Change Your Name & Student ID

1. Open the project folder in a text editor (VS Code, Notepad++, or even Notepad)
2. Press `Ctrl + Shift + H` (Find and Replace in all files)
3. Find: `INGABIRE GISELE` → Replace with: `YOUR NAME`
4. Click **Replace All**
5. Find: `BBICTR/2024/36790` → Replace with: `YOUR STUDENT ID`
6. Click **Replace All**
7. Save all files

---

## 2. How to Change the University Name

1. Press `Ctrl + Shift + H` (Find in all files)
2. Find: `Mount Kigali University` → Replace with: `Your University Name`
3. Click **Replace All**
4. Find: `Empowering Generations Through Education` → Replace with: `Your University Motto`
5. Save all files

---

## 3. How to Change Colors

All colors are in ONE file: `css/style.css`

1. Open `css/style.css`
2. Press `Ctrl + H` (Find and Replace)
3. Find: `#003366` → Replace with your new color
4. Click **Replace All**
5. Save

**Color options:**

| Color | Code | Look |
|-------|------|------|
| Dark Navy Blue (current) | `#003366` | Professional |
| Dark Green | `#1b5e20` | Nature/Growth |
| Dark Red/Maroon | `#8b0000` | Bold |
| Dark Purple | `#4a148c` | Royal |
| Dark Teal | `#004d40` | Modern |
| Royal Blue | `#1565c0` | Tech |

Also replace `#004080` with a slightly lighter shade of your chosen color.

---

## 4. How to Change the Logo

1. Get your university logo (PNG format)
2. Rename it to: `MKUR-logo.png`
3. Go to `ztna_system/images/`
4. Replace the old file with your new one
5. Refresh browser — new logo appears everywhere

---

## 5. How to Change Contact Information

1. Open `index.php`
2. Scroll to the "Contact Us" section near the bottom
3. Change the address, email, phone numbers to your university's info
4. Save

---

## 6. PRESENTATION SCRIPT

### Before You Start (Setup — Do 10 Minutes Before)

1. Open XAMPP → Start Apache + MySQL
2. Open browser → go to `http://localhost/ztna_system/`
3. Test login with `admin` / `Admin@1234` to make sure it works
4. Logout and go back to homepage

---

### Part 1: Introduction (2 minutes)

**SAY:**
> "Good morning/afternoon. My name is [YOUR NAME], student ID [YOUR ID].
> 
> My project is: Zero-Trust Network Access Architecture for University Campuses.
> 
> The problem: Traditional networks use perimeter-based security — once you're inside the network, you're trusted. This is dangerous because if one account is compromised, the attacker can access everything.
> 
> My solution: A Zero-Trust system that NEVER trusts any user by default. Every access is verified, every action is monitored, and resources are segmented by role."

---

### Part 2: Show Homepage (1 minute)

**DO:** Open `http://localhost/ztna_system/`

**SAY:**
> "This is the system homepage. It explains what ZTNA is, the features, and the services available. Users can register or login from here."

**POINT OUT:** The navigation, hero section, features, services for each role, contact info.

---

### Part 3: Show Registration with Validation (2 minutes)

**DO:** Click "Create Account" or go to `http://localhost/ztna_system/register.php`

**SAY:**
> "Let me show the registration. The system enforces strict validation."

**DEMO:**
- Type `john123` in Full Name field → show the red error: "letters only"
- Type `test` in email → show error: "must be valid email"
- Type `weak` in password → show the password rules not met
- Then fill correctly with a real name, real email, strong password
- Show that registration works

**SAY:**
> "This is Objective ii — the authentication system verifies users with strong credentials. Passwords are encrypted with bcrypt before storing."

---

### Part 4: Show Login & Access Monitoring (2 minutes)

**DO:** Go to login page

**DEMO 1 — Failed login:**
- Enter `admin` / `WrongPassword`
- Show the error message

**SAY:**
> "The failed attempt was logged with my IP address and device. Let me prove it."

**DEMO 2 — Successful login:**
- Enter `admin` / `Admin@1234`
- You're now in the admin dashboard

**SAY:**
> "I'm now in the admin panel. Notice the stats: total users, logins today, failed attempts."

**DO:** Click **Access Logs** in sidebar

**SAY:**
> "Here you can see EVERY access attempt. Look — there's my failed attempt from just now with my IP address, device, and timestamp. This is Objective i — the system monitors all access."

---

### Part 5: Show Role-Based Access Control (2 minutes)

**SAY:**
> "Now let me prove that roles are strictly enforced."

**DO:** Logout → Login as `student1` / `Student@1234`

**SAY:**
> "I'm now logged in as a student. I can see: Learning Platform, Academic Records, Library, Email."

**DO:** Click on "Learning Platform"

**POINT OUT the green ZTNA banner at the top:**
> "Look at this green banner — it shows:
> - My role was verified: Student
> - My IP address: 127.0.0.1
> - The network segment: Academic Network
> - Access was ALLOWED
> - The exact time
> 
> This proves the system is continuously verifying my identity on every page."

**DO:** Now type in the URL bar: `http://localhost/ztna_system/dashboard_admin.php`

**SAY:**
> "Watch — I'm trying to access the admin panel as a student..."

**RESULT:** Redirected to login page.

**SAY:**
> "BLOCKED. A student cannot access admin resources. This is the Zero-Trust principle and Objective ii — strict access control."

---

### Part 6: Show My Security Page (1 minute)

**DO:** Login as any user → click **My Security** in sidebar

**SAY:**
> "Every user can see their own security information. It shows:
> - Their account details
> - Total logins and denied attempts
> - Their last IP address and device captured
> - Which network segments they can access
> - The active security policies
> - Their full access history
> 
> This demonstrates all three objectives working together — access control, authentication verification, and network segmentation all visible on one page."

---

### Part 7: Show Network Segmentation & Policies (2 minutes)

**DO:** Login as admin → click **Network Segments**

**SAY:**
> "This is Objective iii — network segmentation. The university network is divided into zones:
> - Academic Network: students, lecturers
> - Administrative Network: admin, staff
> - Research Network: lecturers only
> - General Network: everyone
> 
> Each segment defines which roles are allowed. The admin can add, edit, or delete segments."

**DO:** Click **Security Policies**

**SAY:**
> "These are the security policies that protect the system:
> - Multi-Factor Authentication
> - Device Compliance Check
> - Session Timeout
> - IP Restriction
> 
> The admin can create new policies and activate or deactivate them. This is also Objective iii."

---

### Part 8: Show Account Suspension (1 minute)

**DO:** Admin → User Management → Click "Suspend" on student1

**SAY:**
> "I just suspended this account. Now watch..."

**DO:** Logout → Try to login as `student1` / `Student@1234`

**RESULT:** "Account suspended. Contact the network administrator."

**SAY:**
> "Even with the correct password, access is denied. This demonstrates protection against internal threats — if an account is compromised, the admin can instantly block it."

**DO:** Go back to admin → Activate the account again (for next demo).

---

### Part 9: Show PDF Report (1 minute)

**DO:** Admin → Reports → Click "Download PDF Report"

**SAY:**
> "The system generates real PDF reports for auditing. This PDF shows all users, login activity, access statistics, and security status."

---

### Part 10: Conclusion (1 minute)

**SAY:**
> "In conclusion, this system successfully implements all three objectives:
> 
> 1. Objective i: The architecture controls access through role verification on every page, and monitors it through comprehensive logging of IP, device, and timestamps.
> 
> 2. Objective ii: The authentication system uses bcrypt encryption, captures device and IP information, enforces strong passwords, and blocks suspended accounts.
> 
> 3. Objective iii: Network segmentation divides resources into zones with role-based access, and security policies provide configurable rules to protect against threats.
> 
> Thank you."

---

## 7. Common Questions the Panel Will Ask

**Q: "What is Zero-Trust?"**
> "Zero-Trust is a security model where no user or device is trusted by default. Every access request must be verified — even if the user is inside the network."

**Q: "How are passwords stored?"**
> "Passwords are hashed using bcrypt via PHP's password_hash() function. They're never stored as plain text. Even if the database is stolen, passwords can't be read."

**Q: "What happens if someone enters wrong credentials?"**
> "The system logs the failed attempt with IP address, device, and timestamp, then shows an error. The admin can see all failed attempts in the Access Logs."

**Q: "How does role-based access work?"**
> "After login, the user's role is stored in the session. Every page checks: is the session valid? Is the role correct? If not, the user is redirected to login. A student physically cannot access admin pages."

**Q: "What is network segmentation?"**
> "Dividing the network into zones. Academic resources are separate from administrative ones. Each zone has specific roles allowed to access it. This limits damage if one account is breached."

**Q: "What prevents SQL injection?"**
> "All database queries use prepared statements with parameter binding. User input is never directly placed in SQL — it's treated as data, not code."

**Q: "How do you monitor access?"**
> "Every login, logout, and resource access is recorded in the database with user ID, IP address, device info, timestamp, and result. The admin can view this in Access Logs and generate PDF reports."

**Q: "What technologies did you use?"**
> "PHP for server-side logic, MySQL for the database, HTML/CSS for the frontend, JavaScript for form validation, XAMPP as the local server, and FPDF for PDF report generation."

**Q: "Can this scale for real use?"**
> "This is a working prototype. For production, you would add HTTPS, two-factor authentication, rate limiting, and deploy on a proper web server."

**Q: "What's the difference between this and traditional security?"**
> "Traditional security trusts everyone inside the network. ZTNA verifies every request, regardless of location. Our system proves this — even logged-in users are checked on every page."

---

## 8. Tips for Presentation Day

1. ✅ Start XAMPP 10 minutes early (Apache + MySQL)
2. ✅ Test login before presenting
3. ✅ Use Chrome or Firefox (best CSS display)
4. ✅ Keep the URL bar visible so panel sees `localhost/ztna_system`
5. ✅ Have this guide open on a second tab for reference
6. ✅ Speak slowly and clearly
7. ✅ Point at the green ZTNA banner — it's your best visual proof
8. ✅ If something breaks, say "Let me re-import the database" and go to phpMyAdmin
9. ✅ Don't forget to re-activate student1 after the suspension demo

---

## Quick Setup Checklist

- [ ] XAMPP installed
- [ ] `ztna_system` folder in `C:\xampp\htdocs\`
- [ ] Database imported (including `resource_access` table)
- [ ] Login works with admin/Admin@1234
- [ ] All four roles work (admin, student, lecturer, staff)
- [ ] Green ZTNA banner shows on resource pages
- [ ] PDF download works
- [ ] My Security page shows access history

---

Good luck! 🎉

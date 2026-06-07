# 🎓 PRESENTATION GUIDE — How to Customize This Project

**This guide is for the student presenting this project.**  
**No coding experience needed. Just follow the simple steps below.**

---

## 📋 TABLE OF CONTENTS

1. [How to Change Your Name & Student ID](#1-how-to-change-your-name--student-id)
2. [How to Change the University Name](#2-how-to-change-the-university-name)
3. [How to Change Colors](#3-how-to-change-colors)
4. [How to Change the Logo](#4-how-to-change-the-logo)
5. [How to Change Contact Information](#5-how-to-change-contact-information)
6. [How to Present the Project (Script)](#6-how-to-present-the-project)
7. [Common Questions You Might Be Asked](#7-common-questions-you-might-be-asked)

---

## 1. How to Change Your Name & Student ID

Your name and student ID appear in two places:

### Place 1: The Footer (bottom of homepage)

1. Open the file: `index.php`
2. Press `Ctrl + H` (Find and Replace)
3. Find: `INGABIRE GISELE`
4. Replace with: `YOUR FULL NAME`
5. Click **Replace All**
6. Then find: `BBICTR/2024/36790`
7. Replace with: `YOUR STUDENT ID`
8. Click **Replace All**
9. Save the file (`Ctrl + S`)

### Place 2: Inside PHP file comments (not visible to users, but visible if code is checked)

1. Open any `.php` file
2. At the top you'll see:
```
 * Student: INGABIRE GISELE
 * Student ID: BBICTR/2024/36790
```
3. Change these to your name and ID
4. **TIP:** You can use `Ctrl + H` in your text editor and do "Replace All" across all files

---

## 2. How to Change the University Name

The university name "Mount Kigali University" appears in many files.

### Easy way (for all files at once):

1. Open the folder `ztna_system` in a text editor (like VS Code or Notepad++)
2. Use **Find and Replace in All Files**:
   - In VS Code: Press `Ctrl + Shift + H`
   - In Notepad++: Go to Search → Find in Files
3. Find: `Mount Kigali University`
4. Replace with: `Your University Name`
5. Click **Replace All**
6. Save all files

### Also change the motto:

1. Find: `Empowering Generations Through Education`
2. Replace with: `Your University Motto`

---

## 3. How to Change Colors

All colors are in ONE file: `css/style.css`

### The Main Color (Dark Navy Blue):

The current color is: `#003366` (dark navy blue)

To change it:

1. Open: `css/style.css`
2. Press `Ctrl + H` (Find and Replace)
3. Find: `#003366`
4. Replace with your new color (see color options below)
5. Click **Replace All**
6. Save the file

### Popular Color Options:

| Color | Code | What it looks like |
|-------|------|-------------------|
| Dark Navy Blue (current) | `#003366` | Professional, formal |
| Dark Green | `#1b5e20` | Nature, growth |
| Dark Red / Maroon | `#8b0000` | Bold, powerful |
| Dark Purple | `#4a148c` | Royal, creative |
| Dark Teal | `#004d40` | Modern, calm |
| Black | `#1a1a1a` | Sleek, minimal |
| Dark Orange | `#e65100` | Energetic |
| Royal Blue | `#1565c0` | Trust, tech |

### Also change the lighter blue (used for hover effects):

1. Find: `#004080`
2. Replace with a slightly lighter version of your chosen color

### Example — Changing to Dark Green:

1. Replace `#003366` → `#1b5e20`
2. Replace `#004080` → `#2e7d32`
3. Save. Done!

### Also change the dark background color:

1. Find: `#001a33` (very dark blue, used in footer/hero)
2. Replace with a darker version of your color
   - For green: use `#0d3311`
   - For red: use `#3d0000`
   - For purple: use `#1a0033`

---

## 4. How to Change the Logo

1. Get your university logo image (PNG format works best)
2. Rename it to: `MKUR-logo.png`
3. Go to: `ztna_system/images/`
4. Delete the old `MKUR-logo.png`
5. Paste your new logo file there (with the same name: `MKUR-logo.png`)
6. Refresh the browser — your new logo will appear everywhere!

### If your logo has a different filename:

1. Press `Ctrl + Shift + H` in VS Code (Find in All Files)
2. Find: `MKUR-logo.png`
3. Replace with: `your-logo-filename.png`
4. Click Replace All
5. Save all files

---

## 5. How to Change Contact Information

1. Open: `index.php`
2. Scroll to the bottom — find the "Contact Us" section
3. You'll see:

```html
<p>Mount Kigali University<br>Kigali, Rwanda</p>
```
Change to your university's location.

```html
<p>it-support@mku.ac.rw<br>admin@mku.ac.rw</p>
```
Change to your university's email.

```html
<p>+250 788 000 000<br>+250 722 000 000</p>
```
Change to your university's phone number.

4. Save the file.

---

## 6. How to Present the Project

### Presentation Script (What to Say):

**SLIDE / DEMO 1: Introduction (1-2 minutes)**

> "Good morning/afternoon. My name is [YOUR NAME], student ID [YOUR ID]. 
> Today I'm presenting my project: a Zero-Trust Network Access System 
> built for [University Name].
>
> Zero-Trust means 'never trust, always verify.' Unlike traditional 
> security where once you're inside the network you're trusted, 
> Zero-Trust verifies every user, every time, before giving access."

**DEMO 2: Show the Homepage (1 minute)**

> "This is the homepage. It explains what the system does, the features,
> and the services available for students, lecturers, and staff."

Open: `http://localhost/ztna_system/`

**DEMO 3: Show Registration (1 minute)**

> "Users can create accounts. Notice the validation — the system requires
> real names (no numbers allowed), valid email addresses, and strong 
> passwords with uppercase, lowercase, numbers, and special characters."

Click "Create Account" and show the form validation.

**DEMO 4: Login as Admin (2 minutes)**

> "Let me login as the network administrator."
> Login with: admin / Admin@1234

> "The admin dashboard shows statistics: total users, logins today, 
> failed attempts, and network segments. From here, the admin can 
> manage users, view access logs, set security policies, and configure
> network segments."

Show each admin section briefly.

**DEMO 5: Show Access Logs (1 minute)**

> "Every login attempt is recorded with the user's IP address and 
> device information. This is a core Zero-Trust principle — continuous 
> monitoring and verification."

**DEMO 6: Login as Student (1 minute)**

> Logout, then login as: student1 / Student@1234

> "Students see their own dashboard with access to learning platforms,
> academic records, and library services. They cannot access admin pages."

**DEMO 7: Security Features (1 minute)**

> "The system uses bcrypt password hashing, prepared SQL statements 
> to prevent injection attacks, session-based access control, and 
> IP/device tracking. Suspended accounts are automatically blocked."

**CLOSING (30 seconds)**

> "This system demonstrates how Zero-Trust principles can be applied 
> to secure a university's digital resources. Thank you."

---

## 7. Common Questions You Might Be Asked

### Q: "What is Zero-Trust?"
**A:** "Zero-Trust is a security model where no user or device is automatically trusted. Every access request is verified — even if the user is inside the network. The principle is: never trust, always verify."

### Q: "What technologies did you use?"
**A:** "PHP for server-side logic, MySQL for the database, HTML/CSS for the frontend, JavaScript for form validation, and XAMPP as the local server environment."

### Q: "How are passwords stored?"
**A:** "Passwords are hashed using bcrypt (PHP's password_hash function). They're never stored in plain text. Even if someone steals the database, they can't read the passwords."

### Q: "What happens if someone enters wrong credentials?"
**A:** "The system logs the failed attempt with the IP address and device info, then shows an error message. If the account is suspended, it tells the user to contact the administrator."

### Q: "How does role-based access work?"
**A:** "When a user logs in, the system checks their role (admin, student, lecturer, or staff) and redirects them to their specific dashboard. Each page checks the session — if a student tries to access the admin page, they're blocked and sent back to login."

### Q: "What is network segmentation?"
**A:** "It means dividing the network into separate zones. Each zone has specific roles allowed to access it. For example, the Research Network is only for lecturers, while the Administrative Network is for admins and staff."

### Q: "What prevents SQL injection?"
**A:** "All database queries use prepared statements with parameter binding. This means user input is never directly inserted into SQL queries — it's treated as data, not code."

### Q: "Can this system scale for real use?"
**A:** "This is a prototype/demonstration. For real deployment, you would add HTTPS encryption, two-factor authentication, rate limiting, and deploy it on a proper web server instead of XAMPP."

---

## 💡 QUICK TIPS FOR PRESENTATION DAY

1. **Start XAMPP early** — Open XAMPP and start Apache + MySQL 10 minutes before your presentation
2. **Test the login** — Make sure you can login with admin/Admin@1234 before presenting
3. **Use Chrome or Firefox** — They display the CSS styling best
4. **Keep the URL visible** — Show `localhost/ztna_system` in the address bar so the panel can see it's running locally
5. **Have this guide open** — In case you need to reference something
6. **Speak confidently** — You built this. You know how it works!

---

## ⚠️ IMPORTANT: Before Presentation Day

1. Make sure XAMPP is installed on the presentation computer
2. Copy the `ztna_system` folder to `C:\xampp\htdocs\`
3. Import the database (Step 4 from README)
4. Test that login works
5. Done!

---

Good luck with your presentation! 🎉

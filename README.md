# 🔍 AI Content Verification & Blockchain Audit Platform

A web platform for reviewing and verifying content with a structured user review system.

---

# 📂 Project Structure

```
Suggestify/
│
├── index.php                  # Main landing page
├── login.html                 # User login page
├── logout.php                 # User logout script
├── register.php               # User registration page
│
├── db_connection.php          # Database connection file
│
├── images/                    # Directory for images
│   ├── drama-logo.png         # Application logo
│   ├── review/
│   │   ├── squid-game.jpeg
│   │   ├── Marry_My_Husband.jpg
│   │   ├── BLUELOCK.jpg
│   │   ├── Dandadan.jpg
│   │   ├── BluePeriod-Poster.jpg
│   │   ├── Alchemy_of_Souls.jpg
│   │   └── slamDunk.jpg
│   │
│   └── pngtree-pug-face-png-image_6888946.png   # Default user avatar
│
├── css/
│   ├── grid.css
│   └── app.css
│
├── js/
│   └── app.js
│
├── review.php
├── about.html
├── change_nickname.php
├── change_email.php
├── change_password.php
├── upload_avatar.php
└── register-status.php
```

---

# ⚙️ Installation Guide

## 1️⃣ Copy Project Files

Copy the project folder into your web server directory.

Example:

```
xampp/htdocs/Suggestify
```

---

## 2️⃣ Import Database

Open **PHPMyAdmin** and import the SQL file:

```
G02.sql
```

This will create the required database and tables.

---

## 3️⃣ Configure Database Connection

Edit the following files:

```
login.php
register.php
db_connection.php
```

Update the database configuration:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "YOUR_DATABASE_NAME";
```

Adjust the values according to your MySQL configuration.

---

# 👤 User Registration & Login

## Register

Go to:

```
register.html
```

Create a new account.

---

## Login

Go to:

```
login.html
```

Enter your username and password to access the system.

---

# 🖼 Example Image

Example of displaying an image from the project:

```markdown
![Logo](images/drama-logo.png)
```

---

# 🛠 Tech Stack

- PHP
- MySQL
- HTML
- CSS
- JavaScript

---

# 📜 License

This project is for educational purposes.

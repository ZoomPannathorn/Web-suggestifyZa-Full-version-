# 🔍🎬 SuggestifyZa: Anime & K-Drama Review Platform

Suggestify is a web-based community platform for Anime and Korean Drama (K-Drama) enthusiasts. The platform allows users to discover trending series, read community reviews, and manage their personal watchlists.

---
<img width="975" height="455" alt="537346863-304e73d6-3f0f-4017-87f1-7017691df9a2" src="https://github.com/user-attachments/assets/a2d39266-b44d-4c05-a389-6a08c8dfc2e1" />
<img width="834" height="773" alt="Screenshot 2026-03-16 221644" src="https://github.com/user-attachments/assets/478c1302-7f08-4ca2-aaf5-9c99a64d05f9" />
<img width="835" height="757" alt="Screenshot 2026-03-16 221713" src="https://github.com/user-attachments/assets/46e81898-410a-4990-bb1b-d1f7f8a094ab" />
<img width="832" height="449" alt="Screenshot 2026-03-16 221753" src="https://github.com/user-attachments/assets/8d828618-83b0-4d6c-bd62-319e5a3c601b" />
<img width="734" height="354" alt="Screenshot 2026-03-16 221908" src="https://github.com/user-attachments/assets/3172c96b-5165-422c-9232-24368ab7a121" />
<img width="288" height="317" alt="Screenshot 2026-03-16 221932" src="https://github.com/user-attachments/assets/023fc899-0fd7-454a-86b7-1522c8d01ded" />


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

## 🚀 Key Features

### 🌟 Content Discovery
- **Curated Lists** – Browse popular Anime and K-Dramas (e.g., *Start-Up*, *Demon Slayer*, *Blue Lock*).
- **Category Filtering** – Easily filter content between **Anime** and **Series** categories.

### ✍️ Community & Reviews
- **User Reviews** – Users can post comments and share opinions about shows.
- **Rating System** – Dynamic calculation of average ratings based on user feedback.

### 👤 User Personalization
- **Profile Management** – Users can customize their profiles, including **avatar uploads**.
- **Personal Watchlist** – Save interesting titles to a private **“Watch Later”** list.
- **Account Security** – Secure **Login / Register system** with session management.

---

## 🛠️ Tech Stack

### Frontend
- **HTML5**
- **CSS3** (Custom Grid System – `grid.css`)
- **JavaScript** (DOM Manipulation)

### Backend
- **PHP (Native)** – Handles server-side logic and session management.

### Database
- **MySQL** – Relational database used to store users, content, and reviews.

### Architecture
- **MVC-inspired structure** separating:
  - Logic
  - Views
  - Database operations

---

## 💾 Database Structure

The system uses a **Relational Database** (see `SQL_WEB.sql`). Key tables include:

| Table Name | Description |
|------------|-------------|
| users | Stores user credentials, email, and profile image paths |
| content | Metadata for Anime & K-Dramas (Title, Genre, Description) |
| reviews | Stores user comments and ratings (linked to users and content) |
| watchlist | Many-to-many relationship table for users' saved content |

---

## ⚙️ Installation Guide (Localhost)

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/PhonrawatLimfaguang/suggestifyZa-entertainment-platform.git
```

---

### 2️⃣ Setup Web Server

Use **XAMPP** or **WAMP**

Move the project folder to:

```
htdocs/     (for XAMPP)
```

or

```
www/        (for WAMP)
```

---

### 3️⃣ Import Database

1. Open **phpMyAdmin**

```
http://localhost/phpmyadmin
```

2. Create a new database:

```
suggestify_db
```

3. Import the SQL file included in the repository:

```
SQL_WEB.sql
```

---

### 4️⃣ Configure Database Connection

Open:

```
db_connection.php
```

Ensure the credentials match your local MySQL configuration.

Example:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "suggestify_db";
```

---

### 5️⃣ Run the Project

Open your browser and go to:

```
http://localhost/suggestifyZa-entertainment-platform/index.php
```

The platform should now be running locally.
# 📜 License

This project is for educational purposes.

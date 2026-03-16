# 🎬 SuggestifyZa: Anime & K-Drama Review Platform


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
│   ├── drama-logo.png         # Logo for the application
│   ├── review/                # Directory for review images
│   │   ├── squid-game.jpeg    # Image for Squid Game
│   │   ├── Marry_My_Husband.jpg # Image for Marry My Husband
│   │   ├── BLUELOCK.jpg       # Image for Blue Lock
│   │   ├── Dandadan.jpg       # Image for Dandadan
│   │   ├── BluePeriod-Poster.jpg # Image for Blue Period
│   │   ├── Alchemy_of_Souls.jpg # Image for Alchemy of Souls
│   │   └── slamDunk.jpg       # Image for Slam Dunk
│   └── pngtree-pug-face-png-image_6888946.png # User avatar placeholder
│
├── css/                       # Directory for CSS files
│   ├── grid.css               # Grid layout styles
│   └── app.css                # Main application styles
│
├── js/                        # Directory for JavaScript files
│   └── app.js                 # Main application JavaScript
│
├── review.php                 # Review page
├── about.html                 # About page
├── change_nickname.php        # Change user nickname script
├── change_email.php           # Change user email script
├── change_password.php        # Change user password script
├── upload_avatar.php          # Upload user avatar script
└── register-status.php         # Registration status page


การติดตั้งโปรเจกต์: คัดลอกไฟล์จากโฟลเดอร์ source
import G02.sql ใน PHPMyAdmin เพื่อสร้างฐานข้อมูลและตาราง

แก้การเชื่อมต่อกับฐานข้อมูลใน login.php,register.php,db_connection.php
$servername = "localhost"; // เปลี่ยน server host ตาม MySQL ที่ใช้
$username = "root"; // เปลี่ยน user ตาม MySQL ที่ใช้
$password = ""; // เปลี่ยน password ตาม MySQL ที่ใช้
$dbname = ""; // ชื่อฐานข้อมูล

สมัครสมาชิกผ่าน register ในหน้า register.html แล้วสามารถ login ผ่าน login.html


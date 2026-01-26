<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data from the database
$userID = $_SESSION['user_id']; // Assuming user_id is stored in session
$stmt = $pdo->prepare("SELECT username, email, profile_picture FROM user WHERE user_id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    echo "User  not found.";
    exit;
}

// Assign user data to variables
$userName = $user['username'];
$userEmail = $user['email'];
$userProfilePicture = $user['profile_picture'];
// Convert binary data to base64 for display
if ($userProfilePicture) {
    $base64 = base64_encode($userProfilePicture);
    $userProfilePictureSrc = 'data:image/jpeg;base64,' . $base64; // Adjust MIME type if necessary
} else {
    $userProfilePictureSrc = './images/pngtree-pug-face-png-image_6888946.png'; // Fallback image
}


?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestiffy_Za</title>
    <link rel="stylesheet" href="style.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">


    <style>
:root {
            --main-color: #203641;
            --box-bg: #10171f;
            --text-color: #ffffff;
            --logout-color: #ae0000;
            --logout-hover: #54191f;
            --nav-height: 60px;
        }

        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--box-bg);
            color: var(--text-color);
        }

        .nav-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: var(--main-color);
            padding: 0 20px;
            z-index: 1000;
            height: var(--nav-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);

        }

        .logo {
            color: var(--text-color);
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .nav-menu li a {
            color: var(--text-color);
            font-size: 16px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);

        }

        .nav-menu li a:hover {
            color: #ffcc00;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .dropdown-toggle img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-menu {
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #203641;
            width: 250px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu li {
            list-style: none;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
        }

        .dropdown-menu li:hover {
            background-color: #345667;
        }

        .dropdown-menu li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .dropdown-menu li a i {
            font-size: 16px;
        }

        .logout {
            background-color: var(--logout-color);
            color: #fff;
            text-align: center;
            padding: 10px;
            margin: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: block;
        }

        .logout:hover {
            background-color: var(--logout-hover);
        }
        html {
            scroll-behavior: smooth; /* Smooth Scroll */
        }

    /* ส่วนโปรไฟล์ */
    .profile-container {
    max-width: 60%;
    margin: 80px auto 20px; /* ระยะห่างจาก Navbar */
    background: #333;
    border-radius: 10px;
    padding: 20px;
    
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

    display: flex;            /* ใช้ Flexbox */
    flex-direction: column;   /* จัดเรียงเป็นแนวตั้ง */
    align-items: center;      /* จัดกึ่งกลางแนวนอน */
    justify-content: center;  /* จัดกึ่งกลางแนวตั้ง (หากต้องการ) */
    }
    .avatar-container {
        display: flex;            /* Use Flexbox */
        flex-direction: column;   /* Arrange items vertically */
        align-items: center;      /* Center items horizontally */
        gap: 10px;                /* Add spacing between items */
    }

    label {
        display: flex;            /* Ensure labels behave like Flex items */
        align-items: center;      /* Align input and image within the label */
    }

        .profile-header {
            display:flex;
            align-items:center;
            margin-bottom:20px;
        }

        .profile-header img {
            width:120px;
            height:120px;
            border-radius:50%;
            margin-right:20px;
            object-fit:cover;
        }

        .profile-header h2 {
            margin:0;
        }

        .profile-details {
            display:flex;
            flex-wrap:wrap;
        }

        .profile-details .detail {
            flex:1 1 300px;
            margin-bottom:10px;
            
        }

        .profile-details .detail label {
            font-weight:bold;
            
        }

        .profile-actions {
            margin-top:20px;
        }

        .profile-actions form input, 
        .profile-actions form button {
            padding:8px;
            margin:5px 0;
        }

        .profile-actions form .btn {
            background:#28a745;
            color:#fff;
            border:none;
            cursor:pointer;
        }

        .profile-actions form .btn:hover {
            background:#218838;
        }

        .profile-actions form .btn-delete {
            background:#dc3545;
        }

        .profile-actions h3 {
            margin-top:30px;
        }
   
        .profile-actions form {
    display: flex;
    flex-direction: column;
    align-items: center;  /* จัดฟอร์มให้อยู่กลางแนวนอน */
    justify-content: center; /* จัดฟอร์มให้อยู่กลางแนวตั้ง */
    margin-top: 20px;
}

.profile-actions form input,
.profile-actions form button {
    padding: 8px;
    margin: 10px 0;
    width: 100%;  /* ทำให้ input และ button กว้างเต็มฟอร์ม */
    max-width: 400px;  /* จำกัดขนาดสูงสุด */
}

.profile-actions form .btn {
    background: #004085;
    color: #fff;
    border: none;
    cursor: pointer;
}

.profile-actions form .btn:hover {
    background: #0056b3;
}

.profile-actions form .btn-delete {
    background: #dc3545;
}

.profile-actions h3 {
    margin-top: 30px;
    text-align: center;  /* จัดหัวข้อให้กลาง */
}
.profile-details {
    display: flex;
    flex-direction: column;
    align-items: center; /* จัดกึ่งกลางแนวนอน */
    justify-content: center; /* จัดกึ่งกลางแนวตั้ง */
     /* ทำให้ข้อความในแต่ละส่วนอยู่กึ่งกลาง */
}
.profile-details .detail {
    flex: 1;
    margin-bottom: 10px;
    width: 100%;
     /* ทำให้ข้อความในแต่ละส่วนอยู่กึ่งกลาง */
}
input[type="text"], input[type="password"], input[type="email"] {
    border-radius: 15px; /* กำหนดความโค้งให้กับมุม */
    padding: 10px; /* เพิ่มระยะห่างภายใน */
    border: 2px solid #ccc; /* กำหนดเส้นขอบ */
    width: 100%; /* ทำให้กว้างเต็มช่อง */
    margin: 5px 0; /* เพิ่มระยะห่างระหว่างช่องกรอกข้อมูล */
    font-size: 16px; /* ขนาดตัวอักษร */
    background-color: #f5f5f5; /* สีพื้นหลัง */
}

input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
    border-color: #0056b3; /* เปลี่ยนสีเส้นขอบเมื่อเลือกช่องกรอกข้อมูล */
    outline: none; /* ลบขอบสีฟ้าเมื่อเลือก */
}

button[type="submit"] {
    border-radius: 15px; /* กำหนดความโค้งให้กับมุม */
    padding: 10px 20px; /* เพิ่มระยะห่างภายใน */
    border: 2px solid #007BFF; /* กำหนดเส้นขอบ */
    background-color: #007BFF; /* เปลี่ยนสีพื้นหลังเป็นน้ำเงิน */
    color: white; /* สีตัวอักษร */
    font-size: 16px; /* ขนาดตัวอักษร */
    cursor: pointer; /* เปลี่ยน cursor เป็นมือเมื่อเอาเมาส์ไปวางบนปุ่ม */
    transition: background-color 0.3s ease; /* เพิ่มเอฟเฟกต์การเปลี่ยนสี */
}

button[type="submit"]:hover {
    background-color: #0056b3; /* สีพื้นหลังเมื่อเลื่อนเมาส์ไปวางบนปุ่ม */
}

button[type="submit"]:active {
    background-color: #004085; /* สีพื้นหลังเมื่อคลิกปุ่ม */
}

/* จัดการสำหรับหน้าจอขนาดเล็กมาก (ต่ำกว่า 600px) */
@media (max-width: 600px) {
    .nav-wrapper {
        flex-direction: column; /* เมนูนำทางเป็นแนวตั้ง */
        align-items: flex-start; /* จัดเมนูให้อยู่ทางซ้าย */
        height: auto;
        padding: 10px;
    }

    .nav-menu {
        flex-direction: column;
        gap: 10px;
    }

    .nav-menu li a {
        font-size: 14px;
    }

    .profile-container {
        max-width: 90%; /* ลดความกว้างของโปรไฟล์ */
        padding: 15px;
    }

    .profile-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-header img {
        margin-bottom: 15px;
    }

    .profile-details {
        text-align: center;
    }

    .profile-details .detail {
        flex: 1 1 100%;
    }

    .profile-actions form input,
    .profile-actions form button {
        width: 90%; /* ลดความกว้างให้พอดีกับหน้าจอ */
        max-width: 300px; /* จำกัดขนาดสูงสุด */
    }
}

/* จัดการสำหรับหน้าจอขนาดกลาง (601px - 1024px) */
@media (min-width: 601px) and (max-width: 1024px) {
    .nav-wrapper {
        flex-direction: row;
        justify-content: space-between;
        padding: 10px 15px;
    }

    .nav-menu {
        flex-direction: row;
        gap: 15px;
    }

    .profile-container {
        max-width: 80%; /* เพิ่มความกว้างเล็กน้อย */
        padding: 20px;
    }

    .profile-header img {
        width: 100px;
        height: 100px;
    }

    .profile-actions form input,
    .profile-actions form button {
        width: 80%; /* ลดขนาดความกว้าง */
        max-width: 400px;
    }
}

/* สำหรับหน้าจอใหญ่ (1025px ขึ้นไป) */
@media (min-width: 1025px) {
    .profile-container {
        max-width: 60%; /* ใช้ความกว้างเดิม */
    }

    .profile-header img {
        width: 120px;
        height: 120px;
    }

    .profile-actions form input,
    .profile-actions form button {
        max-width: 500px;
    }
}

/* เพิ่ม Transition ให้ปุ่ม Dropdown */
.dropdown-menu {
    transition: all 0.3s ease, visibility 0.3s;
}

/* เพิ่ม Animation ตอนเลื่อนเมาส์ */
button[type="submit"]:hover {
    transform: scale(1.05); /* ขยายเล็กน้อยเมื่อ Hover */
}


.tab-container {
    margin-bottom: 20px;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.tab {
    padding: 10px 20px;
    cursor: pointer;
    background-color: var(--main-color);
    color: var(--text-color);
    border: none;
    border-radius: 5px;
}

.tab.active {
    background-color: #0056b3;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.slider-container {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.slides {
    display: flex;
    transition: transform 0.3s ease-in-out;
}

.slides label {
    margin: 0 5px;
}

.slides img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 2px solid transparent;
    object-fit: cover;
}

.slides img:hover {
    border: 2px solid #0056b3;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: var(--main-color);
    color: white;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 50%;
    z-index: 10;
}

.slider-btn.prev {
    left: 0;
}

.slider-btn.next {
    right: 0;
}

.tab-container {
    margin-bottom: 20px;
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.tab {
    padding: 10px 20px;
    cursor: pointer;
    background-color: var(--main-color);
    color: var(--text-color);
    border: none;
    border-radius: 5px;
}

.tab.active {
    background-color: #0056b3;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.preset-profiles {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.preset-profiles label {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.preset-profiles img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 2px solid transparent;
    object-fit: cover;
    transition: border-color 0.3s ease;
}

.preset-profiles img:hover {
    border: 2px solid #0056b3;
}
.tab-panel {
    display: none; /* ซ่อนทุกแท็บ */
}

.tab-panel.active {
    display: block; /* แสดงแท็บที่ active */
}
.btn {
    font-family: 'Kanit', sans-serif;
}

.eye-icon {
   
        cursor: pointer;
    }

    </style>
</head>
<body>
    <!-- เมนูนำทาง -->
    <div class="nav-wrapper">
        <a href="index.php" class="logo">Suggestiffy_Za</a>
        <ul class="nav-menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="index.php#korea-series-section">ซีรีส์เกาหลี</a></li>
            <li><a href="index.php#anime-section">อนิเมะ</a></li>
            <li><a href="index.php#special-movie-section">รีวิวยอดนิยม</a></li>
        </ul>
        <div class="dropdown">
            <div class="dropdown-toggle" onclick="toggleDropdown()">
            <img src="<?php echo $userProfilePictureSrc; ?>" alt="User  Avatar" />
                <span id="username">ยินดีต้อนรับคุณ → <?php echo htmlspecialchars($userName); ?></span>
            </div>
            <ul class="dropdown-menu" id="dropdownMenu">
                <li><a href="profile.php"><i class="fas fa-user"></i> โปรไฟล์ </a></li>
                <li><a href="index.php#anime-section"><i class="fas fa-history"></i> Anime Section</a></li>
                <li><a href="index.php#korea-series-section"><i class="fas fa-gift"></i> K-Drama Section</a></li>
                <li><a href="index.php#special-movie-section"><i class="fas fa-box"></i> รีวิวที่ปักมุดไว้ 📌</a></li>
                <a href="logout.php" class="logout">ออกจากระบบ</a>
            </ul>
        </div>
    </div>
    <!-- ส่วนเนื้อหาโปรไฟล์ -->
    <div class="profile-container">
        <div class="profile-header">
        <img src="<?php echo $userProfilePictureSrc; ?>" alt="User  Avatar" />
            <div>
            </div>
        </div>

        <div class="profile-details">
            <div class="detail">
                <label>ไอดี (Username) :</label> <?php echo htmlspecialchars($userName); ?>
            </div>
            <div class="detail">
                <label>อีเมล (Email) :</label> <?php echo htmlspecialchars($userEmail); ?>
            </div>
        </div>

        <div class="profile-actions">
            <h3>เปลี่ยนพาสเวิร์ด</h3>
    <form method="post" action="change_password.php">
        <input type="password" name="old_password" id="old_password" placeholder="กรอกรหัสผ่านเก่า" required>
        <span class="eye-icon" onclick="togglePassword('old_password')">&#128065;</span><br>
        
        <input type="password" name="new_password" id="new_password" placeholder="กรอกรหัสผ่านใหม่" required>
        <span class="eye-icon" onclick="togglePassword('new_password')">&#128065;</span><br>
        
        <input type="password" name="confirm_password" id="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
        <span class="eye-icon" onclick="togglePassword('confirm_password')">&#128065;</span><br>
        
        <button type="submit" class="btn">เปลี่ยนพาสเวิร์ด</button>
    </form>

            <h3>เปลี่ยน UserID</h3>
            <form method="post" action="change_nickname.php">
                <input type="text" name="new_nickname" placeholder="กรอกชื่อเล่นใหม่" required><br>
                <button type="submit" class="btn">เปลี่ยนชื่อเล่นใหม่</button>
            </form>

            <h3>เปลี่ยนอีเมล</h3>
            <form method="post" action="change_email.php">
                <input type="email" name="new_email" placeholder="กรอกอีเมลใหม่" required><br>
                <button type="submit" class="btn">อัปเดตอีเมล</button>
            </form>
            
    <!-- เพิ่มฟอร์มเปลี่ยนรูปโปรไฟล์_1 -->
    <h3>เปลี่ยนรูปโปรไฟล์</h3>
<form method="post" action="upload_avatar.php">
    <div class="tab-container">
    <div class="tabs">
    </div>
    <div class="tab-content">
            <!-- หมวดหมู่ซีรี่ย์ -->
            <div id="series" class="tab-panel active">
            <div class="preset-profiles">
        <label>
            <input type="radio" name="preset_avatar" value="start-up-poster_cropped.png" required>
            <img src="./images/profile/start-up-poster_cropped.png" alt="Avatar 1" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-poster_cropped (1).png" required>
            <img src="./images/profile/start-up-poster_cropped (1).png" alt="Avatar 2" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-poster_cropped (2).png" required>
            <img src="./images/profile/start-up-poster_cropped (2).png" alt="Avatar 3" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-poster_cropped (3).png" required>
            <img src="./images/profile/start-up-poster_cropped (3).png" alt="Avatar 4" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-kdrama_cropped.png" required>
            <img src="./images/profile/start-up-kdrama_cropped.png" alt="Avatar 5" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-kdrama_cropped (1).png" required>
            <img src="./images/profile/start-up-kdrama_cropped (1).png" alt="Avatar 6" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="start-up-kdrama_cropped (2).png" required>
            <img src="./images/profile/start-up-kdrama_cropped (2).png" alt="Avatar 7" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="st_cropped.png" required>
            <img src="./images/profile/st_cropped.png" alt="Avatar 8" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>

        <label>
            <input type="radio" name="preset_avatar" value="aira.jpg" required>
            <img src="./images/profile/aira.jpg" alt="Avatar 1" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="damon.jpg" required>
            <img src="./images/profile/damon.jpg" alt="Avatar 2" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="ken.jpg" required>
            <img src="./images/profile/ken.jpg" alt="Avatar 3" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="jiji.jpg" required>
            <img src="./images/profile/jiji.jpg" alt="Avatar 4" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="momo.jpg" required>
            <img src="./images/profile/momo.jpg" alt="Avatar 5" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="sarasara.jpg" required>
            <img src="./images/profile/sarasara.jpg" alt="Avatar 6" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="seiko.jpg" required>
            <img src="./images/profile/seiko.jpg" alt="Avatar 7" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
        <label>
            <input type="radio" name="preset_avatar" value="turbo-granny.jpg" required>
            <img src="./images/profile/turbo-granny.jpg" alt="Avatar 8" style="width: 100px; height: 100px; border: 2px solid transparent;">
        </label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn">เลือกรูปโปรไฟล์</button>
</form>




<script>
    const radios = document.querySelectorAll('input[name="preset_avatar"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            // Remove border from all images
            document.querySelectorAll('.preset-profiles img').forEach(img => {
                img.style.border = '2px solid transparent';
            });
            // Add border to the selected image
            const selectedImg = radio.nextElementSibling;
            selectedImg.style.border = '2px solid blue'; // Change to your desired color
        });
    });
    
    // การคลิกที่ข้อความต้อนรับเพื่อแสดง/ซ่อนเมนูออกจากระบบ
document.getElementById("welcome-message").addEventListener("click", function() {
    let logoutMenu = document.getElementById("logout-menu");
    logoutMenu.style.display = logoutMenu.style.display === "block" ? "none" : "block";
});

// ถ้าผู้ใช้คลิกที่ลิงค์ "ออกจากระบบ", ให้ทำการล้างข้อมูลการล็อกอิน (หรือทำการออกจากระบบจริงๆ)
document.getElementById("logout-link").addEventListener("click", function() {
    alert("ออกจากระบบแล้ว!");
    sessionStorage.clear(); // Clear session storage
    localStorage.clear();   // Clear local storage
    window.location.href = 'login.html'; // ไปยังหน้าล็อกอิน
});

document.querySelectorAll('.nav-menu a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault(); // ป้องกันการเปลี่ยนหน้าทันที
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);

        targetElement.scrollIntoView({
            behavior: 'smooth'
        });
    });
});

    function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('active');
        }

        document.addEventListener('click', (e) => {
            const dropdown = document.querySelector('.dropdown');
            const menu = document.getElementById('dropdownMenu');
            if (!dropdown.contains(e.target)) {
                menu.classList.remove('active');
            }
        });

// เปลี่ยนแท็บ
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function () {
        const target = this.dataset.tab; 

        // ลบคลาส active จากแท็บและแผงเนื้อหาทั้งหมด
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

        // เพิ่มคลาส active ให้กับแท็บและแผงเนื้อหาที่ถูกเลือก
        this.classList.add('active');
        document.getElementById(target).classList.add('active');
    });
});

    // ฟังก์ชันในการสลับประเภทของ input
    function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        var icon = event.target; // ใช้เพื่อเข้าถึงไอคอนที่คลิก
        if (input.type === "password") {
            input.type = "text"; // เปลี่ยนเป็นข้อความ
            icon.innerHTML = "&#128064;"; // เปลี่ยนไอคอนเป็นไอคอนลูกตาที่ปิด
        } else {
            input.type = "password"; // เปลี่ยนกลับเป็นรหัสผ่าน
            icon.innerHTML = "&#128065;"; // เปลี่ยนไอคอนเป็นไอคอนลูกตาที่เปิด
        }
    }



</script>
</body>
</html>

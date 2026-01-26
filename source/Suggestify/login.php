<?php
session_start(); // เริ่ม session เพื่อเก็บข้อมูลผู้ใช้หลังจากเข้าสู่ระบบ

// เชื่อมต่อกับฐานข้อมูล
$servername = "10.1.3.40";  // กำหนดเซิร์ฟเวอร์ฐานข้อมูล
$username = "66102010584";  // ชื่อผู้ใช้ฐานข้อมูล
$password = "66102010584";  // รหัสผ่านฐานข้อมูล
$dbname = "66102010584";    // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่าได้ส่งข้อมูลฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = $_POST['username'];  // รับชื่อผู้ใช้จากฟอร์ม
    $password_input = $_POST['password'];  // รับรหัสผ่านจากฟอร์ม

    // ป้องกัน SQL Injection
    $username_input = $conn->real_escape_string($username_input);
    $password_input = $conn->real_escape_string($password_input);

    // คำสั่ง SQL เพื่อค้นหาผู้ใช้ในฐานข้อมูล
    $sql = "SELECT * FROM user WHERE username = ?";

    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_input); // Bind parameter สำหรับ username
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบว่ามีผู้ใช้หรือไม่
    if ($result->num_rows > 0) {
        // ถ้ามีผู้ใช้, ตรวจสอบรหัสผ่าน
        $user = $result->fetch_assoc();  // รับข้อมูลผู้ใช้

        // ตรวจสอบรหัสผ่านที่เข้ารหัสแล้ว
        if (password_verify($password_input, $user['password'])) {
            // ถ้ารหัสผ่านถูกต้อง
            $_SESSION['user_id'] = $user['user_id'];  // เก็บข้อมูล user_id ใน session
            $_SESSION['username'] = $user['username']; // เก็บชื่อผู้ใช้ใน session
            // เปลี่ยนเส้นทางไปยัง index.php
            header("Location: index.php");
            exit;
        } else {
            // ถ้ารหัสผ่านไม่ถูกต้อง
            header("Location: login.php?error=ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง");
            exit;
        }
    } else {
        // ถ้าไม่พบผู้ใช้
        header("Location: login.php?error=ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง");
        exit;
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* สไตล์พื้นฐาน */
        :root {
            --main-color: #212324; /* สีฟ้า */
            --box-bg: #221f1f;
            --text-color: #ffffff;
            --nav-height: 60px;
            --space-top: 30px;
        }

        /* กำหนดให้ใช้ฟอนต์ Kanit ทั่วทั้งหน้า */
        body {
            font-family: 'Kanit', sans-serif;
            background: url('https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXZ0cXFkaDV0c2tnZzg1YmdyZGptbWUyaXlybTg0Njl4M2Nscmp6NiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Uz4cDaGXPxeuY/giphy.gif') no-repeat center center fixed;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            height: 100vh;
            margin: 0;
        }

        /* เมนูนำทาง */
        .nav-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: var(--main-color);
            padding: 10px 20px;
            z-index: 1000;
            height: var(--nav-height);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .nav-menu li {
            margin: 0 15px;
        }

        .nav-menu li a {
            color: var(--text-color);
            font-size: 16px;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease-in-out;
        }

        /* เปลี่ยนสีเมื่อ hover บนลิงก์ */
        .nav-menu li a:hover {
            color: var(--main-color);
        }

        /* ปุ่มเข้าสู่ระบบ */
        .btn-hover {
            background-color: var(--main-color);
            color: var(--text-color);
            padding: 8px 15px;
            border-radius: 8px;
            text-transform: uppercase;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-hover:hover {
            background-color: #0056b3;
        }

        /* MOBILE MENU TOGGLE */
        .hamburger-menu {
            display: none; /* ซ่อนเมนูแฮมเบอร์เกอร์เริ่มต้น */
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger {
            width: 30px;
            height: 3px;
            background-color: var(--text-color);
            margin: 5px 0;
        }

        /* เมนูสำหรับมือถือ */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                flex-direction: column;
                position: absolute;
                top: var(--nav-height);
                right: 20px;
                background-color: #000;
                width: 200px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            }

            .nav-menu.active {
                display: flex;
            }

            .hamburger-menu {
                display: flex; /* แสดงเมนูแฮมเบอร์เกอร์ */
            }
        }

        /* ฟอร์มเข้าสู่ระบบ */
        .login-container {
            background-color: rgba(219, 214, 214, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin-top: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--main-color);
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            color: var(--text-color);
            background-color: var(--main-color);
            cursor: pointer;
        }

        .btn:hover {
            background-color: #176fb2;
        }

        .btn:active {
            background-color: #125b8e;
        }

        .loading {
            display: none;
            text-align: center;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        p a {
            color: var(--main-color); /* ใช้สีฟ้าของเว็บไซต์ */
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline; /* เพิ่มการขีดเส้นใต้เมื่อผู้ใช้ชี้เมาส์ */
        }
        .error-message {
    color: red;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
}

}

    </style>
</head>
<body>

<!-- เมนูนำทาง -->
<div class="nav-wrapper">
    <div class="container">
        <div class="nav">
            <a href="about.html" class="logo">
                <i class='bx bx-tv bx-tada main-color'></i>Suggestiffy_Za
            </a>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="login.php">หน้าแรก</a></li>
                <li><a href="login.php">ซีรีส์เกาหลี</a></li>
                <li><a href="login.php">อนิเมะ</a></li>
                <li><a href="login.php">รีวิวยอดนิยม</a></li>
                <li><a href="about.html">เกี่ยวกับเรา</a></li>
                <li>
                    <a href="login.php" class="btn btn-hover">
                        <span>เข้าสู่ระบบ</span>
                    </a>
                </li>
            </ul>
            <!-- MOBILE MENU TOGGLE -->
            <div class="hamburger-menu" id="hamburger-menu">
                <div class="hamburger"></div>
                <div class="hamburger"></div>
                <div class="hamburger"></div>
            </div>
        </div>
    </div>
</div>

<!-- ฟอร์มเข้าสู่ระบบ -->
<div class="login-container">
    <h1>เข้าสู่ระบบ</h1>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required />
        <input type="password" name="password" placeholder="รหัสผ่าน" required />
        <button type="submit" class="btn">เข้าสู่ระบบ</button>
    </form>

    <!-- เพิ่มข้อความแสดงข้อผิดพลาด -->
    <?php if (isset($_GET['error'])): ?>
        <p class="error-message"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>

    <p>ยังไม่มีบัญชี? <a href="register.html">สมัครสมาชิก</a></p>
</div>

<script>
    const hamburgerMenu = document.getElementById('hamburger-menu');
    const navMenu = document.getElementById('nav-menu');

    hamburgerMenu.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
</script>


</body>
</html>

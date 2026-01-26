<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user_id'];

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_series_id'])) {
    $deleteSeriesID = $_POST['delete_series_id'];
    $deleteStmt = $pdo->prepare("DELETE FROM watchlist WHERE user_id = ? AND series_id = ?");
    $deleteStmt->execute([$userID, $deleteSeriesID]);
    header("Location: watchlist.php"); // Refresh to reflect changes
    exit;
}

// Fetch user data
$stmt = $pdo->prepare("SELECT username, email, profile_picture FROM user WHERE user_id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$userName = htmlspecialchars($user['username']);
$userEmail = htmlspecialchars($user['email']);

// Process profile picture
$userProfilePictureSrc = !empty($user['profile_picture']) ? 
    'data:image/jpeg;base64,' . base64_encode($user['profile_picture']) : 
    './images/default-avatar.png';

// Fetch watchlist items
$stmt = $pdo->prepare("
    SELECT w.series_id, s.title, s.poster 
    FROM watchlist w 
    JOIN series_anime s ON w.series_id = s.series_id 
    WHERE w.user_id = ?
");
$stmt->execute([$userID]);
$watchlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการของคุณ</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
/* Reset CSS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --main-color: #203641;
    --text-color: #ffffff;
    --box-bg: #10171f;
    --logout-color: #ae0000;
    --logout-hover: #54191f;
    --nav-height: 60px;
}

/* Body Styling */
body {
    font-family: 'Kanit', sans-serif;
    background: var(--box-bg);
    color: var(--text-color);
    margin: 0;
}

/* Navigation Bar */
.nav-wrapper {
    background: var(--main-color);
    height: var(--nav-height);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    width: 100%;
    top: 0;
    left: 0;
    right: 0;
    position: fixed;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Logo Styling */
.logo {
    font-size: 24px;
    font-weight: 600;
    color: var(--text-color);
}

/* Navigation Menu */
.nav-menu {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-menu a {
    color: var(--text-color);
    font-size: 16px;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease-in-out;
}

.nav-menu a:hover {
    color: #ffcc00;
}

/* Dropdown Menu */
.dropdown {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dropdown-toggle img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.dropdown-menu {
    position: absolute;
    top: calc(var(--nav-height) + 5px);
    right: 20px;
    background: var(--main-color);
    width: 200px;
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
}

.dropdown-menu li a {
    text-decoration: none;
    color: var(--text-color);
}

.logout {
    background: var(--logout-color);
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 8px;
    font-weight: bold;
    display: block;
    text-decoration: none;
}

.logout:hover {
    background: var(--logout-hover);
}


        .watchlist-container {
            max-width: 800px;
            margin: 100px auto 0;
            padding: 20px;
            background: #333;
            border-radius: 10px;
        }

        .watchlist-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 20px;
            background: #fff;
            color: #333;
            border-radius: 5px;
            gap: 20px;
        }

        .watchlist-item img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        .watchlist-item h3 {
            margin: 0;
        }
        /* เพิ่ม Transition ให้ปุ่ม Dropdown */
.dropdown-menu {
    transition: all 0.3s ease, visibility 0.3s;
}

/* เพิ่ม Animation ตอนเลื่อนเมาส์ */
button[type="submit"]:hover {
    transform: scale(1.05); /* ขยายเล็กน้อยเมื่อ Hover */
}
.delete-btn {
    background-color: #ae0000;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
    font-family: 'Kanit', sans-serif;
}

.delete-btn:hover {
    background-color: #54191f;
    transform: scale(1.05);
    font-family: 'Kanit', sans-serif;
}

/* เพิ่ม Transition ให้ปุ่ม Dropdown */
.dropdown-menu {
    transition: all 0.3s ease, visibility 0.3s;
}

/* เพิ่ม Animation ตอนเลื่อนเมาส์ */
button[type="submit"]:hover {
    transform: scale(1.05); /* ขยายเล็กน้อยเมื่อ Hover */
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

/* ตั้งค่าลักษณะของ Dropdown Menu */
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

/* เมื่อเมนูเปิด */
.dropdown-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* ลักษณะของแต่ละรายการใน Dropdown */
.dropdown-menu li {
    list-style: none;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
}

/* เมื่อลากเมาส์ไปที่รายการ */
.dropdown-menu li:hover {
    background-color: #345667;
}

/* ลักษณะลิงก์ในรายการ */
.dropdown-menu li a {
    text-decoration: none;
    color: #ffffff;
    font-size: 14px;
    display: flex;
    align-items: center;
    width: 100%;
}

/* ไอคอนในลิงก์ */
.dropdown-menu li a i {
    font-size: 16px;
}

/* การปรับเปลี่ยนสีและขนาดเมื่อ hover ที่ปุ่ม dropdown */
.dropdown-toggle:hover {
    opacity: 0.8;
    transform: scale(1.05); /* ขยายขนาดเล็กน้อยเมื่อ hover */
}

/* ปุ่มออกจากระบบ */
.logout {
    text-decoration: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 4px;
}

.logout:hover {
    background-color: #f44336;
    color: #fff;
}

    </style>
</head>
<body>

<!-- Navigation -->
<div class="nav-wrapper">
    <a href="index.php" class="logo">Suggestiffy_Za</a>
    <ul class="nav-menu">
        <li><a href="index.php">หน้าแรก</a></li>
        <li><a href="index.php#korea-series-section">ซีรีส์เกาหลี</a></li>
        <li><a href="index.php#anime-section">อนิเมะ</a></li>
        <li><a href="index.php#special-movie-section">รีวิวยอดนิยม</a></li>
    </ul>

    <!-- User Dropdown -->
    <div class="dropdown">
        <div class="dropdown-toggle" onclick="toggleDropdown()">
            <img src="<?= $userProfilePictureSrc ?>" alt="User Avatar">
            <span id="username">ยินดีต้อนรับคุณ → <?php echo htmlspecialchars($userName); ?></span>
        </div>
        <ul class="dropdown-menu" id="dropdownMenu">
                <li><a href="profile.php"><i class="fas fa-user"></i> โปรไฟล์ </a></li>
                <li><a href="index.php#anime-section"><i class="fas fa-history"></i> Anime Section</a></li>
                <li><a href="index.php#korea-series-section"><i class="fas fa-gift"></i> K-Drama Section</a></li>
                <li><a href="index.php#special-movie-section"><i class="fas fa-box"></i> รีวิวที่ปักมุดไว้ 📌</a></li>
                <li><a href="watchlist.php">Watchlist</a></li>
                <a href="logout.php" class="logout">ออกจากระบบ</a>
            </ul>
    </div>
</div>

<!-- Watchlist Section -->
<div class="watchlist-container">
    <h1>ลิสต์รีวิวของคุณ</h1>

    <?php 
    // Map normalized titles to drama keys
    $dramaKeys = [
        "squidgame" => "squidGame",
        "marrymyhusband" => "marryMyHusband",
        "thejudgefromhell" => "theJudgeFromHell",
        "startup" => "startUp",
        "bluelock" => "blueLock",
        "dandadan" => "dandadan",
        "blueperiod" => "bluePeriod",
        "alchemyofsouls" => "alchemyOfSouls",
        "slamdunk" => "slamDunk",
        "lovelyrunner" => "Lovelyrunner",
        "moneyheistkorea" => "moneyHeistKorea",
        "whatswrongwithsecretarykim" => "whatWrongWithSecretaryKim",
        "chainsawman" => "chainsawMan",
        "demonslayer" => "demonslayer",
        "deathnote" => "deathNote",
        "dragonball" => "dragonball",
        "jujutsukaisen" => "jujutsukaisen",
        "myheroacademia" => "myHeroAcademia",
        "onepiece" => "onePiece",
        "weatheringwithyou" => "weatheringWithYou",
        "yourname" => "yourName",
        "queenoftears" => "QueenOfTears",
        "truebeauty" => "trueBeauty",
        "gyeongseongcreature" => "GyeongseongCreature",
        "penthouses" => "Penthouses",
        "drromantic" => "DrRomantic",
        "maskgirl" => "MaskGirl",
        "mrqueen" => "MrQueen",
        "bluebox" => "BlueBox",
        "familybychoice" => "FamilyByChoice",
        "yakuzafiance" => "YakuzaFiance",
        "whenthephonering" => "WhenThePhoneRing",
        "ronkamonohashi" => "RonKamonohashi",
        "thetaleofladyok" => "taleofladyok",
        "sololeveling" => "SoloLeveling"
    ];
    ?>
    
    <?php if ($watchlistItems): ?>
        <?php foreach ($watchlistItems as $item): ?>
            <?php 
            // Normalize the title: lowercase + remove spaces + only alphanumeric characters
            $normalizedTitle = strtolower(str_replace([' ', ':', "'", '.', '-'], '', $item['title']));
            $dramaKey = $dramaKeys[$normalizedTitle] ?? null; 
            ?>
    
            <?php if ($dramaKey): ?>
                <div class="watchlist-item">
                    <!-- Link ไปยังรีวิว -->
                    <a href="review.php?drama=<?= $dramaKey ?>" style="display: flex; align-items: center; text-decoration: none; color: inherit; gap: 20px;">
                        <img src="<?= htmlspecialchars($item['poster']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                    </a>
                    
                    <!-- ปุ่มลบ -->
                    <form method="POST" action="watchlist.php" style="margin-left: auto;">
                        <input type="hidden" name="delete_series_id" value="<?= htmlspecialchars($item['series_id']) ?>">
                        <button type="submit" class="delete-btn">ลบออกจากลิสต์</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Debugging message for unmatched titles -->
                <div class="debug-message" style="color: red;">
                    <p>ไม่พบคีย์สำหรับซีรีส์: <?= htmlspecialchars($item['title']) ?> (Normalized: <?= $normalizedTitle ?>)</p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>ลิสต์ของคุณว่างอยู่! เพิ่มซีรีส์ที่คุณชอบได้เลย</p>
    <?php endif; ?>
    
    






<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('active');
}

// Close the dropdown when clicking outside
document.addEventListener('click', (e) => {
    const dropdown = document.querySelector('.dropdown');
    const menu = document.getElementById('dropdownMenu');
    if (!dropdown.contains(e.target)) {
        menu.classList.remove('active');
    }
});
</script>

</body>
</html>

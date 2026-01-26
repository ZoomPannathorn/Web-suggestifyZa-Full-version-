<?php
session_start();
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : "Anonymous";

include 'db_connection.php'; // Include your database connection file
$userID = $_SESSION['user_id']; // Assuming user_id is stored in session
$stmt = $pdo->prepare("SELECT username, email, profile_picture FROM user WHERE user_id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    echo "User  not found.";
    exit;
}

$userName = $_SESSION['username']; // ดึงข้อมูลชื่อผู้ใช้จาก session
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
    <title>Suggestiffy_Za</title>
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

        body.light-mode {
            background-color: #f9f9f9;
            color: #121212;
        }

        /* Navigation Menu */
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

        /* Review Page Styles */
        .review-page {
            max-width: 1200px;
            margin: 100px auto;
            padding: 20px;
            background-color: #10171f;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        body.light-mode .review-page {
            background-color: #fff;
        }

        .drama-description {
            margin-top: 20px;
            font-size: 16px;
            color: #ddd;
        }

        .rating-section, .comment-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #2a2a2a;
            border-radius: 8px;
        }

        body.light-mode .rating-section, body.light-mode .comment-section {
            background-color: #f3f3f3;
        }

        #comments-list .comment {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        body.light-mode #comments-list .comment {
            background-color: #e9e9e9;
        }

        .comment img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment .content p {
            margin: 0;
        }

        .vote-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vote-buttons button {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        body.light-mode .vote-buttons button {
            background-color: #ddd;
            color: #000;
        }

        .tags span {
            display: inline-block;
            background-color: #444;
            color: #fff;
            padding: 3px 8px;
            border-radius: 5px;
            margin-right: 5px;
        }

        body.light-mode .tags span {
            background-color: #ddd;
            color: #000;
        }

        #comment-input {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #2a2a2a;
            color: #fff;
            resize: none;
            box-sizing: border-box;
        }

        body.light-mode #comment-input {
            background-color: #f3f3f3;
            color: #000;
        }

        #submit-comment {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        .poster-container {
        display: flex; /* Use this for flexbox centering */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically if needed */
        margin: 20px 0; /* Optional: Add some margin for spacing */
        }

        .poster {
        width: 420px;
        height: 594px;
        border-radius: 8px;
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

        /* กำหนดสีให้กับลิงก์ Trailer */
        #drama-trailer {
        color: #29b6f6; /* เปลี่ยนสีตามต้องการ */
        text-decoration: none; /* ไม่ให้มีขีดเส้นใต้ */
        }

        #drama-trailer:hover {
        color: #e1f5fe; /* สีเมื่อเมาส์ชี้ */
        text-decoration: underline; /* ขีดเส้นใต้เมื่อเมาส์ชี้ */
        }
        /* ขยายฟอนต์ชื่อเรื่อง */
        #drama-title {
            font-size: 32px !important; /* ขยายขนาดฟอนต์ */
            font-weight: bold; /* ตัวหนา */
            color: #FFFFFF; /* สีฟอนต์ */
            margin-bottom: 20px; /* เว้นระยะห่าง */
        }

                body {
            font-family: 'Kanit', sans-serif;
        }



        h3 {
            font-size: 20px;

        }

        #comment-input {
            font-family: 'Kanit', sans-serif;
            font-size: 16px;
            border: 1px solid #ccc;

        }

        #submit-comment {
            font-family: 'Kanit', sans-serif;
            font-size: 16px;
        }

        #submit-comment:hover {
            background-color: #0056b3;
        }


</style>


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
            <span>ยินดีต้อนรับคุณ → <?php echo htmlspecialchars($userName); ?></span>
            </div>
            <ul class="dropdown-menu" id="dropdownMenu">
                <li><a href="profile.php"><i class="fas fa-user"></i> โปรไฟล์ </a></li>
                <li><a href="index.php#anime-section"><i class="fas fa-history"></i> Anime Section </a></li>
                <li><a href="index.php#korea-series-section"><i class="fas fa-gift"></i> K-Drama Section </a></li>
                <li><a href="index.php#special-movie-section"><i class="fas fa-box"></i> รีวิวที่ปักมุดไว้ 📌</a></li>
                <a href="logout.php" class="logout">ออกจากระบบ</a>
            </ul>
        </div>
    </div>

    

    <!-- หน้ารีวิว -->
    <div class="review-page">
        <h1 id="drama-title"></h1>
        <div class="poster-container">
            <img id="drama-poster" class="poster" src="" alt="Drama Poster">
        </div>

        <!-- เรื่องย่อ -->
        <div id="drama-description" class="drama-description"></div>

        <!-- Genres -->
        <div class="drama-description" id="drama-genres">
            <p><strong>Genres:</strong></p>
        </div>

        <!-- Trailer -->
        <div class="drama-description">
            <p><strong>Trailer:</strong> <a href="#" target="_blank" id="drama-trailer">ดูตัวอย่าง</a></p>
        </div>

<!-- Stream Section -->
<div class="main-content">
    <h1 id="drama-title">Stream available on</h1>
    <a id="drama-url" href="" target="_blank">
        <img id="stream-logo" src="" alt="Streaming Logo" style="cursor: pointer; width: 200px;" />
    </a>
</div>

<!-- Drama Information Section -->
<div class="drama-info">
    <h2 id="drama-title"></h2>
    <p id="drama-description"></p>
    <p id="drama-genres"></p> <!-- Added Genre -->
    <p id="drama-duration"></p>
    <p id="drama-rating"></p>
   
</div>

<!-- Review from Admin -->
<div class="rating-section">
    <h3>รีวิวจากแอดมิน</h3>
    <p id="drama-review"></p>
</div>

<!-- Comment Section -->
<div class="comment-section">
    <h3>รีวิวทั้งหมด (<span id="total-comments">0</span>)</h3>
    <div id="comments-list"></div>

    <textarea id="comment-input" placeholder="แสดงความคิดเห็นของคุณ..."></textarea>
    <button id="submit-comment">ส่งรีวิว</button>
</div>

 <!-- Store the username in a hidden element -->
<span id="username" style="display:none;"><?php echo htmlspecialchars($userName); ?></span>

<script>

// Call the function to load the drama review when the page loads
window.onload = loadDramaReview;

// Data for different dramas
const dramaData = {
    squidGame: {
        title: "Squid Game",
        poster: "./images/review/squid-game.jpeg",
        trailer: "https://youtu.be/oqxAJKy0ii4?si=G6ebbZYQIZDCF2Ts",
        streamUrl: "https://www.netflix.com/title/81040344",
        rating: "8.0 ⭐⭐⭐⭐",
        duration: "10 episodes",
        description: "เรื่องราวสุดลึกลับของเหล่าผู้เข้าแข่งขันหลายร้อยชีวิตที่ประสบปัญหาทางการเงิน และต้องมาแข่งขันกันในเกมเด็กเล่นที่เต็มไปด้วยปริศนาลึกลับ เพื่อชิงเงินรางวัลมหาศาล บรรดาผู้เล่นตอบรับคำเชิญสุดประหลาดด้วยความหวังว่าจะคว้าทั้งชัยชนะและเงินรางวัลมหาศาล แต่โชคร้ายที่พวกเขาไม่รู้เลยว่าราคาของความพ่ายแพ้นั้นต้องจ่ายด้วยชีวิต จนเป็นผลให้เหตุการณ์บานปลายไปสู่การนองเลือด ด้วยจำนวนผู้เข้าแข่งขันถึง 456 คน จึงไม่น่าแปลกใจเลยที่มีผู้คนหลากหลายจากแทบทุกมิติของสังคม ไม่ว่าจะเป็น กีฮุน (อีจองแจ) พ่อหม้ายที่เผชิญความล้มเหลวทางธุรกิจและปัญหาหนี้สิน เพื่อนที่โตมาในละแวกเดียวกันของเขา ซังอู (พัคแฮซู) ซึ่งสอบติดมหาวิทยาลัยชื่อดังและได้ทำงานในบริษัทหลักทรัพย์ แต่กลับเจอทางตันในท้ายที่สุด และยังมี แซบยอก (จองโฮยอน) ผู้ลี้ภัยจากเกาหลีเหนือที่ดิ้นรนเพื่อให้ครอบครัวของเธอได้อยู่พร้อมหน้ากันอีกครั้ง รวมถึงตัวละครอื่น ๆ ที่น่าสนใจอย่าง ด็อกซู (ฮอซองแท) ที่เป็นนักเลง และ จุนโฮ (วีฮาจุน) ตำรวจที่ค้นพบเกมนี้เข้าระหว่างออกตามหาพี่ชายของเขาที่หายตัวไป แล้วคุณจะพบว่าตัวเองนั้นอดที่จะอินและลุ้นตามไปกับตัวละครทุก ๆ ตัวในเรื่องไม่ได้",
        genres: "Survival, Thriller, Drama, Action, Mystery,เอาชีวิตรอด, ระทึกขวัญ, ดราม่า, แอ็คชั่น, ลึกลับ",
        review: "ถ้าคุณชอบซีรี่ย์ที่ทั้งตื่นเต้นและมีปริศนาให้คิดตาม ก็ต้องดู 'Squid Game' เลยครับ! ผมบอกเลยว่าเรื่องนี้ไม่ธรรมดาแน่นอน! ซีรี่ย์เริ่มจากเกมที่ดูเหมือนจะเป็นแค่เกมเด็กๆ ธรรมดา แต่มันไม่ใช่เลย! ผู้เข้าแข่งขันต้องเอาชีวิตรอดจากเกมสุดโหดเพื่อคว้าเงินรางวัลมหาศาล แต่มันไม่ใช่แค่เกม เพราะทุกการตัดสินใจคือการเสี่ยงชีวิต! เรื่องราวเกี่ยวกับคนที่มีปัญหาการเงิน ถูกดึงเข้าไปเล่นในเกมที่ดูเหมือนสนุก แต่มันกลับเต็มไปด้วยความรุนแรงและอันตรายที่ซ่อนอยู่ ทุกๆ เกมที่พวกเขาต้องเล่นมีความเป็นชีวิตหรือความตายอยู่ข้างใน! และมันไม่ใช่แค่การเอาชีวิตรอด แต่ยังมีการแสดงความสัมพันธ์ระหว่างคนที่เต็มไปด้วยความซับซ้อน! 'Squid Game' ไม่ใช่แค่ซีรี่ย์ที่ดูจบแล้วลืม แต่จะทำให้คุณคิดถึงอะไรหลายๆ อย่าง เช่น การเอาตัวรอดในชีวิตจริง และข้อคิดที่ซ่อนอยู่ในทุกตอน! ถ้าอยากตื่นเต้น ลุ้นไปกับเกมที่ไม่เคยเห็นที่ไหนมาก่อน ผมบอกเลยว่า 'Squid Game' คือเรื่องที่คุณไม่ควรพลาด!",
        reviews: [
            { author: "Alice", text: "An intense and thrilling experience!" },
            { author: "Bob", text: "Loved the plot twists!" }
        ]
    },
    marryMyHusband: {
        title: "Marry My Husband",
        poster: "./images/review/Marry_My_Husband.jpg",
        trailer: "https://youtu.be/lfJGSxXf9Xg?si=XXEPYJxg9lBmXEV9",
        streamUrl: "https://www.primevideo.com/dp/amzn1.dv.gti.575a83f3-2ef4-4e54-89c7-7a0e15517d33?autoplay=0&ref_=atv_cf_strg_wb",
        rating: "7.8 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "Marry My Husband คือซีรีส์ที่เล่าเรื่องราวสุดสะเทือนใจของ คังจีวอน (รับบทโดย พัคมินยอง) หญิงสาวที่ป่วยเป็นมะเร็งระยะสุดท้าย และต้องเผชิญกับความสูญเสียในชีวิตที่ไม่เคยคาดคิดมาก่อน ชีวิตของเธอมีแค่สามี พัคมินฮวาน (รับบทโดย อีอีคยอง) และเพื่อนสนิท จองซูมิน (รับบทโดย ซงฮายุน) ซึ่งเป็นเหมือนครึ่งชีวิตของเธอ แต่กลับมีความสงสัยเกี่ยวกับความภักดีของสามี ที่เธอเคยคิดว่าเขากำลังนอกใจ และในช่วงที่ชีวิตของเธอกำลังจะจบลง เธอพบว่า สามี ของเธอได้แอบเก็บเงินประกันชีวิตของเธอ และ เพื่อนสนิท ที่เธอไว้ใจมากที่สุดกลับกลายเป็น ชู้รัก ของสามี เรื่องราวพลิกผันเมื่อ จีวอน เสียชีวิตอย่างโหดร้ายด้วยน้ำมือของคนทั้งสอง และได้กลับมาเกิดใหม่ในปี 2013 หรือ 10 ปีที่แล้วในอดีต ชีวิตที่ได้รับโอกาสอีกครั้งทำให้เธอสามารถทบทวนทุกสิ่งที่เธอเคยพลาดไป และพร้อมที่จะ เช็กบิล คนที่เคยทำร้ายเธออย่างไม่เคยคิด",
        review: "ในชีวิตที่เต็มไปด้วยความผิดหวังและความเสียใจ ผมเชื่อว่าหลายคนคงเคยคิดอยากย้อนกลับไปแก้ไขสิ่งต่างๆ ที่ทำให้ชีวิตของตัวเองเปลี่ยนไปตลอดกาล ถ้าผมมีโอกาสสักครั้งที่จะย้อนเวลากลับไป ผมจะทำอะไร? ‘Marry My Husband’ พาผมไปพบกับเรื่องราวของหญิงสาวคนหนึ่งที่ได้พบกับโอกาสนั้น การย้อนกลับไปในอดีต เพื่อทำการตัดสินใจครั้งสำคัญที่อาจจะเปลี่ยนแปลงชีวิตของเธอ และคนที่เธอรักทั้งหมด ผมไม่สามารถหยุดติดตามการตัดสินใจที่มีความรัก ความเจ็บปวด การเสียสละ และการหักมุมที่รอคอยทุกๆ ตอนอยู่ได้ ซีรีส์นี้ทำให้ผมต้องตั้งคำถามกับตัวเองว่า ถ้าผมเป็นเธอ ผมจะเลือกอะไรระหว่างความรักและความผิดพลาด? ความจริงที่ค่อยๆ ถูกเปิดเผยทำให้ผมลุ้นไปกับทุกการตัดสินใจ และบางครั้งก็ไม่สามารถคาดเดาผลลัพธ์ที่จะเกิดขึ้นได้ ‘Marry My Husband’ คือซีรีส์ที่จะทำให้หัวใจคุณเต้นแรงไปกับตัวละคร และคุณจะไม่สามารถหยุดดูได้จนกว่าจะถึงตอนสุดท้าย!",
        genres: "Fantasy, Drama, Romance, Revenge,แฟนตาซี, ดราม่า, โรแมนติก, และการแก้แค้น",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    blueLock: {
        title: "blue lock",
        poster: "./images/review/BLUELOCK.jpg",
        trailer: "https://youtu.be/1zm5jMcTlVA?si=YMKxPaaWRYky4EGn",
        streamUrl: "https://www.trueid.net/watch/th-th/series/N68GArLPJ8OY/ePll3KmggxAP?autoplay=true&utm_source=google&utm_medium=media_action",
        rating: "8.1 ⭐⭐⭐⭐",
        duration: "24 episodes",
        description: "'Blue Lock' เป็นอนิเมะที่สร้างความตื่นเต้นให้กับแฟนฟุตบอลทั่วโลก โดยนำเสนอเรื่องราวของ 'Yoichi Isagi' นักฟุตบอลหนุ่มที่มีความฝันอยากจะเป็นนักฟุตบอลทีมชาติญี่ปุ่น แต่เขากลับถูกเลือกให้เข้าร่วมโปรเจ็กต์สุดโต่งที่ชื่อว่า 'Blue Lock' ซึ่งเป็นโครงการที่ต้องการฝึกฝนนักฟุตบอลรุ่นใหม่ให้กลายเป็น 'สุดยอดกองหน้า' โดยใช้วิธีการฝึกฝนที่ดุดันและไม่มีใครเคยใช้มาก่อน นี่ไม่ใช่การฝึกเพื่อการเล่นทีม แต่เป็นการฝึกเพื่อให้กองหน้าทุกคนกลายเป็นคนที่ดีที่สุดเท่านั้น แม้แต่ความสัมพันธ์ระหว่างเพื่อนร่วมทีมยังต้องทิ้งไปเพื่อที่จะได้แชมป์เดียวเท่านั้น ความตึงเครียดและการแข่งขันที่สูงนี้จะทำให้ผู้ชมติดตามไม่อยากละสายตา!",
        genres: "Sports, Drama, Thriller, Psychological, แอ็คชั่น, กีฬา, ดราม่า, จิตวิทยา, ตื่นเต้น",
        review: "ถ้าคุณเป็นแฟนกีฬาและชอบเรื่องราวที่ไม่ธรรมดา ‘Blue Lock’ คือลิสต์ที่คุณต้องมีในสายตา! ผมบอกเลยว่ามันไม่ใช่แค่การแข่งฟุตบอลธรรมดาๆ เพราะ ‘Blue Lock’ คือการฝึกฝนนักฟุตบอลในแบบที่ไม่เคยเห็นมาก่อน มันไม่ได้เน้นแค่ทีมเวิร์ค แต่เน้นการพัฒนาทักษะส่วนตัวของกองหน้าคนเดียวเท่านั้น ทุกการแข่งขันใน 'Blue Lock' คือการเอาตัวรอดจากโครงการที่โหดร้ายนี้ และผู้ที่ไม่สามารถทำตามมาตรฐานจะถูกคัดออกไปในทุกๆ รอบ! ถ้าคุณชอบความตึงเครียดและการต่อสู้ที่ไม่รู้ว่าจะจบอย่างไร ผมแนะนำให้ดู ‘Blue Lock’ รับรองว่าไม่ผิดหวัง!",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    dandadan: {
        title: "Dandadan",
        poster: "./images/review/Dandadan.jpg",
        trailer: "https://youtu.be/0XJxfbN36Uw?si=cj9Wvu9U2u4PXx93",
        streamUrl: "https://www.bilibili.tv/th/play/2118582",
        rating: "9.0 ⭐⭐⭐⭐⭐",
        duration: "12 episodes",
        description: "‘Dandadan’ เป็นซีรีส์อนิเมะที่เต็มไปด้วยการผสมผสานระหว่างการผจญภัย, การต่อสู้, และองค์ประกอบของเหนือธรรมชาติที่สร้างความตื่นเต้นให้กับทุกการดู เรื่องราวเริ่มต้นจาก 'Momo' สาวน้อยที่บังเอิญไปเจอกับเหตุการณ์แปลกประหลาด ซึ่งทำให้เธอได้พบกับ 'Ken' หนุ่มที่มาพร้อมพลังพิเศษที่ไม่มีใครคาดคิด ทั้งคู่ต้องร่วมมือกันในการเผชิญหน้ากับสิ่งมีชีวิตเหนือธรรมชาติ ที่เต็มไปด้วยความท้าทายที่ยากจะคาดเดา ทุกการเดินทางเต็มไปด้วยการต่อสู้ที่เข้มข้นและการเปิดเผยความลับที่ทำให้ทั้งสองตัวละครต้องเผชิญหน้ากับตัวตนของตัวเองอย่างไม่เคยมีมาก่อน!",
        genres: "Action, Adventure, Supernatural, Sci-Fi, Comedy, แอ็คชั่น, ผจญภัย, เหนือธรรมชาติ, วิทยาศาสตร์, ตลก",
        review: "ถ้าคุณชอบเรื่องราวเกี่ยวกับผีและเอเลี่ยนที่เต็มไปด้วยการต่อสู้และความตื่นเต้น ‘Dandadan’ คืออนิเมะที่คุณไม่ควรพลาด! ผมสามารถบอกได้เลยว่าเรื่องนี้สนุกตั้งแต่เริ่มต้นจนจบ ตัวละครหลักทั้ง 'Momo' และ 'Ken' มีความพิเศษในตัวที่ไม่เหมือนใคร ทั้งคู่ต้องเผชิญหน้ากับทั้งผีและเอเลี่ยนที่อันตรายสุดๆ แต่ก็ยังคงมีมุมตลกและความเป็นมนุษย์ที่ทำให้เรื่องนี้มีความหลากหลายมากขึ้น ความสัมพันธ์ระหว่างตัวละครก็ดีมากๆ บอกได้คำเดียวว่าผมสนุกกับการดูเรื่องนี้มาก! หากคุณชอบอะไรที่มีความตื่นเต้นและไม่ธรรมดา ‘Dandadan’ คือคำตอบที่คุณต้องลองดู!",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    bluePeriod: {
        title: "blue period",
        poster: "./images/review/BluePeriod-Poster.jpg",
        trailer: "https://youtu.be/IV0-SYn3YuM?si=R8lpjyT0ILaRK4DU",
        streamUrl: "https://www.netflix.com/th/title/81318842#:~:text=Watch%20Blue%20Period%20%7C%20Netflix%20Official%20Site",
        rating: "9.5 ⭐⭐⭐⭐⭐",
        duration: "12 episodes",
        description: "'Blue Period' เล่าเรื่องราวของ 'Yatora Yaguchi' นักเรียนมัธยมปลายที่มีชีวิตค่อนข้างเรียบง่ายและไม่ได้รู้สึกว่ามีเป้าหมายอะไรในชีวิต จนกระทั่งเขาได้พบกับศิลปะและตัดสินใจที่จะก้าวเข้าสู่โลกแห่งการสร้างสรรค์ โดยเฉพาะการเรียนรู้การวาดภาพ เขาจึงเริ่มต้นการเดินทางใหม่ที่เต็มไปด้วยความท้าทายทั้งจากภายในและภายนอก โดยต้องต่อสู้กับข้อสงสัยของตัวเอง ความคาดหวังจากครอบครัว และการต้องพิสูจน์ให้ทุกคนเห็นว่าเขาสามารถเป็นศิลปินที่ยอดเยี่ยมได้ 'Blue Period' ไม่เพียงแค่แสดงให้เห็นถึงความสำคัญของการตามหาความฝัน แต่ยังสะท้อนความลำบากของคนที่ต้องเผชิญกับการค้นหาตัวตนในช่วงวัยรุ่น",
        genres: "Drama, Slice of Life, Art, Coming-of-Age, ดราม่า, ชีวิต, การเติบโต, ศิลปะ",
        review: "ถ้าคุณเคยรู้สึกว่าไม่มีเป้าหมายในชีวิตหรือไม่รู้ว่าจะทำอะไรในอนาคต ผมแนะนำให้ดู 'Blue Period' เลยครับ! ซีรี่ย์เรื่องนี้ทำให้ผมนึกถึงตอนที่ตัวเองยังไม่รู้ว่าความฝันของตัวเองคืออะไร และต้องไปค้นหามันอย่างเต็มที่ โดยเฉพาะในช่วงวัยรุ่นที่เต็มไปด้วยความสับสนและข้อสงสัย ตัวละคร 'Yatora Yaguchi' ถือเป็นตัวแทนของคนที่อยากลองทำสิ่งใหม่ๆ แต่ต้องเผชิญกับความยากลำบากในการตัดสินใจเลือกเส้นทางที่ถูกต้อง ซีรี่ย์นี้ไม่เพียงแค่เกี่ยวกับศิลปะ แต่ยังเกี่ยวกับการค้นหาตัวเองและการเติบโตในช่วงเวลาที่ยากลำบาก 'Blue Period' จึงไม่ใช่แค่อนิเมะที่ให้ความบันเทิง แต่ยังสอนให้เรารู้จักการต่อสู้เพื่อสิ่งที่เราอยากเป็น",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    alchemyOfSouls: {
        title: " Alchemy of souls",
        poster: "./images/review/Alchemy_of_Souls.jpg",
        trailer: "https://youtu.be/50kLmhGpt1s?si=2Cd_Za6qnMG9xGg3",
        streamUrl: "https://www.netflix.com/watch/81517188?source=35",
        rating: "8.0 ⭐⭐⭐⭐",
        duration: "30 episodes",
        description: "'Alchemy of Souls' เป็นซีรี่ย์แฟนตาซีที่เล่าเรื่องราวในโลกแห่งการเปลี่ยนแปลงจิตใจและการแลกเปลี่ยนวิญญาณ ผู้ที่มีความสามารถในการใช้เวทมนตร์ต้องเผชิญหน้ากับคำสาปที่ทำให้วิญญาณของพวกเขาถูกย้ายไปยังร่างกายของคนอื่น โดยมีการผจญภัยของตัวละครหลัก 'Jang Wook' และ 'Mu Deok' ที่จะต้องร่วมมือกันเพื่อค้นหาความจริงและพยายามทำลายคำสาปนั้น โดยมีการต่อสู้กับศัตรูที่ต้องการใช้พลังเวทมนตร์เพื่อประโยชน์ส่วนตัว ในขณะเดียวกันก็มีการต่อสู้ภายในที่เกี่ยวกับการค้นหาตัวตนและความรักที่ซับซ้อน",
        genres: "Fantasy, Action, Drama, Romance, แฟนตาซี, แอ็คชั่น, ดราม่า, โรแมนติก",
        review: "ถ้าคุณเป็นแฟนของซีรี่ย์แฟนตาซีที่เต็มไปด้วยการผจญภัยและเวทมนตร์ ผมขอแนะนำ 'Alchemy of Souls' ให้ลองดูครับ! ซีรี่ย์เรื่องนี้เต็มไปด้วยการหักมุมที่คาดไม่ถึง และมีตัวละครที่ซับซ้อนและมีพัฒนาการที่น่าสนใจ ตัวละครหลักอย่าง 'Jang Wook' และ 'Mu Deok' ต้องเผชิญกับอุปสรรคทั้งทางจิตใจและทางกายภาพ แต่พวกเขาก็ไม่ยอมแพ้และร่วมมือกันเพื่อค้นหาคำตอบ ซีรี่ย์นี้มีความผสมผสานระหว่างแอ็คชั่นและโรแมนติกที่ทำให้ไม่สามารถละสายตาจากหน้าจอได้เลย โดยเฉพาะการเปิดเผยเรื่องราวเกี่ยวกับวิญญาณและคำสาปที่ไม่ธรรมดา 'Alchemy of Souls' ไม่เพียงแค่สนุก แต่ยังมีองค์ประกอบที่ช่วยกระตุ้นให้คิดตามตลอดทั้งเรื่อง",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    slamDunk: {
        title: "slam dunk",
        poster: "./images/review/MV5BMDA1ZDFlZGUtM2M4Ny00M2Q2LWE0MzktZmU2NzNkZGFlZWE2XkEyXkFqcGc@._V1_.jpg",
        trailer: "https://youtu.be/Zdvl1MhwSfc?si=d5wz3ItQZAe__oat",
        streamUrl: "https://www.netflix.com/title/70024218",
        rating: "8.7 ⭐⭐⭐⭐",
        duration: "101 episodes",
        description: "'Slam Dunk' เป็นซีรี่ย์อนิเมะที่เต็มไปด้วยพลังแห่งการต่อสู้และการเรียนรู้ของชีวิต โดยเล่าถึงเรื่องราวของ 'Hanamichi Sakuragi' นักเรียนมัธยมที่ไม่เคยสนใจบาสเก็ตบอลมาก่อน จนกระทั่งเขาตกหลุมรักสาวคนหนึ่งที่เป็นนักบาสเก็ตบอล เมื่อได้เข้าไปลองเล่นบาสเก็ตบอล เขาก็พบว่ามันคือกีฬาที่เขาชื่นชอบมากที่สุด เรื่องราวจะติดตามการพัฒนาและความพยายามของเขาที่จะกลายเป็นนักบาสที่ดีที่สุดในทีม โรงเรียน Shuho ที่เต็มไปด้วยคู่แข่งที่เก่งกาจทั้งในและนอกสนาม 'Slam Dunk' ไม่เพียงแค่เป็นเรื่องของกีฬาบาสเก็ตบอล แต่ยังสอนเรื่องมิตรภาพ, ความพยายาม, และการเติบโตทางจิตใจของแต่ละตัวละคร",
        genres: "Sports, Comedy, Drama, Teen, กีฬาบาสเก็ตบอล, ตลก, ดราม่า, วัยรุ่น",
        review: "ถ้าคุณเป็นแฟนกีฬาบาสเก็ตบอล หรือชอบดูเรื่องราวที่เกี่ยวกับความพยายามและการเอาชนะตัวเอง ผมขอแนะนำให้ดู 'Slam Dunk' เลยครับ! ซีรี่ย์นี้ไม่ได้มีแค่การแข่งขันกีฬา แต่ยังเต็มไปด้วยบทเรียนชีวิตที่มีความหมาย ตัวละครแต่ละตัวมีพัฒนาการที่น่าสนใจ รวมถึง 'Hanamichi Sakuragi' ที่จากเด็กที่ไม่มีประสบการณ์เลยจนกลายเป็นนักบาสที่เก่งขึ้นเรื่อยๆ ผ่านความพยายามและมิตรภาพที่ได้จากทีมงาน การฝึกซ้อมที่หนักหน่วง และการแข่งขันที่เต็มไปด้วยความตื่นเต้น ทำให้ 'Slam Dunk' กลายเป็นอนิเมะที่มีความหมายและสนุกมากๆ ใครที่ชอบการเติบโตทางอารมณ์ และการผจญภัยในการเรียนรู้ ก็ต้องดูเรื่องนี้!",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    Lovelyrunner: {
        title: "Lovely runner",
        poster: "./images/review/Lovely_Runner-p1.jpg",
        trailer: "https://youtu.be/5kk0dYa8Ccc?si=dTqjQ1aBkLqtAMVa",
        streamUrl: "https://www.viu.com/ott/th/en/vod/2342098/Lovely-Runner",
        rating: "8.6 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "ในชีวิตแสนหมดอาลัยตายอยาก ท็อปสตาร์อย่างซอนแจคือความหวังเดียวสำหรับอิมซล ถึงอย่างนั้น วันหนึ่งเธอก็ได้ยินข่าวสะเทือนใจว่าซอนแจเสียชีวิตลงอย่างน่าเศร้า โลกของเธอพลิกกลับตาลปัตรแล้วเธอก็ตื่นขึ้นมาทั้งน้ำตา แต่กลับพบว่าตัวเองอยู่ในอดีตเมื่อ 15 ปีที่แล้วในตอนที่ซอนแจยังมีชีวิตอยู่ ซลคว้าโอกาสนี้ไว้และตั้งใจว่าจะช่วยชีวิตซอนแจอย่างสุดฝีมือ ขณะที่ในอดีตที่ว่านี้ ซอนแจเป็นนักเรียนที่ทุ่มเทให้กับการว่ายน้ำเพียงอย่างเดียว แต่ซลที่ไม่รู้จักเขามาก่อน จนกระทั่งเมื่อวานนี้ เธอรู้ชื่อของเขาได้ยังไงกันนะ? เรื่องราวของ 'อิมโซล' (คิมฮเยยุน) เป็นแฟนคลับของ 'รยูซอนแจ' (บยอนอูซอก) ไอดอลตัวท็อป คนที่ทำให้เธอมีกำลังใจในการใช้ชีวิต แต่แล้ววันนึงเธอกลับได้ทราบข่าวว่าซอนแจเสียชีวิต เธอเศร้าเสียใจอย่างหนัก แต่จู่ๆ เธอก็ได้เดินทางย้อนเวลากลับไปในอดีต 15 ปีก่อน และได้พบกับซอนเจในวัย 19 เธอจึงพยายามทำทุกวิถีทางเพื่อเปลี่ยนแปลงโชคชะตาพวกเขาให้จงได้",
        genres: "โรแมนติก, แฟนตาซี, ข้ามเวลา, ดราม่า",
        review: "ถ้าคุณชอบเรื่องราวการย้อนเวลาและความสัมพันธ์ที่ซับซ้อน 'Lovely Runner: ข้ามเวลามาเซฟเมน' คือซีรีส์ที่ไม่ควรพลาด! นี่คือซีรีส์ที่ผสมผสานการผจญภัยและการย้อนเวลาได้อย่างลงตัว ทุกตอนทำให้หัวใจคุณเต้นรัวไปกับความพยายามของตัวละครหลักในการเปลี่ยนแปลงอนาคตที่เธอรู้ว่าจะเกิดขึ้นอย่างไร! บอกเลยว่าถ้าคุณไม่ดูซีรีส์นี้ ถือว่าพลาดมาก! ต้องดูให้ได้ก่อนตาย!",
        reviews: [
            { author: "Charlie", text: "So heartwarming!" },
            { author: "Diana", text: "The chemistry between the leads is amazing!" }
        ]
    },
    
    theJudgeFromHell: {
    title: "The Judge from Hell",
    poster: "./images/review/The_Judge_from_Hell_poster.jpeg",
    trailer: "https://www.youtube.com/watch?v=uoYhbzAYZjg",
    streamUrl: "https://www.disneyplus.com/en-gb/series/the-judge-from-hell/7Mq13l6hfj1l",
    rating: "7.8 ⭐⭐⭐⭐",
    duration: "14 episodes",
    description: "บอกเล่าเรื่องราวของ บิทนา (รับบทโดย พัคชินฮเย) ผู้พิพากษาซึ่งถูกขนานนามว่ามักจะตัดสินโทษสถานเบาให้กับจำเลย โดยเกิดเป็นกระแสวิจารณ์อย่างหนาหูถึงการกระทำของเธอที่เอาแต่ผ่อนปรนให้ผู้กระทำผิด ทว่าในความจริงแล้วกลับไม่มีใครรู้เลยว่า บิทนา กำลังถูกปีศาจที่ถูกส่งมายังโลกสิงสู่ พร้อมกับภารกิจชั่วร้ายในการลากผู้ต้องหาทั้งสิบกลับลงสู่ปรโลก อย่างไรก็ตาม ความทะเยอทะยานอันไร้ที่สิ้นสุดและยากที่จะหยุดได้ของเธออาจถึงจุดพลิกผันอีกครั้ง เมื่อเธอเริ่มมีใจให้กับ นักสืบดาอน (รับบทโดย คิมแจยอง) สุดท้ายแล้ว ปีศาจจะสามารถครอบงำเธอเพื่อบรรลุภารกิจสุดชั่วร้ายได้หรือไม่ หรือความพยายามทุกอย่างจะต้องพังทลายไปโดยแลกมาซึ่งนักสืบในฝันของเธอ",
    genres: "ดราม่า, แอคชั่น, ทริลเลอร์",
    review: "ถ้าคุณชอบซีรีส์ที่เต็มไปด้วยความตึงเครียดและการปะทะทางกฎหมาย 'The Judge from Hell' คือสิ่งที่คุณไม่ควรพลาด! ซีรีส์นี้เต็มไปด้วยการหักมุมและการพัฒนาเรื่องราวที่ไม่สามารถคาดเดาได้ ทำให้ทุกตอนตื่นเต้นและท้าทายความคิดของคุณ! มันคือการผสมผสานระหว่างดราม่าและทริลเลอร์ที่คุณต้องดูให้ได้ก่อนตาย!",
    reviews: [
        { author: "Charlie", text: "So intense and thrilling!" },
        { author: "Diana", text: "A must-watch for fans of courtroom drama!" }
    ]
    },
    startUp: {
        title: "Start Up",
        poster: "./images/review/startup_poster.jpg",
        trailer: "https://www.youtube.com/watch?v=9rS_wJRPpEA",
        streamUrl: "https://www.netflix.com/title/81009940",
        rating: "8.0 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "Start Up เป็นซีรีส์ที่บอกเล่าเรื่องราวของกลุ่มคนที่พยายามสร้างสตาร์ทอัพในโลกแห่งเทคโนโลยี โดยมีเรื่องราวความรัก, การแข่งขัน, และการเติบโตของตัวละครที่เข้มข้น ซีรีส์นี้สำรวจโลกของการเริ่มต้นธุรกิจและการสร้างความฝันในอุตสาหกรรมสมัยใหม่.",
        genres: "ดราม่า, โรแมนติก, คอมเมดี้, Drama, Romance, Comedy",
        review: "ซีรีส์ที่เต็มไปด้วยความท้าทายในการสร้างสตาร์ทอัพ มาพร้อมกับความโรแมนติกที่น่าติดตามและการเดินทางที่ทำให้คุณรู้สึกถึงแรงบันดาลใจในการไล่ตามความฝัน!",
        reviews: [
            { author: "Anna", text: "This show is both inspiring and heartwarming!" },
            { author: "Ben", text: "The characters are so relatable and real!" }
        ]
    },
    moneyHeistKorea: {
        title: "Money Heist: Korea",
        poster: "./images/review/money_heist_korea_poster.jpg",
        trailer: "https://www.youtube.com/watch?v=euSF7lpdm-M",
        streamUrl: "https://www.netflix.com/title/80997343",
        rating: "5.8 ⭐⭐",
        duration: "12 episodes",
        description: "Money Heist: Korea เป็นซีรีส์เกาหลีที่รีเมคจากซีรีส์ชื่อดัง 'Money Heist' โดยการปรับเปลี่ยนเรื่องราวให้เข้ากับบริบทของเกาหลีใต้ ซีรีส์ติดตามกลุ่มโจรที่สวมหน้ากากและมีการวางแผนปล้นอย่างชาญฉลาด โดยมีผู้นำกลุ่ม 'The Professor' ที่นำพาทีมเข้าไปในสถานที่สำคัญทางการเงินและพยายามขโมยเงินจำนวนมหาศาลในระยะเวลาจำกัด.Money Heist: Korea Joint Economic Area ทรชนคนปล้นโลก: เกาหลีเดือด ซีรีส์เรื่องล่าสุดของ Netflix ที่รีเมคมาจากต้นฉบับเวอร์ชั่นสเปนอย่าง La casa de papel และปล่อยออกมาหลังจากต้นฉบับปิดฉากไม่นานนั้น ดูจะต้องแบกรับความคาดหวังของผู้ชมที่ต้องทำให้สนุกเทียบเท่ากับต้นฉบับ ในขณะที่ต้องนำเสนออะไรบางอย่างที่แตกต่างออกไปจากเดิมมากพอที่จะทำให้มันโดดเด่นด้วยตัวของมันเองด้วยเช่นกัน ในเวอร์ชั่นนี้ซีรีส์คงเส้นเรื่องเดิมแทบจะเหมือนกับเป๊ะ ต่างกันที่รายละเอียดเล็กน้อยแต่ Money Heist: Korea Joint Economic Area ก็สร้างความแตกต่างโดยเพิ่มเอกลักษณ์ความเป็นเกาหลีเข้าไปในบริบท ด้วยเรื่องราวของ ทีมโจรกรรมทั้งหมดแปดชีวิตซึ่งมาร่วมงานกันด้วยนามแฝงที่ตั้งจากเมืองของประเทศต่าง ๆ ภายใต้การนำของ ศาสตราจารย์ และมารวมตัวก่อการปล้นโรงกษาปณ์ในเขตเศรษฐกิจร่วมที่จัดตั้งขึ้นใหม่ระหว่างเกาหลีเหนือและเกาหลีใต้",
        genres: "แอคชั่น, อาชญากรรม, ทริลเลอร์, Action, Crime, Thriller",
        review: "ใครที่กำลังตัดสินใจว่าจะดูดีไหม ขอบอกว่าถ้าคุณยังไม่เคยดู Money Heist มาก่อน การเริ่มด้วยเวอร์ชันเกาหลีสนุกเลย ทั้งยังน่าจะได้รสชาติแบบเอเชียที่คุ้นเคย ส่วนใครที่เป็นแฟนประจำมาตั้งแต่เวอร์ชันสเปนแล้ว ก็อยากแนะนำให้ดู เพราะเกาหลีเลือกใช้โครงเรื่องเดิมมาเล่าใหม่อย่างชาญฉลาด ปรับเปลี่ยนเรื่องราวให้สอดคล้องกับความพยายามรวมประเทศเกาหลีเหนือ-ใต้, พื้นฐานวัฒนธรรมเอเชียที่เน้นเรื่องสายใยความรัก-ครอบครัว, เกมการเมืองเข้มๆ และยังเต็มไปด้วยฉากแอ็กชันสุดลุ้นระทึกในแบบที่ไม่แพ้เวอร์ชันต้นฉบับ ",
        reviews: [
            { author: "Tom", text: "The suspense is real! Can't wait for the next season." },
            { author: "Sarah", text: "This Korean version adds a unique twist to the original." }
        ]
    },
    whatWrongWithSecretaryKim: {
        title: "What's Wrong with Secretary Kim",
        poster: "./images/review/whats_wrong_with_secretary_kim_poster.jpg",
        trailer: "https://www.youtube.com/watch?v=roLQoJc6ABI&pp=ygUbd2hhdCB3cm9uZyB0byBzZWNyZXRhcnkga2lt",
        streamUrl: "https://www.viu.com/ott/th/th/vod/92961/Whats-Wrong-With-Secretary-Kim",
        rating: "8.0 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "What's Wrong with Secretary Kim เป็นซีรีส์เกาหลีที่เล่าเรื่องราวของ คิมมยองจู (รับบทโดย พัคซอจิน) ประธานบริษัทหนุ่มสุดหล่อที่มีทุกอย่างพร้อม แต่กลับพบว่า เขาไม่สามารถทำงานได้โดยปราศจากการช่วยเหลือจากเลขานุการสาวของเขาอย่าง คิมไมรี (รับบทโดย พัคมินยอง) ซึ่งเป็นผู้ที่ทำงานให้เขามา 9 ปี และอยู่เคียงข้างเขาตลอด แต่ไมรีกลับประกาศลาออกจากงานที่เธอทำมานานและทำให้คิมมยองจูต้องหาคำตอบว่าเกิดอะไรขึ้น.",
        genres: "โรแมนติก, คอมเมดี้, ดราม่า, Romance, Comedy, Drama",
        review: "ซีรีส์ที่น่ารักและตลกกับการผสมผสานระหว่างความโรแมนติกและความดราม่าอย่างลงตัว! ความสัมพันธ์ระหว่างพระเอกและนางเอกที่เต็มไปด้วยการพัฒนาของตัวละครทำให้เรื่องนี้เป็นซีรีส์ที่คุณต้องดูให้ได้.",
        reviews: [
            { author: "Emily", text: "The chemistry between the leads is amazing!" },
            { author: "John", text: "A charming and heartwarming drama. Highly recommend!" }
        ]
    },
    chainsawMan: {
        title: "Chainsaw Man",
        poster: "./images/review/chainsawman_poster.jpg",
        trailer: "https://www.youtube.com/watch?v=2uvMx3Rv82w",
        streamUrl: "https://movie.trueid.net/th-th/series/X436vlYQBK3J/R2bGGoPNvKG6/zNg8BVQ0ny8D/JVxjqvlAdppb",
        rating: "8.3 ⭐⭐⭐⭐",
        duration: "12 episodes",
        description: "Chainsaw Man เป็นอนิเมะที่ดัดแปลงจากมังงะชื่อดังเล่าเรื่องราวของ เดนจิ (Denji) เด็กหนุ่มที่ทำงานเป็นนักล่าปีศาจเพื่อชำระหนี้ให้กับพ่อที่เหลือไว้ หลังจากที่เขาตายไป เขาได้ผสมร่างกับปีศาจ Chainsaw และกลายเป็น Chainsaw Man ผู้ซึ่งมีพลังในการล่าปีศาจเพื่อช่วยเหลือสังคม โดยในระหว่างการทำงานเขาได้พบกับความท้าทายและการเผชิญหน้ากับปีศาจที่มีอำนาจมากมาย.",
        genres: "แอคชั่น, ดราม่า, ผจญภัย, Action, Drama, Adventure",
        review: "อนิเมะที่เต็มไปด้วยความตื่นเต้นและการต่อสู้ที่รุนแรง! หากคุณเป็นแฟนอนิเมะที่มีเรื่องราวที่เต็มไปด้วยความโหดร้ายและการต่อสู้ที่สะใจ 'Chainsaw Man' คือสิ่งที่คุณต้องไม่พลาด!",
        reviews: [
            { author: "Mike", text: "One of the best action-packed anime I've seen!" },
            { author: "Sophia", text: "The character development is incredible, and the plot keeps you on the edge of your seat!" }
        ]
    },
    demonslayer: {
        title: "Demon slayer",
        poster: "./images/review/demonslayer.jpg",
        trailer: "https://youtu.be/wyiZWYMilgk?si=U5eX_Tg-RcSK1FiI",
        streamUrl: "https://www.netflix.com/title/81091393",
        rating: "8.5 ⭐⭐⭐⭐",
        duration: "55 episodes",
        description: "Demon Slayer เป็นเรื่องราวของเด็กหนุ่มชื่อ ทานจิโร่ Kamado (Tanjiro Kamado) ที่ต้องกลายเป็นนักล่าปีศาจหลังจากครอบครัวของเขาถูกฆ่าจากปีศาจ และน้องสาวของเขา เนซึโกะ (Nezuko) กลายเป็นปีศาจ ทานจิโร่ต้องเผชิญหน้ากับศัตรูที่อันตรายและค้นหาวิธีที่จะรักษาน้องสาวของเขา พร้อมทั้งเผชิญกับการทดสอบตัวเองในโลกที่เต็มไปด้วยปีศาจ.",
        genres: "แอ็คชั่น, แฟนตาซี, ดราม่า, Action, Fantasy, Drama",
        review: "Demon Slayer เป็นอนิเมะที่เต็มไปด้วยฉากแอ็คชั่นสุดตระการตาและอารมณ์ลึกซึ้งที่สะท้อนถึงความเสียสละและความรักในครอบครัว การต่อสู้กับปีศาจเป็นเพียงส่วนหนึ่งของการเดินทางที่ยิ่งใหญ่และยากลำบากของตัวละคร!",
        reviews: [
            { author: "Alaaice", text: "An intense and thrilling experience!" },
            { author: "Baaob", text: "Loved the plot twists!" }
        ]
    },
    deathNote: {
        title: "Death Note",
        poster: "./images/review/deathnote_poster.jpg",
        trailer: "https://www.youtube.com/watch?v=NlJZ-YgAt-c",
        streamUrl: "https://www.netflix.com/title/70204970",
        rating: "8.9 ⭐⭐⭐⭐",
        duration: "37 episodes",
        description: "Death Note เป็นอนิเมะที่ดัดแปลงจากมังงะชื่อดังเกี่ยวกับ ไลท์ ยางามิ (Light Yagami) นักเรียนมัธยมปลายที่ค้นพบสมุดโน้ตลึกลับชื่อ 'Death Note' ซึ่งสามารถฆ่าคนได้หากเขียนชื่อของคนในสมุดนั้น พร้อมกับเหตุการณ์การต่อสู้ทางปัญญาระหว่างเขากับนักสืบชื่อดังที่รู้จักในชื่อ 'L' เรื่องราวเต็มไปด้วยการตัดสินใจที่ยากลำบากและผลกระทบของการใช้พลังที่ไม่สามารถย้อนกลับได้.",
        genres: "ดราม่า, จิตวิทยา, สืบสวน, Drama, Psychological, Mystery",
        review: "Death Note คืออนิเมะที่เต็มไปด้วยการพลิกผันที่ไม่สามารถคาดเดาได้! ด้วยการต่อสู้ทางปัญญาที่ตึงเครียดและการสำรวจจริยธรรมในการใช้พลังที่ยิ่งใหญ่ มันคือนิยายที่ทำให้คุณต้องตั้งคำถามกับความถูกต้องของการกระทำของตัวละคร!",
        reviews: [
            { author: "James", text: "A masterpiece of psychological warfare!" },
            { author: "Lucy", text: "An intense battle of wits and morals!" }
        ]
    },
    dragonball: {
        title: "Dragon Ball",
        poster: "./images/review/Dragon_Ball.jpg",
        trailer: "https://youtu.be/CYcrmsdZuyw?si=8RlnD7jQfCEc_Xho",
        streamUrl: "https://www.netflix.com/jp-en/title/70295183",
        rating: "8.6 ⭐⭐⭐⭐",
        duration: "153 episodes",
        description: "Dragon Ball เป็นเรื่องราวการผจญภัยของโงกุน (Goku) เด็กหนุ่มจากดาวไซย่า ที่มาพร้อมกับพลังพิเศษและความสามารถในการต่อสู้ที่ไม่ธรรมดา เขาออกเดินทางเพื่อค้นหา 'ลูกแก้วมังกร' ที่สามารถขอพรได้ โดยมีเพื่อนและศัตรูมากมายในระหว่างการผจญภัย ซึ่งเต็มไปด้วยการฝึกฝน, การต่อสู้ และการเผชิญหน้ากับศัตรูที่มีพลังมหาศาล.",
        genres: "แอ็คชั่น, ผจญภัย, มิตรภาพ, Action, Adventure, Friendship",
        review: "Dragon Ball คืออนิเมะระดับตำนานที่ได้สร้างแรงบันดาลใจให้กับแฟนๆ ทั่วโลก ด้วยการต่อสู้ที่น่าตื่นเต้นและการผจญภัยที่ไม่รู้จบ มันเป็นเรื่องราวเกี่ยวกับการเติบโตและการก้าวข้ามขีดจำกัดของตัวเอง.",
        reviews: [
            { author: "John", text: "A classic anime that has stood the test of time!" },
            { author: "Sophia", text: "Full of action, friendship, and unforgettable battles!" }
        ]
    },
    jujutsukaisen: {
        title: "Jujutsu Kaisen",
        poster: "./images/review/jujutsukaisen.jpg",
        trailer: "https://youtu.be/Pm-wNmS9RGI?si=qCw9HRbT3SFHKJmP",
        streamUrl: "https://www.netflix.com/title/81278456",
        rating: "8.5 ⭐⭐⭐⭐",
        duration: "24 episodes",
        description: "Jujutsu Kaisen เล่าเรื่องราวของยาจิ (Yuji Itadori) ที่ได้รับพลังจากวิญญาณปีศาจโดยไม่ตั้งใจ หลังจากที่เขากิน 'นิ้วของสุมุ (Sukuna)' เขาต้องร่วมกับนักเรียนจากโรงเรียนวิญญาณศาสตร์ (Jujutsu Sorcery School) เพื่อต่อสู้กับปีศาจและปกป้องโลก.",
        genres: "แอ็คชั่น, ดราม่า, ผจญภัย, Action, Drama, Adventure",
        review: "Jujutsu Kaisen คืออนิเมะที่ผสมผสานการต่อสู้ที่ดุเดือดและการพัฒนาตัวละครที่น่าสนใจ ผลงานที่เต็มไปด้วยแอ็คชั่นและมีความลึกซึ้งในเรื่องของพลังและจิตวิญญาณ.",
        reviews: [
            { author: "John", text: "A thrilling battle of sorcerers with unique characters and epic fights!" },
            { author: "Sophia", text: "Every episode is packed with adrenaline and heart-pounding action!" }
        ]
    },
    myheroacademy: {
        title: "My Hero Academia",
        poster: "./images/review/myhero.jpg",
        trailer: "https://youtu.be/yZwRYsDNEQ8?si=j13pMnzvUuG12c5u",
        streamUrl: "https://www.netflix.com/th-en/title/80135674",
        rating: "8.2 ⭐⭐⭐⭐",
        duration: "168 episodes",
        description: "My Hero Academia เล่าเรื่องราวของ Midoriya Izuku เด็กหนุ่มที่เกิดมาโดยไม่มีพลังพิเศษ แต่เขามีความฝันที่จะเป็นฮีโร่ เขาได้รับโอกาสในการฝึกฝนและได้รับพลังจากฮีโร่ที่ยิ่งใหญ่ที่สุด All Might การต่อสู้และการผจญภัยของเขากับเพื่อนๆ เพื่อปกป้องโลกจากอาชญากรรม.",
        genres: "แอ็คชั่น, ซูเปอร์ฮีโร่, ดราม่า, Action, Superhero, Drama",
        review: "My Hero Academia คือการผสมผสานที่ยอดเยี่ยมของความเป็นฮีโร่และการพัฒนาตัวละครที่สมจริง นี่คือการเดินทางของเด็กหนุ่มที่มีจิตใจกล้าแกร่งและความพยายามในการเป็นฮีโร่.",
        reviews: [
            { author: "James", text: "An inspiring story about courage, friendship, and fighting for justice!" },
            { author: "Emily", text: "A perfect blend of superhero action with emotional moments and incredible character growth!" }
        ]
    },
    onePiece: {
        title: "One Piece",
        poster: "./images/review/onePiece.jpg",
        trailer: "https://youtu.be/MCb13lbVGE0?si=yyGmgAPjI2TcROdG",
        streamUrl: "https://www.netflix.com/title/80107103",
        rating: "9.0 ⭐⭐⭐⭐⭐",
        duration: "1088 episodes ",
        description: "One Piece เล่าเรื่องราวการผจญภัยของมังกี้ ดี. ลูฟี่ (Monkey D. Luffy) ที่ฝันอยากเป็นโจรสลัดที่ยิ่งใหญ่ที่สุด เขาตั้งทีมและเดินทางไปทั่วโลกเพื่อค้นหา 'One Piece' สมบัติที่เชื่อว่าจะทำให้เขากลายเป็นราชาโจรสลัด การผจญภัยของเขาเต็มไปด้วยเพื่อนใหม่, ศัตรูที่แข็งแกร่ง, และการต่อสู้ที่ไม่มีที่สิ้นสุด.",
        genres: "ผจญภัย, แอ็คชั่น, คอมเมดี้, Adventure, Action, Comedy",
        review: "One Piece คืออนิเมะที่ไม่ใช่แค่เรื่องของการผจญภัย แต่ยังเต็มไปด้วยมิตรภาพ ความฝัน และการต่อสู้ที่ไม่เคยหยุดนิ่ง.",
        reviews: [
            { author: "Chris", text: "A true adventure with heart, humor, and some of the most unforgettable characters!" },
            { author: "Olivia", text: "An epic journey that has lasted for decades – One Piece is a masterpiece!" }
        ]
    },
    weatheringWithYou: {
        title: "Weathering with You",
        poster: "./images/review/weatheringWithYou.jpg",
        trailer: "https://youtu.be/Q6iK6DjV_iE?si=a5loK3a-PBgDcwK0",
        streamUrl: "https://www.netflix.com/jp-en/title/81172898",
        rating: "7.5 ⭐⭐⭐⭐",
        duration: "1 movie",
        description: "Weathering with You เล่าเรื่องราวของ ฮินะ (Hina), สาวสาวที่มีพลังพิเศษในการควบคุมสภาพอากาศ และ โฮโดกะ (Hodaka), เด็กหนุ่มที่หนีจากบ้านมาที่โตเกียว ทั้งสองร่วมกันใช้พลังในการช่วยแก้ไขปัญหาชีวิตและพยายามหาทางที่จะให้ฝนหยุดตกอย่างถาวร.",
        genres: "ดราม่า, โรแมนซ์, แฟนตาซี, Drama, Romance, Fantasy",
        review: "Weathering with You คือเรื่องราวที่อบอุ่นและเต็มไปด้วยอารมณ์เกี่ยวกับความรักและการเสียสละ.",
        reviews: [
            { author: "Anna", text: "A beautiful story with breathtaking visuals and a heart-wrenching ending!" },
            { author: "Mark", text: "A stunning film that captures the magic of love and the power of nature!" }
        ]
    },
    yourName: {
        title: "Your Name",
        poster: "./images/review/yourName.jpg",
        trailer: "https://youtu.be/mPsjLnEtJZI?si=OYVbgVrpKZFYBvhD",
        streamUrl: "https://www.netflix.com/it-en/title/80161371",
        rating: " 8.4 ⭐⭐⭐⭐",
        duration: "1 movie",
        description: "Your Name เป็นเรื่องราวของ มิทสึฮะ (Mitsuha) และ ทาคาโอะ (Taki) เด็กหนุ่มและสาวที่ต่างคนต่างอาศัยอยู่ในสถานที่ที่ห่างไกลกัน แต่ทั้งคู่พบว่าพวกเขาสามารถสลับร่างกันได้ในบางช่วงเวลา เรื่องราวของพวกเขาพัฒนาไปสู่ความรักและการค้นหากันในโลกที่มีความเชื่อมโยงทางลึกลับ.",
        genres: "โรแมนซ์, ดราม่า, แฟนตาซี, Romance, Drama, Fantasy",
        review: "Your Name คืออนิเมะที่เต็มไปด้วยความโรแมนติกและอารมณ์ที่ลึกซึ้ง การเล่าเรื่องที่สวยงามและฉากที่ตรึงใจทำให้มันกลายเป็นหนึ่งในอนิเมะที่น่าจดจำที่สุด.",
        reviews: [
            { author: "Michael", text: "A stunning blend of romance and fantasy with a beautifully emotional story!" },
            { author: "Sarah", text: "An unforgettable movie that captures the essence of love and fate!" }
        ]
    }, 
    QueenOfTears: {
    title: "Queen Of Tears",
    poster: "./images/review/QueenOfTears.jpg",
    trailer: "https://youtu.be/Y2oxBSp3pf8?si=fAbUjVa7sQAdtaCG",
    streamUrl: "https://www.netflix.com/title/81707950",
    rating: "8.2 ⭐⭐⭐⭐",
    duration: "16 episodes",
    description: "Queen Of Tears เป็นเรื่องราวเกี่ยวกับความรักและความเสียสละของคู่รักที่เผชิญหน้ากับความท้าทายและความเจ็บปวดในชีวิต เพื่อพิสูจน์ถึงความรักที่แท้จริง.",
    genres: "Animation, Romance, Fantasy",
    review: "Queen of Tears เป็นเรื่องราวความรักและการเสียสละที่งดงาม ตั้งอยู่ในบริบทของความท้าทายและความเจ็บปวดในชีวิต การเดินทางทางอารมณ์ในเรื่องนี้จะพาคุณไปพบกับการทดสอบความรักที่แท้จริงและพิสูจน์ถึงความรักนั้นได้อย่างสวยงาม ภาพอนิเมะที่งดงามและเนื้อหาที่เต็มไปด้วยความรู้สึกทำให้มันกลายเป็นหนึ่งในเรื่องที่น่าจดจำ",
    reviews: [
        { author: "Alice", text: "A beautifully animated film with a heartwarming story!" },
        { author: "Liam", text: "An emotional and visually stunning experience." }
    ]
  },  
  trueBeauty: {
    title: "True Beauty",
    poster: "./images/review/trueBeauty.jpg",
    trailer: "https://youtu.be/Rq3Afp1XGm0?si=MvSgZ9CvPTuAe7V8",  // Add the trailer URL if available
    streamUrl: "https://www.netflix.com/title/81410834",    // Add the streaming URL if available
    rating: "8.0 ⭐⭐⭐⭐",
    duration: "16 episodes",
    description: "True Beauty เป็นเรื่องราวของหญิงสาวที่เรียนรู้ที่จะรักตัวเองในโลกที่เต็มไปด้วยมาตรฐานความงาม และค้นพบความหมายของความมั่นใจและความรัก.",
    genres: "Romance, Comedy, Drama",
    review: "True Beauty เป็นเรื่องราวที่น่าประทับใจเกี่ยวกับการรักตัวเองและความมั่นใจในโลกที่เต็มไปด้วยมาตรฐานความงาม ซีรีส์นี้พูดถึงความสำคัญของการยอมรับตัวเองและการเรียนรู้ที่จะรักความงามภายใน ด้วยความขบขัน โรแมนติก และดราม่า มันเป็นการเดินทางที่อบอุ่นใจที่เฉลิมฉลองการเติบโตส่วนบุคคลและการเปลี่ยนแปลง",
    reviews: [
        { author: "Alice", text: "A refreshing take on self-love and beauty standards!" },
        { author: "Liam", text: "A heartwarming journey of self-discovery and confidence." }
    ]
},
  GyeongseongCreature: {
        title: "Gyeongseong Creature",
        poster: "./images/review/gyeong.jpg",
        trailer: "https://youtu.be/Q3YgWMxaq8o?si=G8VTM4sDhuct4JYS",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/title/81618079",    // Add the streaming URL if available
        rating: "7.3 ⭐⭐⭐",
        duration: "17 episodes",
        description: "Gyeongseong Creature เป็นเรื่องราวเกี่ยวกับการต่อสู้กับสิ่งมีชีวิตลึกลับในยุคประวัติศาสตร์ที่เต็มไปด้วยความลึกลับและความระทึกใจ.",
        genres: "Thriller, Mystery, Action",
        review: "Gyeongseong Creature เป็นเรื่องราวระทึกขวัญที่ตั้งอยู่ในโลกประวัติศาสตร์ที่เต็มไปด้วยความลึกลับและอันตราย การต่อสู้ที่เข้มข้นกับสิ่งมีชีวิตในตำนานทำให้ซีรีส์นี้เต็มไปด้วยความตื่นเต้นและความสนุก การผสมผสานระหว่างประวัติศาสตร์และความสยองขวัญทำให้มันกลายเป็นการดูที่น่าติดตามและมีความระทึกขวัญ",
        reviews: [
            { author: "John", text: "A thrilling blend of history and horror!" },
            { author: "Sarah", text: "Suspenseful and gripping from start to finish." }
        ]
    },
    Penthouses: {
        title: "Penthouses",
        poster: "./images/review/Penthouses.jpg",
        trailer: "https://youtu.be/NgD7nVVHAaQ?si=Q5FnjZny3Q7BvPNZ",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/de-en/title/81552562",    // Add the streaming URL if available
        rating: "7.9 ⭐⭐⭐⭐",
        duration: "48 episodes",
        description: "Penthouses เป็นเรื่องราวดราม่าสุดเข้มข้นในวงสังคมชั้นสูง ที่เต็มไปด้วยความลับ การหักหลัง และการแข่งขันในชีวิตที่หรูหรา.",
        genres: "Drama, Thriller",
        review: "Penthouses นำเสนอเรื่องราวดราม่าที่เข้มข้นในวงสังคมชั้นสูง ที่เต็มไปด้วยความลับ การหักหลัง และการแข่งขันในชีวิตหรูหรา ซีรีส์นี้ทำให้คุณติดตามทุกตอนด้วยความตื่นเต้น การบิดเบือนเรื่องราวและฉากที่หรูหราทำให้มันเป็นความเพลิดเพลินสำหรับผู้ที่ชอบความตื่นเต้นและความน่าสงสัย เตรียมตัวให้พร้อมสำหรับการหักมุมและความตื่นเต้นที่ไม่หยุดยั้ง",
        reviews: [
            { author: "Anna", text: "Dramatic twists and turns keep you hooked!" },
            { author: "Mark", text: "An intense and addicting drama!" }
        ]
    },
    DrRomantic: {
        title: "Dr. Romantic",
        poster: "./images/review/DrRomantic.jpg",
        trailer: "https://youtu.be/PLnSlTZnwkA?si=270b0ok6Vbjg6YRK",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/in/title/80998941",    // Add the streaming URL if available
        rating: "8.4 ⭐⭐⭐⭐",
        duration: "54 episodes",
        description: "Dr. Romantic บอกเล่าเรื่องราวของหมอที่ต้องต่อสู้กับความท้าทายของงานการแพทย์ในโรงพยาบาลชนบท พร้อมทั้งสร้างความเปลี่ยนแปลงในชีวิตของผู้คนที่เขาพบเจอ.",
        genres: "Medical, Drama, Romance",
        review:"Dr. Romantic เป็นซีรีส์ดราม่าทางการแพทย์ที่สร้างแรงบันดาลใจ ซึ่งสำรวจความท้าทายในการเป็นหมอในโรงพยาบาลชนบท ด้วยเรื่องราวที่อบอุ่นและกระตุ้นความรู้สึก ซีรีส์นี้แสดงให้เห็นถึงการอุทิศตนและการเสียสละของบุคลากรทางการแพทย์ เป็นการแสดงภาพชีวิตของหมอและผลกระทบที่พวกเขามีต่อผู้ป่วยของพวกเขา เหมาะสำหรับผู้ที่ชื่นชอบเรื่องราวที่สร้างแรงบันดาลใจที่มีส่วนผสมของโรแมนติกและดราม่า",
        reviews: [
            { author: "Chris", text: "Heartfelt and inspiring medical drama!" },
            { author: "Olivia", text: "Beautifully portrays the struggles of doctors." }
        ]
    },
    MaskGirl: {
        title: "Mask Girl",
        poster: "./images/review/MaskGirl.jpg",
        trailer: "https://youtu.be/HEbtoIs0qJ0?si=j_YpllzHex96sNRQ",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/th/title/81516491",    // Add the streaming URL if available
        rating: "7.3 ⭐⭐⭐",
        duration: "7 episodes",
        description: "Mask Girl เป็นเรื่องราวของหญิงสาวที่มีความลับเกี่ยวกับชีวิตในโลกออนไลน์ ที่มาพร้อมกับความมืดและผลกระทบที่ตามมา.",
        genres: "Medical, Drama, Romance",
        review:"สาวออฟฟิศผู้ไม่เคยมั่นใจในหน้าตาของตัวเองโด่งดังชั่วข้ามคืนภายใต้หน้ากาก และกลายเป็นดาวเด่นแห่งโลกออนไลน์ แต่แล้วกลับเกิดเหตุการณ์ร้ายที่อาจทำลายชีวิตของเธอ",
        reviews: [
            { author: "Chris", text: "Heartfelt and inspiring medical drama!" },
            { author: "Olivia", text: "Beautifully portrays the struggles of doctors." }
        ]
    },
    MrQueen: {
        title: "Mr.Queen",
        poster: "./images/review/MrQueen.jpg",
        trailer: "https://youtu.be/H1vplUsGDoI?si=JQYjHDrMSHg5xFWg",  // Add the trailer URL if available
        streamUrl: "https://www.viu.com/ott/th/th/vod/318577/Mr-Queen",    // Add the streaming URL if available
        rating: "8.7 ⭐⭐⭐⭐",
        duration: "20 episodes",
        description: "Mr. Queen เป็นเรื่องราวของเชฟชายในยุคปัจจุบันที่ประสบอุบัติเหตุและตื่นขึ้นมาในร่างของราชินีในยุคโชซอน เขาต้องปรับตัวให้เข้ากับชีวิตในวังและปัญหาการเมือง พร้อมทั้งค้นหาความลับที่ซ่อนอยู่.",
        genres: "Medical, Drama, Romance",
        review:"เชฟหนุ่มที่ตื่นขึ้นมาในร่างของราชินีในศตวรรษ 19 ต้องรับมือกับเรื่องดราม่าในรั้วในวัง และพยายามหาทางกลับสู่โลกยุคปัจจุบันเพื่อทวงคืนชีวิตเดิมกลับมา",
        reviews: [
            { author: "Chris", text: "Heartfelt and inspiring medical drama!" },
            { author: "Olivia", text: "Beautifully portrays the struggles of doctors." }
        ]
    },
    BlueBox: {
        title: "Blue Box",
        poster: "./images/review/BlueBox.jpg",
        trailer: "https://youtu.be/tcK9Eghsfq8?si=_vY3BgALr5HF1bKn",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/title/81663323",    // Add the streaming URL if available
        rating: "7.9 ⭐⭐⭐",
        duration: "12 episodes",
        description: "Blue Box เป็นเรื่องราวของความรักในโรงเรียนที่เกี่ยวกับเด็กหนุ่มผู้ชื่นชอบแบดมินตันและหญิงสาวนักกีฬาบาสเกตบอล เรื่องนี้นำเสนอความสัมพันธ์ที่แสนบริสุทธิ์ในช่วงวัยรุ่น.",
        genres: "Romance, School, Sports",
        review:"ไทกิ หนุ่มนักแบดมินตัน ชื่นชอบจินัตสิ หญิงสาวซึ่งเป็นซูเปอร์สตาร์นักบาสมาตลอด วันหนึ่งในฤดูใบไม้ผลิ เหตุการณ์ที่คาดไม่ถึงก็นำพาให้ทั้งสองมาใกล้ชิดกัน",
        reviews: [
            { author: "Alex", text: "A sweet and heartwarming story of young love." },
            { author: "Mia", text: "The sports and romance blend perfectly in Blue Box." }
        ]
    },
    FamilyByChoice: {
        title: "Family By Choice",
        poster: "./images/review/FamilyByChoice.jpg",
        trailer: "https://youtu.be/rcaEeJ4ejQ8?si=nB0f8MS5kvFw9f1h",  // Add the trailer URL if available
        streamUrl: "https://www.viu.com/ott/th/th/vod/2436277/Family-By-Choice-%E0%B8%84%E0%B8%A3%E0%B8%AD%E0%B8%9A%E0%B8%84%E0%B8%A3%E0%B8%B1%E0%B8%A7%E0%B8%AB%E0%B8%B1%E0%B8%A7%E0%B9%83%E0%B8%88%E0%B8%A5%E0%B8%B4%E0%B8%82%E0%B8%B4%E0%B8%95%E0%B9%80%E0%B8%AD%E0%B8%87",    // Add the streaming URL if available
        rating: "8.5 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "Family By Choice เล่าเรื่องราวของความสัมพันธ์ที่สร้างจากความผูกพันมากกว่าสายเลือด ตัวละครต้องต่อสู้กับอุปสรรคและพิสูจน์ความหมายของคำว่าครอบครัว.",
        genres: "Drama, Family",
        review:"ยุนจูวอนเป็นเด็กสาวสดใสและติดดินที่อาศัยอยู่กับยุนจองแจพ่อเลี้ยงเดี่ยวของเธอ เมื่อครั้งคิมซานฮาและคังแฮจุน สองเด็กหนุ่มที่มีเรื่องราวแต่หนหลังอันซับซ้อนเข้ามาในชีวิตของเธอ พ่อยุนจูวอนรับเด็กหนุ่มทั้งสองมาเลี้ยงเป็นครอบครัวเดียวกัน โดยมีคิมแดอุคพ่อของซานฮาคอยช่วยด้วย  แม้ว่าจะไม่มีความเกี่ยวข้องกันทางสายเลือด จูวอนก็รักซานฮากับแฮจุนและคิดว่าทั้งสองเป็นพี่ชายของเธอ โดยไม่สนใจว่าคนอื่น ๆ จะคิดหรือพูดอะไร ซานฮามีรูปร่างหน้าตาโดดเด่นและมีความเฉลียวฉลาดที่ใคร ๆ ก็ชื่นชม แม้ว่าภายนอกเขาจะดูสมบูรณ์แบบ ทว่าภายในกลับแบกรับความรู้สึกผิดที่คอยตามติดเป็นเงา แฮจุนเป็นคนที่ป๊อปในโรงเรียน แถมยังเป็นนักบาสเกตบอลที่อยากจะประสบความสำเร็จ เขาทำทุกอย่างเพื่อตอบแทนความใจดีของจองแจ สิบปีผ่านไปพวกเขาได้กลับมาเจอกันอีกครั้ง และความรู้สึกที่มีต่อกันก็แปรเปลี่ยนเป็นความรัก ก่อเกิดเป็นรักสามเส้า เรื่องราวจะดำเนินต่อไปอย่างไร",
        reviews: [
            { author: "Emma", text: "A touching series about love and family ties." },
            { author: "John", text: "Beautifully portrays the meaning of family beyond blood." }
        ]
    },
    YakuzaFiance: {
        title: "Yakuza Fiancé",
        poster: "./images/review/YakuzaFiance.jpg",
        trailer: "https://youtu.be/iztVtadySOk?si=rEY-9sg1z9F9bjk-",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/jp/browse/genre/6721",    // Add the streaming URL if available
        rating: "7.4 ⭐⭐⭐",
        duration: "12 episodes",
        description: "Yakuza Fiancé เป็นเรื่องราวความรักที่ไม่ธรรมดาระหว่างลูกสาวของผู้นำยากูซ่าและชายหนุ่มจากกลุ่มคู่แข่ง การต่อสู้ระหว่างความรักและความขัดแย้งของครอบครัวทำให้เรื่องนี้เต็มไปด้วยความตื่นเต้น.",
        genres: "Romance, Action, Drama",
        review:"ไทกิ หนุ่มนักแบดมินตัน ชื่นชอบจินัตสิ หญิงสาวซึ่งเป็นซูเปอร์สตาร์นักบาสมาตลอด วันหนึ่งในฤดูใบไม้ผลิ เหตุการณ์ที่คาดไม่ถึงก็นำพาให้ทั้งสองมาใกล้ชิดกัน",
        reviews: [
            { author: "Sophia", text: "A thrilling mix of romance and underworld drama." },
            { author: "David", text: "Unexpected twists and emotional depth." }
        ]
    },
    WhenThePhoneRing: {
        title: "When The Phone Rings",
        poster: "./images/review/WhenThePhoneRing.jpg",
        trailer: "https://youtu.be/Y4P-z1Qy6xI?si=WXIYx6isV-mzwSR8",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/title/81912961",    // Add the streaming URL if available
        rating: "8.1 ⭐⭐⭐⭐",
        duration: "120 episodes",
        description: "When The Phone Rings เป็นเรื่องราวลึกลับที่เกิดจากการโทรศัพท์ที่ไม่ทราบที่มา นำไปสู่เหตุการณ์ที่คาดไม่ถึงในชีวิตของตัวละคร.",
        genres: "Mystery, Thriller, Drama",
        review:"ไทกิ หนุ่มนักแบดมินตัน ชื่นชอบจินัตสิ หญิงสาวซึ่งเป็นซูเปอร์สตาร์นักบาสมาตลอด วันหนึ่งในฤดูใบไม้ผลิ เหตุการณ์ที่คาดไม่ถึงก็นำพาให้ทั้งสองมาใกล้ชิดกัน",
        reviews: [
            { author: "Olivia", text: "Keeps you guessing until the very end." },
            { author: "Chris", text: "Suspenseful and brilliantly written." }
        ]
    },
    RonKamonohashi: {
        title: "Ron Kamonohashi's Forbidden Deductions",
        poster: "./images/review/RonKamonohashi.jpg",
        trailer: "https://youtu.be/98OVGH1Tt1Y?si=1F5ugle_hZZTqXnY",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/th-en/title/81726765",    // Add the streaming URL if available
        rating: "7.1 ⭐⭐⭐",
        duration: "13 episodes",
        description: "Ron Kamonohashi เป็นนักสืบที่มีพรสวรรค์ในการไขคดีที่ยากที่สุด แต่เขาต้องเผชิญกับความลึกลับที่เกี่ยวข้องกับอดีตของเขาเอง.",
        genres: "Mystery, Detective, Thriller",
        review:"ไทกิ หนุ่มนักแบดมินตัน ชื่นชอบจินัตสิ หญิงสาวซึ่งเป็นซูเปอร์สตาร์นักบาสมาตลอด วันหนึ่งในฤดูใบไม้ผลิ เหตุการณ์ที่คาดไม่ถึงก็นำพาให้ทั้งสองมาใกล้ชิดกัน",
        reviews: [
            { author: "Mark", text: "An intriguing detective story with complex characters." },
            { author: "Liam", text: "Clever and full of twists!" }
        ]
    },
    TaleOfLadyOk: {
        title: "The Tale Of Lady Ok",
        poster: "./images/review/TaleOfLadyOk.jpg",
        trailer: "https://youtu.be/VVFBKICoeJk?si=JSLLHJuuaWNLCqA0",  // Add the trailer URL if available
        streamUrl: "https://www.bilibili.tv/en/video/4793210547606016",    // Add the streaming URL if available
        rating: "8.0 ⭐⭐⭐⭐",
        duration: "16 episodes",
        description: "The Tale Of Lady Ok เป็นเรื่องราวของหญิงสาวผู้มีความสามารถที่ต้องเผชิญกับการต่อสู้ทางการเมืองและความลับในอดีตของครอบครัว.",
        genres: "Historical, Drama, Romance",
        review:"นักกวีหนุ่มและนักเล่าเรื่องราวซึ่งเดินทางไปทั่วทุกสารทิศเพื่อไปเผยแพร่บทกวีตามที่ต่าง ๆ จนวันหนึ่งได้พบกับ อ๊กแทยอง ก็เกิดตกหลุมรักเธอและพร้อมจะอยู่เคียงข้างคอยช่วยเหลือเธอไม่ว่าจะด้วยเรื่องอะไร",
        reviews: [
            { author: "Anna", text: "A beautifully crafted historical drama." },
            { author: "Sophia", text: "Rich in emotion and stunning visuals." }
        ]
    },

    SoloLeveling: {
        title: "The Tale Of Lady Ok",
        poster: "./images/review/SoloLeveling.jpg",
        trailer: "https://youtu.be/eqy85AL70PU?si=rNwk1kr4iCpxxWR4",  // Add the trailer URL if available
        streamUrl: "https://www.netflix.com/th-en/title/81749761",    // Add the streaming URL if available
        rating: "8.3 ⭐⭐⭐⭐",
        duration: "12 episodes",
        description: "Solo Leveling เล่าเรื่องราวของ ซองจินอู (Sung Jin-Woo) นักล่าที่อ่อนแอที่สุดในโลก แต่หลังจากเหตุการณ์ประหลาด เขาได้รับพลังพิเศษที่ทำให้เขา 'เลเวลอัพ' ได้อย่างไร้ขีดจำกัด การเดินทางของเขาเต็มไปด้วยอันตรายและการต่อสู้เพื่อเอาชีวิตรอด.",
        genres: "Historical, Drama, Romance",
        review:"Solo Leveling เป็นอนิเมะที่เต็มไปด้วยแอ็คชั่นที่ดุเดือดและการเติบโตของตัวละครที่น่าติดตาม ถือเป็นหนึ่งในอนิเมะที่สร้างแรงบันดาลใจและน่าตื่นเต้นที่สุดในยุคนี้!",
        reviews: [
            { author: "Anna", text: "A beautifully crafted historical drama." },
            { author: "Sophia", text: "Rich in emotion and stunning visuals." }
        ]
    },

};


// Function to get the drama name from the URL query parameters
function getDramaFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('drama'); // Returns 'squidGame', 'marryMyHusband', etc.
}

// Dynamically update the page based on the drama name
function loadDramaReview() {
    const dramaName = getDramaFromURL(); // Get the drama from the URL
    const drama = dramaData[dramaName]; // Get the data fTraileror the selected drama

    if (!drama) return; // Return if no drama is found

    // Populate drama details
    document.getElementById("drama-title").textContent = drama.title;
    document.getElementById("drama-poster").src = drama.poster;
    document.getElementById("drama-description").textContent = drama.description;
    document.getElementById("drama-genres").textContent = drama.genres;
    document.getElementById("drama-rating").textContent = `Rating: ${drama.rating}`; // แสดงคะแนน
    document.getElementById("drama-duration").textContent = `Duration: ${drama.duration}`; // แสดงจำนวนตอน
    document.getElementById("drama-trailer").href = drama.trailer;  
    document.getElementById('drama-url').href = drama.streamUrl;
    document.getElementById("drama-review").textContent = drama.review;

    // เลือกโลโก้ของบริการสตรีมมิ่งตามลิงก์ streamUrl
    if (drama.streamUrl.includes("netflix")) {
        document.getElementById('stream-logo').src = "./images/logo/netflix.png"; 
    } else if (drama.streamUrl.includes("prime")) {
        document.getElementById('stream-logo').src = "./images/logo/Prime_Video.png"; 
    } else if (drama.streamUrl.includes("viu")) {
        document.getElementById('stream-logo').src = "./images/logo/Viu_logo.svg.png"; 
    } else if (drama.streamUrl.includes("trueid")) {
        document.getElementById('stream-logo').src = "./images/logo/True_ID.png"; 
    } else if (drama.streamUrl.includes("bilibili")) {
        document.getElementById('stream-logo').src = "./images/logo/Bilibili.png";
    } else if (drama.streamUrl.includes("disneyplus")) {
        document.getElementById('stream-logo').src = "./images/logo/disney_plus.png"; 
    }

    // Fetch the username from the hidden span element
    const username = document.getElementById("username").textContent.trim();

    // Display existing reviews
    const reviewsList = document.getElementById("comments-list");
    drama.reviews.forEach(review => {
        const reviewDiv = document.createElement("div");
        reviewDiv.classList.add("comment");
        reviewDiv.innerHTML = `<p><strong>${review.author}:</strong> ${review.text}</p>`;
        reviewsList.appendChild(reviewDiv);
    });
    
    // Handle Comment Submission
    const submitButton = document.getElementById('submit-comment');
    const commentInput = document.getElementById('comment-input');
    const totalComments = document.getElementById('total-comments');

    
    submitButton.addEventListener('click', () => {
        if (commentInput.value) {
            const commentDiv = document.createElement("div");
            commentDiv.classList.add("comment");
            commentDiv.innerHTML = `<p><strong>${username}:</strong> ${commentInput.value}</p>`;
            reviewsList.appendChild(commentDiv);
            commentInput.value = '';
            totalComments.textContent = reviewsList.children.length;
        }
    });
}


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


    // เมื่อหน้าโหลดเสร็จแล้วจะเลื่อนไปยัง section ที่ต้องการ
    window.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;  // ตรวจสอบ URL ของหน้า
        if (hash) {
            document.querySelector(hash).scrollIntoView({
                behavior: 'smooth', // เลื่อนแบบนุ่มนวล
                block: 'start'      // ให้เลื่อนไปที่ตำแหน่งของ section
            });
        }
    });

    // Handle Comment Submission
const submitButton = document.getElementById('submit-comment');
const commentInput = document.getElementById('comment-input');
const totalComments = document.getElementById('total-comments');

// Assuming `username` and `user_id` are available globally
// And `series_anime_id` is known (e.g., passed to the page or available via JavaScript)
const seriesAnimeId = 1; // Replace with actual series ID
const userId = 123; // Replace with the logged-in user's ID

submitButton.addEventListener('click', () => {
    if (commentInput.value) {
        const commentDiv = document.createElement("div");
        commentDiv.classList.add("comment");
        commentDiv.innerHTML = `<p><strong>${username}:</strong> ${commentInput.value}</p>`;
        reviewsList.appendChild(commentDiv);
        
        // Send the comment to the server (database)
        fetch('/submit-comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                series_anime_id: seriesAnimeId,
                comment_text: commentInput.value
            })
        }).then(response => {
            if (response.ok) {
                console.log('Comment submitted successfully');
            } else {
                console.log('Failed to submit comment');
            }
        }).catch(error => console.error('Error submitting comment:', error));
        
        // Clear input and update the total comments counter
        commentInput.value = '';
        totalComments.textContent = reviewsList.children.length;
    }
});

// Display existing reviews on page load
window.addEventListener('DOMContentLoaded', () => {
    fetch(`/comments/${seriesAnimeId}`)
        .then(response => response.json())
        .then(comments => {
            const reviewsList = document.getElementById("comments-list");
            comments.forEach(review => {
                const reviewDiv = document.createElement("div");
                reviewDiv.classList.add("comment");
                reviewDiv.innerHTML = `<p><strong>${review.user_id}:</strong> ${review.comment_text}</p>`;
                reviewsList.appendChild(reviewDiv);
            });
            const totalComments = document.getElementById('total-comments');
            totalComments.textContent = comments.length;
        })
        .catch(error => console.error('Error loading comments:', error));
});


</script>
</body>
</html>


<?php
session_start(); // เริ่ม session เพื่อรับข้อมูลผู้ใช้
include 'db_connection.php'; // Include your database connection file

// ตรวจสอบว่า user_id ใน session มีค่าหรือไม่ (หมายถึงผู้ใช้ล็อกอินแล้ว)
if (!isset($_SESSION['user_id'])) {
    // ถ้าผู้ใช้ไม่ได้ล็อกอิน จะรีไดเรกต์ไปยังหน้า login
    header("Location: login.html");
    exit;
}


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
    <link rel="shortcut icon" type="x-icon" href="./images/drama-logo.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestiffy_Za</title>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- OWL CAROUSEL -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- BOX ICONS -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <!-- APP CSS -->
    <link rel="stylesheet" href="grid.css">
    <link rel="stylesheet" href="app.css">
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
    </style>
</head>

<body>
    <!-- เมนูนำทาง -->
    <div class="nav-wrapper">
        <a href="index.php" class="logo">Suggestiffy_Za</a>
        <ul class="nav-menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="#korea-series-section">ซีรีส์เกาหลี</a></li>
            <li><a href="#anime-section">อนิเมะ</a></li>
            <li><a href="#special-movie-section">รีวิวยอดนิยม</a></li>
        </ul>
        <div class="dropdown">
            <div class="dropdown-toggle" onclick="toggleDropdown()">
            <img src="<?php echo $userProfilePictureSrc; ?>" alt="User  Avatar" />
            <span id="username">ยินดีต้อนรับคุณ → <?php echo htmlspecialchars($userName); ?></span>
            </div>
            <ul class="dropdown-menu" id="dropdownMenu">
                <li><a href="profile.php"><i class="fas fa-user"></i> โปรไฟล์ </a></li>
                <li><a href="#anime-section"><i class="fas fa-history"></i> Anime Section</a></li>
                <li><a href="#korea-series-section"><i class="fas fa-gift"></i> K-Drama Section</a></li>
                <li><a href="#special-movie-section"><i class="fas fa-box"></i> รีวิวที่ปักมุดไว้ 📌</a></li>
                <li><a href="watchlist.php">Watchlist</a></li>
                <a href="logout.php" class="logout">ออกจากระบบ</a>
            </ul>
        </div>
    </div>

    <!-- จบเมนูนำทาง -->

            <!-- HERO SECTION -->
            <div class="hero-section">
             <!-- HERO SLIDE -->
            <div class="hero-slide">
            <div class="owl-carousel carousel-nav-center" id="hero-carousel">
                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/squidgame.jpg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                Squid game
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.0</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>10 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>15+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Squid Game" ซีรีส์สุดระทึกที่เกมเด็ก ๆ กลับเต็มไปด้วยความตาย! ผู้เล่น 456 คนต้องต่อสู้ในเกมที่เดิมพันด้วยชีวิต เพื่อคว้าเงินรางวัลมหาศาล แต่เมื่อเกมเริ่มดุเดือด ความโลภและการทรยศก็เริ่มขึ้น! ใครจะรอด? ใครจะชนะ? ห้ามพลาด!
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=squidGame" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->

                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/Marry-My-Husband.jpg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                Marry My Husband
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>7.8</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>16 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>12+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                            🎬 "Marry My Husband" เป็นละครโทรทัศน์เกาหลีใต้ที่ออกอากาศในปี 2024 ซึ่งมีเนื้อเรื่องเกี่ยวกับหญิงสาวคนหนึ่งที่ป่วยหนักและกลับไปที่อดีตเพื่อเปลี่ยนแปลงชะตาชีวิตตนเอง เมื่อรู้ว่าสามีของเธอมีความสัมพันธ์กับเพื่อนสนิทของเธอ โดยมีนักแสดงนำคือ ปาร์คมินยอง, นาอินอู, และลีอีย์คยอง
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=marryMyHusband" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->

                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/blue lock.jpeg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                blue lock
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.1</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>24 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>13+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Blue Lock" อนิเมะฟุตบอลที่เต็มไปด้วยการแข่งขันสุดมันส์! 300 นักเตะถูกคัดเลือกให้เข้าร่วมโครงการ Blue Lock เพื่อค้นหากองหน้าที่ดีที่สุดของญี่ปุ่น! ทุกคนต้องแข่งขันกันอย่างดุเดือดเพื่ออยู่รอดและเป็นตัวเลือกของทีมชาติ! ใครจะได้เป็นผู้ชนะและพาทีมชาติไปสู่ความสำเร็จ? ⚽
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=blueLock" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->

                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/Dandadan.jpeg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                Dandadan
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>9.0</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>12 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>15+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Dandadan" อนิเมะสุดมันส์! ผจญภัยในโลกปีศาจและวิญญาณที่ทั้งลึกลับและตื่นเต้น! เมื่อ มิโฮ และ โคจิ สองเด็กหนุ่มสาวต้องมาร่วมมือกันเผชิญหน้ากับปีศาจจากหลายมิติ และไขปริศนาที่เกี่ยวข้องกับอดีตของพวกเขาเอง เตรียมตัวให้พร้อมกับการต่อสู้สุดเดือดที่พวกเขาต้องใช้ทั้งไหวพริบและพลังที่ไม่เคยรู้มาก่อน! อย่าพลาด!
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=dandadan" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->

                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/blue-period.jpg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                blue period
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>9.5</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>12 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>12+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Blue Period" อนิเมะที่เต็มไปด้วยแรงบันดาลใจ! ยาโตะ เด็กหนุ่มที่เคยมีชีวิตมัธยมธรรมดา กลับพบความหลงใหลใน ศิลปะ และตัดสินใจทุ่มเททุกอย่างเพื่อสอบเข้าเรียนศิลปะ แม้จะต้องเผชิญกับอุปสรรคมากมาย! เรื่องราวที่ทำให้คุณอยากตามหาความฝันของตัวเอง! 🎨
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=bluePeriod" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->
                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/alchemyofsouel.jpg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                Alchemy of Souls
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.0</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>30 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>13+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Alchemy of Souls" ซีรีส์แฟนตาซีสุดมันส์! เมื่อเวทมนตร์สามารถย้ายจิตวิญญาณไปยังร่างใหม่, นากอน นักดาบต้องต่อสู้กับเวทมนตร์และความลึกลับที่ซ่อนอยู่ในโลกนี้ พร้อมกับ มูด็อก หญิงสาวที่มีความลับบางอย่าง! การผจญภัยเต็มไปด้วยความรัก, การแก้แค้น, และพลังเวทมนตร์ที่ไม่เคยเห็นมาก่อน! ✨
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=alchemyOfSouls" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->
                <!-- SLIDE ITEM -->
                <div class="hero-slide-item">
                    <img src="./images/Slam_Dunk_basketball_comic_art-2210211.jpg" alt="">
                    <div class="overlay"></div>
                    <div class="hero-slide-item-content">
                        <div class="item-content-wraper">
                            <div class="item-content-title top-down">
                                slam dunk
                            </div>
                            <div class="movie-infos top-down delay-2">
                                <div class="movie-info">
                                    <i class="bx bxs-star"></i>
                                    <span>8.7</span>
                                </div>
                                <div class="movie-info">
                                    <i class="bx bxs-time"></i>
                                    <span>101 episodes</span>
                                </div>
                                <div class="movie-info">
                                    <span>HD</span>
                                </div>
                                <div class="movie-info">
                                    <span>15+</span>
                                </div>
                            </div>
                            <div class="item-content-description top-down delay-4">
                                🎬 "Slam Dunk" อนิเมะบาสเกตบอลสุดมันส์! ติดตาม ฮานามิจิ ซากุรางิ เด็กหนุ่มที่เริ่มเล่นบาสเพื่อจีบสาว แต่กลับหลงรักกีฬานี้และพัฒนาไปอย่างรวดเร็ว เขาจะพาทีม Shohoku สู่ชัยชนะได้ไหม? ห้ามพลาดการต่อสู้ที่เต็มไปด้วยแรงบันดาลใจและความตื่นเต้น! 🏀
                            </div>
                            <div class="item-action top-down delay-6">
                                <a href="review.php?drama=slamDunk" class="btn btn-hover">
                                    <i class="bx bxs-right-arrow"></i>
                                    <span>อ่านรีวิว</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SLIDE ITEM -->
                 
            </div>
        </div>
        <!-- END HERO SLIDE -->

<!-- TOP MOVIES SLIDE -->
<div class="top-movies-slide">
    <div class="owl-carousel" id="top-movies-slide">
        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=theJudgeFromHell">
                <img src="./images/series/The_Judge_from_Hell_poster.png" alt="The Judge from Hell">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    The Judge from Hell
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>7.8</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>14 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>15+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->

        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=startUp">
                <img src="./images/series/startup.jpg" alt="Start Up">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    Start Up
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>8.0</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>16 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>13+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->

        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=moneyHeistKorea">
                <img src="./images/series/money heist korea.jpg" alt="Money Heist Korea">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    Money Heist Korea
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>5.8</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>12 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>18+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->

        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=whatWrongWithSecretaryKim">
                <img src="./images/series/what wrong (1).jpg" alt="What Wrong with Secretary Kim">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    What Wrong with Secretary Kim
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>8.0</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>16 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>13+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->

        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=chainsawMan">
                <img src="./images/animes/chainsaw man (1).jpg" alt="Chainsaw Man">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    Chainsaw Man
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>8.3</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>12 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>16+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->

        <!-- MOVIE ITEM -->
        <div class="movie-item">
            <a href="review.php?drama=deathNote">
                <img src="./images/animes/death note (1).jpg" alt="Death Note">
            </a>
            <div class="movie-item-content">
                <div class="movie-item-title">
                    Death Note
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>8.9</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>37 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>13+</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MOVIE ITEM -->
    </div>
</div>
<!-- END TOP MOVIES SLIDE -->


<!-- ANIME MOVIES SECTION -->
<div class="section" id="anime-section">
    <div class="container">
        <div class="section-header">
            ANIME
        </div>
        <div class="movies-slide carousel-nav-center owl-carousel">
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=demonslayer" class="movie-item">
                <img src="./images/animes/demon-slayer.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Demon Slayer
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.5</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>55 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>12+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=dragonball" class="movie-item">
                <img src="./images/animes/dragon.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Dragon Ball
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.6</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>153 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>9+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=jujutsukaisen" class="movie-item">
                <img src="./images/animes/jujutsu kaisen.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Jujutsu Kaisen
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.5</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>24 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>15+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=myheroacademy" class="movie-item">
                <img src="./images/animes/myhero.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Myhero Academy
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.2</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>168 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>12+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=onePiece" class="movie-item">
                <img src="./images/animes/one piece (1).jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        One Piece
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>9.0</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>1088 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>14+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=weatheringWithYou" class="movie-item">
                <img src="./images/animes/weathering.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Weathering With You
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>7.5</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>1 episode</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>8+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->

            <!-- MOVIE ITEM -->
            <a href="review.php?drama=yourName" class="movie-item">
                <img src="./images/animes/your-name.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Your Name
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.4</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>1 episode</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>12+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
        </div>
    </div>
</div>
<!-- END ANIME MOVIES SECTION -->


<!-- Korea Series SECTION -->
<div class="section" id="korea-series-section">
    <div class="container">
        <div class="section-header">
            Korea Series
        </div>
        <div class="movies-slide carousel-nav-center owl-carousel">
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=QueenOfTears" class="movie-item">
                    <img src="./images/series/Queen.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Queen Of Tears
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>8.2</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>16 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>15+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=trueBeauty" class="movie-item">
                    <img src="./images/series/zpEf3jVD7LONvIm38Cc7UweWg3u.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            True Beauty
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>8.0</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>120 mins</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>12+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=GyeongseongCreature" class="movie-item">
                    <img src="./images/series/lMhqqPAHsEj1PXZxxOxeYt1Bmwf.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Gyeongseong Creature
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>7.3</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>17 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>15+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=Penthouses" class="movie-item">
                    <img src="./images/series/penthouses.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Penthouses
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>7.9</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>48 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>16+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=DrRomantic" class="movie-item">
                    <img src="./images/series/3mY7kkXa4HFNLZf7y2E56r7yeEe.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Dr. Romantic
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>8.4</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>54 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>15+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=MaskGirl" class="movie-item">
                    <img src="./images/series/MV5BMDYxNmFkYmItNGMzYS00NzEwLThlYmYtNWZhOTAxZWQxZTEzXkEyXkFqcGc@._V1_.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Mask Girl
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>7.3</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>7 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>15+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
                <!-- MOVIE ITEM -->
                <a href="review.php?drama=MrQueen" class="movie-item">
                    <img src="./images/series/MV5BMmMzZDdjMjktMjY0MS00ODk2LTgxZmEtZTlkYmI0YTQ3MzQ5XkEyXkFqcGc@._V1_.jpg" alt="">
                    <div class="movie-item-content">
                        <div class="movie-item-title">
                            Mr. Queen
                        </div>
                        <div class="movie-infos">
                            <div class="movie-info">
                                <i class="bx bxs-star"></i>
                                <span>8.6</span>
                            </div>
                            <div class="movie-info">
                                <i class="bx bxs-time"></i>
                                <span>20 episodes</span>
                            </div>
                            <div class="movie-info">
                                <span>HD</span>
                            </div>
                            <div class="movie-info">
                                <span>15+</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- END MOVIE ITEM -->
            </div>
        </div>
    </div>
</div>
<!-- END Korea Series SECTION -->


<!-- HOT SECTION 🔥 -->
<div class="section">
    <div class="container">
        <div class="section-header">
            HOT 🔥
        </div>
        <div class="movies-slide carousel-nav-center owl-carousel">
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=BlueBox" class="movie-item">
                <img src="./images/hot/MV5BMTRiYzMzN2YtZDFjNC00OTg1LTk0ZTQtOTlhZjYxZTAwNWYzXkEyXkFqcGc@._V1_.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Blue Box
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>7.9</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>12 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>12+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=FamilyByChoice" class="movie-item">
                <img src="./images/hot/ktruj4lesvnd1.jpeg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Family By Choice
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.5</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>16 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>15+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=YakuzaFiance" class="movie-item">
                <img src="./images/hot/MV5BNmY1NmYzMjgtNWU3MS00ZDk4LTgyNjEtYmIyZTUyMzQ3MGJiXkEyXkFqcGc@._V1_.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Yakuza Fiancé
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>7.4</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>12 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>12+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=WhenThePhoneRing" class="movie-item">
                <img src="./images/hot/MV5BZWMyMjRkYzMtZDMyNS00ZTEwLTg3ZmMtYTljZDMxMjg2MjNhXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        When The Phone Ring
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.1</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>120 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>13+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=RonKamonohashi" class="movie-item">
                <img src="./images/hot/ggy4zttwptid1.jpeg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Ron Kamonohashi's Forbidden Deductions
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>7.1</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>13 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>13+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=taleofladyok" class="movie-item">
                <img src="./images/hot/talesofladyok.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        The Tale Of Lady Ok
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.0</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>16 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>15+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
            <!-- MOVIE ITEM -->
            <a href="review.php?drama=SoloLeveling" class="movie-item">
                <img src="./images/hot/81ZAC67DE1S._SL1500_.jpg" alt="">
                <div class="movie-item-content">
                    <div class="movie-item-title">
                        Solo Leveling
                    </div>
                    <div class="movie-infos">
                        <div class="movie-info">
                            <i class="bx bxs-star"></i>
                            <span>8.3</span>
                        </div>
                        <div class="movie-info">
                            <i class="bx bxs-time"></i>
                            <span>12 episodes</span>
                        </div>
                        <div class="movie-info">
                            <span>HD</span>
                        </div>
                        <div class="movie-info">
                            <span>10+</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- END MOVIE ITEM -->
        </div>
    </div>
</div>
<!-- END HOT SECTION 🔥 -->


<!-- SPECIAL MOVIE SECTION -->
<div class="section" id="special-movie-section">
    <div class="hero-slide-item">
        <img src="./images/45c6e5d6cf5d9366ee695c4915a66206866d02c5_639c008e-bffa-411e-894f-dc37b24e849e.jpg" alt="">
        <div class="overlay"></div>
        <div class="hero-slide-item-content">
            <div class="item-content-wraper">
                <div class="item-content-title">
                    🥇Lovely Runner
                </div>
                <div class="movie-infos">
                    <div class="movie-info">
                        <i class="bx bxs-star"></i>
                        <span>8.6</span>
                    </div>
                    <div class="movie-info">
                        <i class="bx bxs-time"></i>
                        <span>16 episodes</span>
                    </div>
                    <div class="movie-info">
                        <span>HD</span>
                    </div>
                    <div class="movie-info">
                        <span>15+</span>
                    </div>
                </div>
                <div class="item-content-description">
                    🎬"Lovely Runner" ซีรีส์เกาหลีแนวโรแมนติก-แฟนตาซีที่คุณไม่ควรพลาด! เมื่อ อิมซล (รับบทโดย คิมฮเยยุน) แฟนคลับตัวยงของไอดอลหนุ่ม รยูซอนแจ (รับบทโดย บยอนอูซอก) ได้รับโอกาสย้อนเวลากลับไปในอดีตหลังจากการเสียชีวิตอย่างกะทันหันของเขา เธอตัดสินใจใช้โอกาสนี้เพื่อเปลี่ยนแปลงชะตากรรมและช่วยชีวิตคนที่เธอรัก เรื่องราวการผจญภัยข้ามเวลาที่เต็มไปด้วยความรักและความหวัง กำลังรอคุณอยู่!
                </div>
                <div class="item-action">
                    <a href="review.php?drama=Lovelyrunner" class="btn btn-hover">
                        <i class="bx bxs-right-arrow"></i>
                        <span>อ่านรีวิว</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- END SPECIAL MOVIE SECTION -->

    <!-- FOOTER SECTION -->
    <footer class="section">
        <div class="container">
            <div class="row">
                <div class="col-4 col-md-6 col-sm-12">
                    <div class="content">
                        <a href="#" class="logo">
                            <i class=''></i><span class="main-color"></span>Suggestiffy_Za
                        </a>
                        <p>
                            เป็นแพลตฟอร์มสำหรับคนรัก ซีรีส์เกาหลี (K-dramas) <br> และอนิเมะ ที่ช่วยให้ผู้ใช้สามารถมองหาคำแนะนำเกี่ยวกับผลงานต่างๆ <br>ผ่านการรีวิวและเรื่องย่อ พร้อมทั้งแชร์ความคิดเห็นกับผู้อื่น ผ่านแพลตฟอร์ม <br>ที่ช่วยทำให้การตัดสินใจในการเลือกรับชมซีรีส์หรืออนิเมะของคุณง่ายขึ้น.
                        <div class="social-list">
                            <a href="#" class="social-item">
                                <i class="bx bxl-facebook"></i>
                            </a>
                            <a href="#" class="social-item">
                                <i class="bx bxl-twitter"></i>
                            </a>
                            <a href="#" class="social-item">
                                <i class="bx bxl-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-8 col-md-6 col-sm-12">
                    <div class="row">
                        <div class="col-3 col-md-6 col-sm-6">
                            <div class="content">
                                <p><b>1st Pannathorn Suvannasai</b></p>
                                <ul class="footer-menu">
                                    <li><a href="#">About us</a></li>
                                    <li><a href="#">My profile</a></li>
                                    <li><a href="#">Pricing plans</a></li>
                                    <li><a href="#">Contacts</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-3 col-md-6 col-sm-6">
                            <div class="content">
                                <p><b>2nd Phonrawat Phongthippanut</b></p>
                                <ul class="footer-menu">
                                    <li><a href="#">About us</a></li>
                                    <li><a href="#">My profile</a></li>
                                    <li><a href="#">Pricing plans</a></li>
                                    <li><a href="#">Contacts</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-3 col-md-6 col-sm-6">
                            <div class="content">
                                <p><b>3rd Phonlawit Wanida</b></p>
                                <ul class="footer-menu">
                                    <li><a href="#">About us</a></li>
                                    <li><a href="#">My profile</a></li>
                                    <li><a href="#">Pricing plans</a></li>
                                    <li><a href="#">Contacts</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-3 col-md-6 col-sm-6">
                            <div class="content">
                       
                                <ul class="footer-menu">
                                    <li>
                                        <a href="#">
                                            
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- END FOOTER SECTION -->

    <!-- COPYRIGHT SECTION -->
    <div class="copyright">
        Copyright © 2024. Non-profit use only. Suggestify Team.
    </div>
    <!-- END COPYRIGHT SECTION -->

    <!-- SCRIPT -->
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- OWL CAROUSEL -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>
    <!-- APP SCRIPT -->
    <script src="app.js"></script>

<script>


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
</script>

</body>
</html>
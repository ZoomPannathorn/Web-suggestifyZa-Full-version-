<?php
// ตรวจสอบว่าได้รับข้อมูลจาก register.php หรือไม่
$status = isset($_GET['status']) ? $_GET['status'] : 'error';
$message = isset($_GET['message']) ? $_GET['message'] : 'เกิดข้อผิดพลาด';

// ส่งค่าให้ตรงกับสถานะที่ได้รับจาก PHP
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะการสมัครสมาชิก</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        .status-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            width: 400px;
        }

        .status-container.success {
            border-color: #28a745;
            background-color: #d4edda;
        }

        .status-container.error {
            border-color: #dc3545;
            background-color: #f8d7da;
        }

        .status-container h1 {
            font-size: 24px;
            color: #333;
        }

        .status-container p {
            font-size: 18px;
            color: #333;
        }

        .status-container .countdown {
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>

    <div class="status-container <?php echo $status; ?>">
        <h1><?php echo $status === 'success' ? 'สมัครสมาชิกสำเร็จ!' : 'เกิดข้อผิดพลาด'; ?></h1>
        <p><?php echo $message; ?></p>
        <p class="countdown" id="countdown">5</p>
        <p>จะกลับไปยังหน้าเข้าสู่ระบบใน <span id="seconds"></span> วินาที</p>
    </div>

    <script>
        // นับถอยหลัง 5 วินาที
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const secondsElement = document.getElementById('seconds');

        function updateCountdown() {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown === 0) {
                window.location.href = 'register.html';  // ไปหน้า login เมื่อหมดเวลา
            }
        }

        setInterval(updateCountdown, 1000);  // อัพเดททุก 1 วินาที
    </script>
</body>

</html>

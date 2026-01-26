<?php
// เริ่มต้น Session
session_start();

// ลบข้อมูลทั้งหมดใน Session
session_unset(); // ลบตัวแปร Session ทั้งหมด
session_destroy(); // ทำลาย Session

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ (หรือหน้าแรก)
header("Location: login.php"); // ระบุ URL ของหน้าที่ต้องการเปลี่ยนเส้นทาง
exit();
?>
